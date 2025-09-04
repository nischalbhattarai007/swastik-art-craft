<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";
$product = null;

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header("Location: products.php");
    exit();
}

// Fetch product data
try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header("Location: products.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Failed to fetch product: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    if (empty($name)) {
        $error = "Product name is required.";
    } elseif ($price <= 0) {
        $error = "Price must be greater than 0.";
    } else {
        try {
            $image_data = $product['image_data'];
            $image_type = $product['image_type'];
            
            // Handle new image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $file_type = $_FILES['image']['type'];
                
                if (in_array($file_type, $allowed_types)) {
                    $image_data = file_get_contents($_FILES['image']['tmp_name']);
                    $image_type = $file_type;
                } else {
                    $error = "Invalid image type.";
                }
            }
            
            if (empty($error)) {
                $stmt = $conn->prepare("
                    UPDATE products 
                    SET name = :name, description = :description, price = :price, 
                        category_id = :category_id, is_active = :is_active, 
                        is_featured = :is_featured, image_data = :image_data, 
                        image_type = :image_type, updated_at = NOW()
                    WHERE id = :id
                ");
                
                $stmt->execute([
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'category_id' => $category_id > 0 ? $category_id : null,
                    'is_active' => $is_active,
                    'is_featured' => $is_featured,
                    'image_data' => $image_data,
                    'image_type' => $image_type,
                    'id' => $product_id
                ]);
                
                $success = "Product updated successfully!";
                
                // Refresh product data
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
                $stmt->execute(['id' => $product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch categories
try {
    $categoriesStmt = $conn->query("SELECT id, name FROM categories WHERE is_active = 1 ORDER BY name");
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Swastik Art & Craft Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">

<?php include "../assets/partials/navbar.php"; ?>

<div class="lg:ml-64 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="products.php" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-8">
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                <span class="text-red-700"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-green-700"><?= htmlspecialchars($success) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                    <input type="number" name="price" value="<?= $product['price'] ?>" step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="0">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $product['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                    <?php if ($product['image_data']): ?>
                        <div class="mb-4">
                            <img src="data:<?= $product['image_type'] ?>;base64,<?= base64_encode($product['image_data']) ?>" 
                                 class="w-32 h-32 object-cover rounded-lg border">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-amber-50 file:text-amber-700">
                </div>

                <div class="md:col-span-2">
                    <div class="flex gap-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" <?= $product['is_active'] ? 'checked' : '' ?>
                                   class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" <?= $product['is_featured'] ? 'checked' : '' ?>
                                   class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                            <span class="ml-2 text-sm text-gray-700">Featured</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="products.php" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-lg hover:from-amber-700 hover:to-orange-700">
                    <i class="fas fa-save mr-2"></i>Update Product
                </button>
            </div>
        </form>
    </div>
    </div>
</div>

</body>
</html>