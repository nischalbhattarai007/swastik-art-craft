<?php
// Get current page name for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Vertical Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-slate-900 to-slate-800 shadow-2xl transform transition-transform duration-300 ease-in-out lg:translate-x-0" id="sidebar">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-4 bg-slate-900 border-b border-slate-700">
        <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
            <img src="../assets/images/logo.jpg" alt="Swastik Art & Craft" class="w-full h-full object-cover">
        </div>
        <span class="text-white text-lg font-bold">Swastik Admin</span>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 px-4">
        <div class="space-y-2">
            <a href="dashboard.php" 
               class="<?= $current_page == 'dashboard.php' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                Dashboard
            </a>

            <a href="products.php" 
               class="<?= $current_page == 'products.php' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <i class="fas fa-box mr-3 text-lg"></i>
                Products
            </a>

            <a href="categories.php" 
               class="<?= $current_page == 'categories.php' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <i class="fas fa-tags mr-3 text-lg"></i>
                Categories
            </a>

            <a href="featured.php" 
               class="<?= $current_page == 'featured.php' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <i class="fas fa-star mr-3 text-lg"></i>
                Featured
            </a>

            <a href="orders.php" 
               class="<?= $current_page == 'orders.php' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <i class="fas fa-shopping-cart mr-3 text-lg"></i>
                Orders
            </a>

            <a href="messages.php" 
               class="<?= $current_page == 'messages.php' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <i class="fas fa-envelope mr-3 text-lg"></i>
                Messages
            </a>

            <a href="users.php" 
               class="<?= $current_page == 'users.php' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <i class="fas fa-users mr-3 text-lg"></i>
                Users
            </a>

            <a href="settings.php" 
               class="<?= $current_page == 'settings.php' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <i class="fas fa-cog mr-3 text-lg"></i>
                Settings
            </a>
        </div>
    </nav>

    <!-- User Info & Logout -->
    <div class="absolute bottom-0 w-full p-4 border-t border-slate-700">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-user text-white"></i>
            </div>
            <div>
                <p class="text-white text-sm font-medium"><?= htmlspecialchars($_SESSION['admin_username']) ?></p>
                <p class="text-slate-400 text-xs">Administrator</p>
            </div>
        </div>
        <a href="logout.php" 
           class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 flex items-center justify-center shadow-lg">
            <i class="fas fa-sign-out-alt mr-2"></i>
            Logout
        </a>
    </div>
</div>

<!-- Mobile overlay -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" id="sidebar-overlay"></div>

<!-- Mobile menu button -->
<div class="lg:hidden fixed top-4 left-4 z-50">
    <button type="button" 
            class="bg-slate-800 text-white p-3 rounded-xl shadow-lg hover:bg-slate-700 transition-colors"
            onclick="toggleSidebar()">
        <i class="fas fa-bars text-lg"></i>
    </button>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    if (window.innerWidth < 1024) {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
}

// Close sidebar when clicking overlay
document.getElementById('sidebar-overlay').addEventListener('click', toggleSidebar);

// Handle responsive behavior
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    if (window.innerWidth >= 1024) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
    }
});

// Initialize sidebar state
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth < 1024) {
        sidebar.classList.add('-translate-x-full');
    }
});
</script>