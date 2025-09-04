<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$success = "";

// Handle feature toggle
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("UPDATE products SET is_featured = NOT is_featured WHERE id = :id");
        $stmt->execute(['id' => $_GET['id']]);
        $success = "Product featured status updated!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch featured products
try {
    $featuredStmt = $conn->query("
        SELECT p.id, p.name, p.price, p.is_active, c.name as category_name, p.created_at, p.image_data, p.image_type
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_featured = 1
        ORDER BY p.created_at DESC
    ");
    $featuredProducts = $featuredStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch non-featured products
    $nonFeaturedStmt = $conn->query("
        SELECT p.id, p.name, p.price, p.is_active, c.name as category_name, p.image_data, p.image_type
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_featured = 0 AND p.is_active = 1
        ORDER BY p.name
    ");
    $nonFeaturedProducts = $nonFeaturedStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featuredProducts = [];
    $nonFeaturedProducts = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Featured Products - Swastik Art & Craft Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">

<?php include "../assets/partials/navbar.php"; ?>

<div class="lg:ml-64 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Featured Products</h1>
        <p class="text-gray-600 mt-2">Manage products that appear prominently on your website</p>
    </div>

    <?php if (!empty($success)): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
            <i class="fas fa-check-circle text-green-500 mr-2"></i>
            <span class="text-green-700"><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Featured Products -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>Featured Products
                </h2>
                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                    <?= count($featuredProducts) ?> products
                </span>
            </div>

            <?php if (empty($featuredProducts)): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-star text-4xl mb-4"></i>
                    <p>No featured products yet</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($featuredProducts as $product): ?>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center flex-1">
                                <div class="w-12 h-12 rounded-lg overflow-hidden mr-3 flex-shrink-0">
                                    <?php if ($product['image_data']): ?>
                                        <img src="data:<?= $product['image_type'] ?>;base64,<?= base64_encode($product['image_data']) ?>" 
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gradient-to-r from-amber-400 to-orange-400 flex items-center justify-center">
                                            <i class="fas fa-image text-white text-sm"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900"><?= htmlspecialchars($product['name']) ?></h3>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($product['category_name'] ?? 'No Category') ?></p>
                                    <p class="text-sm font-medium text-gray-900">Rs.<?= number_format($product['price'], 2) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs rounded-full <?= $product['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                                <button onclick="removeFeatured(<?= $product['id'] ?>)" 
                                        class="text-yellow-500 hover:text-red-600 p-2 transition-colors duration-200"
                                        title="Remove from featured">
                                    <i class="fas fa-star"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Available Products -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-plus-circle text-blue-500 mr-2"></i>Available Products
                </h2>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    <?= count($nonFeaturedProducts) ?> products
                </span>
            </div>

            <?php if (empty($nonFeaturedProducts)): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box text-4xl mb-4"></i>
                    <p>All active products are featured</p>
                </div>
            <?php else: ?>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <?php foreach ($nonFeaturedProducts as $product): ?>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center flex-1">
                                <div class="w-12 h-12 rounded-lg overflow-hidden mr-3 flex-shrink-0">
                                    <?php if ($product['image_data']): ?>
                                        <img src="data:<?= $product['image_type'] ?>;base64,<?= base64_encode($product['image_data']) ?>" 
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gradient-to-r from-blue-400 to-purple-400 flex items-center justify-center">
                                            <i class="fas fa-image text-white text-sm"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900"><?= htmlspecialchars($product['name']) ?></h3>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($product['category_name'] ?? 'No Category') ?></p>
                                    <p class="text-sm font-medium text-gray-900">Rs. <?= number_format($product['price'], 2) ?></p>
                                </div>
                            </div>
                            <a href="?toggle=1&id=<?= $product['id'] ?>" 
                               class="text-yellow-600 hover:text-yellow-800 p-2"
                               title="Add to featured">
                                <i class="fas fa-star"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>

<!-- Remove Featured Confirmation Modal -->
<div id="removeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-star-slash text-yellow-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Remove from Featured</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to remove this product from featured list?</p>
            <div class="flex space-x-3">
                <button onclick="closeRemoveModal()" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button onclick="confirmRemove()" 
                        class="flex-1 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    Remove
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let removeProductId = null;

function removeFeatured(id) {
    removeProductId = id;
    document.getElementById('removeModal').classList.remove('hidden');
    document.getElementById('removeModal').classList.add('flex');
}

function closeRemoveModal() {
    document.getElementById('removeModal').classList.add('hidden');
    document.getElementById('removeModal').classList.remove('flex');
    removeProductId = null;
}

function confirmRemove() {
    if (removeProductId) {
        window.location.href = `?toggle=1&id=${removeProductId}`;
    }
}

// Close modal when clicking outside
document.getElementById('removeModal').addEventListener('click', function(e) {
    if (e.target === this) closeRemoveModal();
});
</script>

</body>
</html>