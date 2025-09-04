<?php
session_start();
require_once "../config/db.php";

// Auth check
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

try {
    // 1. Total products
    $totalProductsStmt = $conn->query("SELECT COUNT(*) as total FROM products");
    $totalProducts = $totalProductsStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 2. Active products
    $activeProductsStmt = $conn->query("SELECT COUNT(*) as total FROM products WHERE is_active = 1");
    $activeProducts = $activeProductsStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 3. Featured products
    $featuredStmt = $conn->query("SELECT COUNT(*) as total FROM products WHERE is_featured = 1");
    $featuredProducts = $featuredStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 4. Categories
    $categoriesStmt = $conn->query("SELECT COUNT(*) as total FROM categories WHERE is_active = 1");
    $totalCategories = $categoriesStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 5. Recent products (last 7 days)
    $recentProductsStmt = $conn->query("SELECT COUNT(*) as total FROM products WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $recentProducts = $recentProductsStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 6. Total orders
    $totalOrdersStmt = $conn->query("SELECT COUNT(*) as total FROM orders");
    $totalOrders = $totalOrdersStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 7. Contact messages
    $contactMessagesStmt = $conn->query("SELECT COUNT(*) as total FROM contact_messages");
    $contactMessages = $contactMessagesStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // 8. Today's messages
    $todayMessagesStmt = $conn->query("SELECT COUNT(*) as total FROM contact_messages WHERE DATE(created_at) = CURDATE()");
    $todayMessages = $todayMessagesStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 8. Pie chart data - Products by category
    $chartStmt = $conn->query("
        SELECT c.name, COUNT(p.id) as count 
        FROM categories c 
        LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
        WHERE c.is_active = 1
        GROUP BY c.id, c.name
        ORDER BY count DESC
    ");
    $chartData = [];
    while ($row = $chartStmt->fetch(PDO::FETCH_ASSOC)) {
        $chartData[] = $row;
    }

    // 9. Recent products for display
    $recentProductsListStmt = $conn->query("
        SELECT p.name, p.price, c.name as category_name, p.created_at
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC 
        LIMIT 5
    ");
    $recentProductsList = $recentProductsListStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Log the error
    error_log("Database Error: " . $e->getMessage());
    // Set default values
    $totalProducts = 0;
    $activeProducts = 0;
    $featuredProducts = 0;
    $totalCategories = 0;
    $recentProducts = 0;
    $totalOrders = 0;
    $contactMessages = 0;
    $chartData = [];
    $recentProductsList = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Swastik Art & Craft Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    <div class="mb-8 fade-in">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Overview</h1>
        <p class="text-gray-600">Welcome back, <?= htmlspecialchars($_SESSION['admin_username']) ?>! Here's what's happening with your store.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $totalProducts ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500"><?= $activeProducts ?> active products</span>
            </div>
        </div>

        <!-- Featured Products -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Featured Products</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $featuredProducts ?></p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-star text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="featured.php" class="text-sm text-blue-600 hover:text-blue-800">Manage featured →</a>
            </div>
        </div>

        <!-- Categories -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Categories</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $totalCategories ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-tags text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="categories.php" class="text-sm text-blue-600 hover:text-blue-800">Manage categories →</a>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">New This Week</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $recentProducts ?></p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-plus-circle text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="product_add.php" class="text-sm text-blue-600 hover:text-blue-800">Add new product →</a>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $totalOrders ?></p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-full">
                    <i class="fas fa-shopping-cart text-indigo-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Contact Messages -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Contact Messages</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $contactMessages ?></p>
                </div>
                <div class="bg-pink-100 p-3 rounded-full">
                    <i class="fas fa-envelope text-pink-600 text-lg"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500"><?= $todayMessages ?> new today</span>
                <a href="messages.php" class="ml-4 text-sm text-blue-600 hover:text-blue-800">View messages →</a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl shadow-sm p-6 text-white card-hover transition-all duration-300 fade-in">
            <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="product_add.php" class="block text-sm hover:text-gray-200 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add New Product
                </a>
                <a href="categories.php" class="block text-sm hover:text-gray-200 transition-colors">
                    <i class="fas fa-tags mr-2"></i>Manage Categories
                </a>
                <a href="featured.php" class="block text-sm hover:text-gray-200 transition-colors">
                    <i class="fas fa-star mr-2"></i>Featured Products
                </a>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Distribution Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 fade-in">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Product Distribution by Category</h2>
            <?php if (!empty($chartData)): ?>
                <div class="relative h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            <?php else: ?>
                <div class="flex items-center justify-center h-64 text-gray-500">
                    <div class="text-center">
                        <i class="fas fa-chart-pie text-4xl mb-4"></i>
                        <p>No data available</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Products -->
        <div class="bg-white rounded-xl shadow-sm p-6 fade-in">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Recent Products</h2>
                <a href="products.php" class="text-sm text-blue-600 hover:text-blue-800">View all →</a>
            </div>
            
            <?php if (!empty($recentProductsList)): ?>
                <div class="space-y-4">
                    <?php foreach ($recentProductsList as $product): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($product['category_name'] ?? 'No Category') ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">Rs.<?= number_format($product['price'], 2) ?></p>
                                <p class="text-xs text-gray-500"><?= date('M j, Y', strtotime($product['created_at'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="flex items-center justify-center h-32 text-gray-500">
                    <div class="text-center">
                        <i class="fas fa-box-open text-3xl mb-2"></i>
                        <p>No recent products</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>

<?php if (!empty($chartData)): ?>
<script>
const ctx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($chartData, 'name')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($chartData, 'count')) ?>,
            backgroundColor: [
                '#F59E0B', '#EF4444', '#10B981', '#3B82F6', '#8B5CF6', '#F97316', '#06B6D4', '#84CC16'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} products (${percentage}%)`;
                    }
                }
            }
        },
        cutout: '60%'
    }
});
</script>
<?php endif; ?>

</body>
</html>
