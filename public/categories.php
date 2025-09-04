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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = trim($_POST['name']);
                if (empty($name)) {
                    $error = "Category name is required.";
                } else {
                    try {
                        $stmt = $conn->prepare("INSERT INTO categories (name, is_active) VALUES (:name, 1)");
                        $stmt->execute(['name' => $name]);
                        $success = "Category added successfully!";
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) {
                            $error = "Category name already exists.";
                        } else {
                            $error = "Database error: " . $e->getMessage();
                        }
                    }
                }
                break;
                
            case 'edit':
                $id = intval($_POST['id']);
                $name = trim($_POST['name']);
                if (empty($name)) {
                    $error = "Category name is required.";
                } else {
                    try {
                        $stmt = $conn->prepare("UPDATE categories SET name = :name WHERE id = :id");
                        $stmt->execute(['name' => $name, 'id' => $id]);
                        $success = "Category updated successfully!";
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) {
                            $error = "Category name already exists.";
                        } else {
                            $error = "Database error: " . $e->getMessage();
                        }
                    }
                }
                break;
        }
    }
}

// Handle GET actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'toggle':
            $id = intval($_GET['id']);
            try {
                $stmt = $conn->prepare("UPDATE categories SET is_active = NOT is_active WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $success = "Category status updated successfully!";
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
            break;
            
        case 'delete':
            $id = intval($_GET['id']);
            try {
                // Check if category has products
                $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = :id");
                $checkStmt->execute(['id' => $id]);
                $productCount = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                if ($productCount > 0) {
                    $error = "Cannot delete category. It has {$productCount} products associated with it.";
                } else {
                    $stmt = $conn->prepare("DELETE FROM categories WHERE id = :id");
                    $stmt->execute(['id' => $id]);
                    $success = "Category deleted successfully!";
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
            break;
    }
}

// Fetch categories with product count
try {
    $stmt = $conn->query("
        SELECT c.id, c.name, c.is_active, c.created_at, COUNT(p.id) as product_count
        FROM categories c
        LEFT JOIN products p ON c.id = p.category_id
        GROUP BY c.id, c.name, c.is_active, c.created_at
        ORDER BY c.created_at DESC
    ");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
    $error = "Failed to fetch categories: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management - Swastik Art & Craft Admin</title>
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

<!-- Main Content -->
<div class="lg:ml-64 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8 fade-in">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Categories Management</h1>
            <p class="text-gray-600 mt-2">Organize your products with categories</p>
        </div>
        <button onclick="openAddModal()" 
                class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-6 py-3 rounded-lg font-medium hover:from-amber-700 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus mr-2"></i>Add Category
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 fade-in">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-tags text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Categories</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($categories) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 fade-in">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Active Categories</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($categories, fn($c) => $c['is_active'])) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 fade-in">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-box text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900"><?= array_sum(array_column($categories, 'product_count')) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <?php if (!empty($error)): ?>
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                <span class="text-red-700"><?= htmlspecialchars($error) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-green-700"><?= htmlspecialchars($success) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in">
        <?php foreach ($categories as $category): ?>
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2"><?= htmlspecialchars($category['name']) ?></h3>
                        <p class="text-sm text-gray-500 mb-2">
                            <i class="fas fa-box mr-1"></i>
                            <?= $category['product_count'] ?> products
                        </p>
                        <p class="text-xs text-gray-400">
                            Created: <?= date('M j, Y', strtotime($category['created_at'])) ?>
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $category['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $category['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex space-x-2">
                        <button onclick="openEditModal(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name'], ENT_QUOTES) ?>')" 
                                class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="?action=toggle&id=<?= $category['id'] ?>" 
                           class="text-<?= $category['is_active'] ? 'yellow' : 'green' ?>-600 hover:text-<?= $category['is_active'] ? 'yellow' : 'green' ?>-800 transition-colors duration-200">
                            <i class="fas fa-<?= $category['is_active'] ? 'pause' : 'play' ?>"></i>
                        </a>
                        <?php if ($category['product_count'] == 0): ?>
                            <button onclick="deleteCategory(<?= $category['id'] ?>)" 
                                    class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                    <a href="products.php?category=<?= $category['id'] ?>" 
                       class="text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        View products →
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($categories)): ?>
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500">
                    <i class="fas fa-tags text-4xl mb-4"></i>
                    <p class="text-lg font-medium">No categories found</p>
                    <p class="text-sm">Get started by adding your first category</p>
                    <button onclick="openAddModal()" 
                            class="mt-4 inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Add Category
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Add New Category</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                <input type="text" 
                       name="name" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                       placeholder="Enter category name"
                       required>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeAddModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                    Add Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Edit Category</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="editCategoryId">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                <input type="text" 
                       name="name" 
                       id="editCategoryName"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                       placeholder="Enter category name"
                       required>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeEditModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Category</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this category? This action cannot be undone.</p>
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
let deleteCategoryId = null;

function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
    document.querySelector('#addModal input[name="name"]').focus();
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
}

function openEditModal(id, name) {
    document.getElementById('editCategoryId').value = id;
    document.getElementById('editCategoryName').value = name;
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    document.getElementById('editCategoryName').focus();
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

function deleteCategory(id) {
    deleteCategoryId = id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
    deleteCategoryId = null;
}

function confirmDelete() {
    if (deleteCategoryId) {
        window.location.href = `?action=delete&id=${deleteCategoryId}`;
    }
}

// Close modals when clicking outside
document.getElementById('addModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddModal();
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>

</body>
</html>