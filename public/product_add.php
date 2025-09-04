<?php
session_start();
require_once "../config/db.php";

// Auth check
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Validation
    if (empty($name)) {
        $error = "Product name is required.";
    } elseif ($price <= 0) {
        $error = "Price must be greater than 0.";
    } else {
        try {
            // Handle image upload
            $image_data = null;
            $image_type = null;
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $file_type = $_FILES['image']['type'];
                
                if (in_array($file_type, $allowed_types)) {
                    $image_data = file_get_contents($_FILES['image']['tmp_name']);
                    $image_type = $file_type;
                } else {
                    $error = "Invalid image type. Please upload JPEG, PNG, GIF, or WebP images only.";
                }
            }
            
            if (empty($error)) {
                $stmt = $conn->prepare("
                    INSERT INTO products (name, description, price, category_id, is_active, is_featured, image_data, image_type) 
                    VALUES (:name, :description, :price, :category_id, :is_active, :is_featured, :image_data, :image_type)
                ");
                
                $stmt->execute([
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'category_id' => $category_id > 0 ? $category_id : null,
                    'is_active' => $is_active,
                    'is_featured' => $is_featured,
                    'image_data' => $image_data,
                    'image_type' => $image_type
                ]);
                
                $success = "Product added successfully!";
                // Clear form data
                $name = $description = $price = $category_id = "";
                $is_active = $is_featured = 0;
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
    <title>Add Product - Swastik Art & Craft Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

<!-- Sidebar -->
<?php include "../assets/partials/navbar.php"; ?>

<div class="lg:ml-64 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 fade-in">
        <div class="flex items-center mb-4">
            <a href="products.php" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
        </div>
        <p class="text-gray-600">Create a new product for your art and craft store</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm p-8 fade-in">
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <span class="text-red-700"><?= htmlspecialchars($error) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-green-700"><?= htmlspecialchars($success) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-gray-500"></i>Product Name *
                    </label>
                    <input type="text" 
                           name="name" 
                           value="<?= htmlspecialchars($name ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300" 
                           placeholder="Enter product name"
                           required>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-rupee-sign mr-2 text-gray-500"></i>Price *
                    </label>
                    <input type="number" 
                           name="price" 
                           value="<?= htmlspecialchars($price ?? '') ?>"
                           step="0.01" 
                           min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300" 
                           placeholder="0.00"
                           required>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-list mr-2 text-gray-500"></i>Category
                    </label>
                    <select name="category_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300">
                        <option value="0">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= (isset($category_id) && $category_id == $category['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-gray-500"></i>Description
                    </label>
                    <textarea name="description" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300" 
                              placeholder="Enter product description"><?= htmlspecialchars($description ?? '') ?></textarea>
                </div>

                <!-- Image Upload -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image mr-2 text-gray-500"></i>Product Image
                    </label>
                    <div class="flex items-center space-x-4">
                        <input type="file" 
                               name="image" 
                               accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all duration-300"
                               onchange="previewImage(this)">
                    </div>
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="preview" class="image-preview rounded-lg border border-gray-300" alt="Preview">
                    </div>
                </div>

                <!-- Status Options -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        <i class="fas fa-cog mr-2 text-gray-500"></i>Product Status
                    </label>
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   <?= (isset($is_active) && $is_active) ? 'checked' : 'checked' ?>
                                   class="w-4 h-4 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500 focus:ring-2">
                            <span class="ml-2 text-sm text-gray-700">Active (visible to customers)</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_featured" 
                                   value="1"
                                   <?= (isset($is_featured) && $is_featured) ? 'checked' : '' ?>
                                   class="w-4 h-4 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500 focus:ring-2">
                            <span class="ml-2 text-sm text-gray-700">Featured product</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="products.php" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-300">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-lg font-medium hover:from-amber-700 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>Add Product
                </button>
            </div>
        </form>
    </div>
    </div>
</div>

<script src="../assets/js/common.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.querySelector('input[name="image"]');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            previewImage(this);
        });
    }
});
</script>

</body>
</html>