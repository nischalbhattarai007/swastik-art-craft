<?php
session_start();
require_once "../config/db.php";

// Auth check
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Handle Delete action
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $_GET['delete']]);
    header("Location: products.php");
    exit();
}

// Handle Bulk Actions
if (isset($_POST['bulk_action']) && isset($_POST['selected_products'])) {
    $action = $_POST['bulk_action'];
    $product_ids = $_POST['selected_products'];
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    
    switch ($action) {
        case 'activate':
            $stmt = $conn->prepare("UPDATE products SET is_active = 1 WHERE id IN ($placeholders)");
            break;
        case 'deactivate':
            $stmt = $conn->prepare("UPDATE products SET is_active = 0 WHERE id IN ($placeholders)");
            break;
        case 'feature':
            $stmt = $conn->prepare("UPDATE products SET is_featured = 1 WHERE id IN ($placeholders)");
            break;
        case 'unfeature':
            $stmt = $conn->prepare("UPDATE products SET is_featured = 0 WHERE id IN ($placeholders)");
            break;
        case 'delete':
            $stmt = $conn->prepare("DELETE FROM products WHERE id IN ($placeholders)");
            break;
    }
    
    if (isset($stmt)) {
        $stmt->execute($product_ids);
        header("Location: products.php");
        exit();
    }
}

// Handle Feature toggle
if (isset($_GET['feature'])) {
    $stmt = $conn->prepare("UPDATE products SET is_featured = NOT is_featured WHERE id = :id");
    $stmt->execute(['id' => $_GET['feature']]);
    header("Location: products.php");
    exit();
}

// Handle Active toggle
if (isset($_GET['toggle_active'])) {
    $stmt = $conn->prepare("UPDATE products SET is_active = NOT is_active WHERE id = :id");
    $stmt->execute(['id' => $_GET['toggle_active']]);
    header("Location: products.php");
    exit();
}

// Search and filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "p.name LIKE :search";
    $params['search'] = "%$search%";
}

if ($category_filter > 0) {
    $where_conditions[] = "p.category_id = :category";
    $params['category'] = $category_filter;
}

if ($status_filter === 'active') {
    $where_conditions[] = "p.is_active = 1";
} elseif ($status_filter === 'inactive') {
    $where_conditions[] = "p.is_active = 0";
} elseif ($status_filter === 'featured') {
    $where_conditions[] = "p.is_featured = 1";
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, p.is_featured, p.is_active, c.name AS category_name, p.created_at, p.image_data, p.image_type
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    $where_clause
    ORDER BY p.created_at DESC
");
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all categories for add/edit forms
$catStmt = $conn->query("SELECT * FROM categories");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - Swastik Art & Craft Admin</title>
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

<!-- Sidebar -->
<?php include "../assets/partials/navbar.php"; ?>

<div class="lg:ml-64 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8 fade-in">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Products Management</h1>
            <p class="text-gray-600 mt-2">Manage your art and craft products</p>
        </div>
        <a href="product_add.php" 
           class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-6 py-3 rounded-lg font-medium hover:from-amber-700 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus mr-2"></i>Add New Product
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8 fade-in">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search products..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="0">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $category_filter == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All Status</option>
                    <option value="active" <?= $status_filter === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $status_filter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="featured" <?= $status_filter === 'featured' ? 'selected' : '' ?>>Featured</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <a href="products.php" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 fade-in">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($products) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 fade-in">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Active Products</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($products, fn($p) => $p['is_active'])) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 fade-in">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                    <i class="fas fa-star text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Featured Products</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($products, fn($p) => $p['is_featured'])) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 fade-in">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-tags text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Categories</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($categories) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden fade-in">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Products</h2>
                <div class="flex items-center space-x-4">
                    <form method="POST" id="bulkForm" class="flex items-center space-x-2">
                        <select name="bulk_action" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="feature">Feature</option>
                            <option value="unfeature">Unfeature</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">
                            Apply
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-amber-600 rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($products as $product): ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="selected_products[]" value="<?= $product['id'] ?>" 
                                   class="product-checkbox w-4 h-4 text-amber-600 rounded">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <?php if ($product['image_data']): ?>
                                        <img src="data:<?= $product['image_type'] ?>;base64,<?= base64_encode($product['image_data']) ?>" 
                                             class="h-10 w-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-amber-400 to-orange-400 flex items-center justify-center">
                                            <i class="fas fa-palette text-white text-sm"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($product['name']) ?></div>
                                    <div class="text-sm text-gray-500">ID: <?= $product['id'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?= htmlspecialchars($product['category_name'] ?? 'No Category') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rs.<?= number_format($product['price'], 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="products.php?toggle_active=<?= $product['id'] ?>" 
                               class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 <?= $product['is_active'] ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' ?>">
                                <i class="fas <?= $product['is_active'] ? 'fa-check-circle' : 'fa-times-circle' ?> mr-1"></i>
                                <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="products.php?feature=<?= $product['id'] ?>" 
                               class="text-2xl transition-colors duration-200 <?= $product['is_featured'] ? 'text-yellow-500 hover:text-yellow-600' : 'text-gray-300 hover:text-yellow-400' ?>">
                                <i class="fas fa-star"></i>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= date('M j, Y', strtotime($product['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="product_edit.php?id=<?= $product['id'] ?>" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteProduct(<?= $product['id'] ?>)" 
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No products found</p>
                                <p class="text-sm">Get started by adding your first product</p>
                                <a href="product_add.php" 
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>Add Product
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Product</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this product? This action cannot be undone.</p>
            <div class="flex space-x-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button onclick="confirmDelete()" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteProductId = null;

function deleteProduct(id) {
    deleteProductId = id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
    deleteProductId = null;
}

function confirmDelete() {
    if (deleteProductId) {
        window.location.href = `products.php?delete=${deleteProductId}`;
    }
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Update select all when individual checkboxes change
document.querySelectorAll('.product-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allCheckboxes = document.querySelectorAll('.product-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
        const selectAllCheckbox = document.getElementById('selectAll');
        
        if (checkedCheckboxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCheckboxes.length === allCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    });
});
</script>

</body>
</html>
