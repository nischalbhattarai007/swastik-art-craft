<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-gray-900 to-gray-800 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out" id="sidebar">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-black bg-opacity-20">
        <div class="flex items-center">
            <i class="fas fa-palette text-amber-500 text-2xl mr-3"></i>
            <span class="text-white text-lg font-bold">Swastik Art & Craft</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 px-4">
        <div class="space-y-2">
            <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'bg-amber-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                <i class="fas fa-tachometer-alt mr-3 text-lg"></i>Dashboard
            </a>
            
            <a href="products.php" class="<?= $current_page == 'products.php' ? 'bg-amber-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                <i class="fas fa-box mr-3 text-lg"></i>Products
            </a>
            
            <a href="categories.php" class="<?= $current_page == 'categories.php' ? 'bg-amber-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                <i class="fas fa-tags mr-3 text-lg"></i>Categories
            </a>
            
            <a href="featured.php" class="<?= $current_page == 'featured.php' ? 'bg-amber-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                <i class="fas fa-star mr-3 text-lg"></i>Featured
            </a>
            
            <a href="orders.php" class="<?= $current_page == 'orders.php' ? 'bg-amber-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                <i class="fas fa-shopping-cart mr-3 text-lg"></i>Orders
            </a>
            
            <a href="messages.php" class="<?= $current_page == 'messages.php' ? 'bg-amber-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                <i class="fas fa-envelope mr-3 text-lg"></i>Messages
            </a>
        </div>
        
        <div class="mt-8 pt-8 border-t border-gray-700">
            <a href="settings.php" class="<?= $current_page == 'settings.php' ? 'bg-amber-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                <i class="fas fa-cog mr-3 text-lg"></i>Settings
            </a>
        </div>
    </nav>
    
    <!-- User Info -->
    <div class="absolute bottom-0 left-0 right-0 p-4 bg-black bg-opacity-20">
        <div class="flex items-center">
            <div class="bg-amber-500 w-8 h-8 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate"><?= htmlspecialchars($_SESSION['admin_username']) ?></p>
                <p class="text-xs text-gray-300">Administrator</p>
            </div>
        </div>
        <button onclick="showLogoutConfirm()" 
           class="mt-3 w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
        </button>
    </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Mobile Header -->
<div class="lg:hidden bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-30">
    <div class="flex items-center justify-between h-16 px-4">
        <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div class="flex items-center">
            <i class="fas fa-palette text-amber-600 text-xl mr-2"></i>
            <span class="text-gray-900 font-bold">Admin Panel</span>
        </div>
        <div class="w-6"></div>
    </div>
</div>