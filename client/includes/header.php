<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Swastik Art & Craft' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

<!-- Navigation -->
<nav class="bg-gradient-to-r from-white via-amber-50 to-orange-50 backdrop-blur-md shadow-xl sticky top-0 z-50 border-b border-amber-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-18">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="index.php" class="flex items-center group">
                    <div class="relative">
                        <img src="../assets/images/logo.jpg" alt="Swastik Art & Craft" class="h-12 w-12 rounded-full mr-4 ring-2 ring-amber-200 group-hover:ring-amber-400 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                    </div>
                    <span class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-600 group-hover:from-amber-700 group-hover:to-orange-700 transition-all duration-300">Swastik Art & Craft</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="relative text-gray-700 hover:text-amber-600 transition-all duration-300 font-medium group">
                    <span>Home</span>
                    <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-amber-500 to-orange-500 group-hover:w-full transition-all duration-300"></div>
                </a>
                <a href="products.php" class="relative text-gray-700 hover:text-amber-600 transition-all duration-300 font-medium group">
                    <span>Products</span>
                    <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-amber-500 to-orange-500 group-hover:w-full transition-all duration-300"></div>
                </a>
                <a href="about.php" class="relative text-gray-700 hover:text-amber-600 transition-all duration-300 font-medium group">
                    <span>About</span>
                    <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-amber-500 to-orange-500 group-hover:w-full transition-all duration-300"></div>
                </a>
                <a href="contact.php" class="relative text-gray-700 hover:text-amber-600 transition-all duration-300 font-medium group">
                    <span>Contact</span>
                    <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-amber-500 to-orange-500 group-hover:w-full transition-all duration-300"></div>
                </a>
                
                <?php if (isLoggedIn()): ?>
                    <a href="wishlist.php" class="relative text-gray-700 hover:text-red-500 transition-all duration-300 font-medium group px-3 py-2">
                        <i class="fas fa-heart mr-2 text-red-400 group-hover:text-red-500"></i>Wishlist
                        <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-red-400 to-red-500 group-hover:w-full transition-all duration-300"></div>
                    </a>
                    <div class="relative group">
                        <button class="flex items-center bg-gradient-to-r from-amber-500 to-orange-500 text-white px-6 py-2 rounded-full hover:from-amber-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-user mr-2"></i><?= getUserName() ?>
                            <i class="fas fa-chevron-down ml-2 text-sm"></i>
                        </button>
                        <div class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 border border-amber-100">
                            <a href="logout.php" class="block px-6 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-amber-50 hover:to-orange-50 hover:text-amber-600 transition-all duration-200 rounded-xl">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-6 py-2 rounded-full hover:from-amber-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button onclick="toggleMobileMenu()" class="text-gray-700 hover:text-amber-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobileMenu" class="md:hidden hidden bg-gradient-to-br from-white to-amber-50 border-t border-amber-100">
        <div class="px-4 pt-4 pb-6 space-y-2">
            <a href="index.php" class="block px-4 py-3 text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200">Home</a>
            <a href="products.php" class="block px-4 py-3 text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200">Products</a>
            <a href="about.php" class="block px-4 py-3 text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200">About</a>
            <a href="contact.php" class="block px-4 py-3 text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200">Contact</a>
            
            <?php if (isLoggedIn()): ?>
                <a href="wishlist.php" class="block px-4 py-3 text-gray-700 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
                    <i class="fas fa-heart mr-2 text-red-400"></i>Wishlist
                </a>
                <a href="logout.php" class="block px-4 py-3 text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            <?php else: ?>
                <a href="login.php" class="block mx-3 mt-2 px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-300 text-center font-medium">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>