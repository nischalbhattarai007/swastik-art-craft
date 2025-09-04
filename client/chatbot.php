<?php
$pageTitle = 'Chatbot - Swastik Art & Craft';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-amber-600 to-orange-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-4">Chat Assistant</h1>
        <p class="text-xl opacity-90">Get instant help with our AI-powered chat assistant</p>
    </div>
</section>

<!-- Chatbot Features -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">How Our Chat Assistant Helps You</h2>
            <p class="text-xl text-gray-600">Get instant answers to your questions about our products and services</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <div class="bg-white p-8 rounded-2xl shadow-lg text-center hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-bag text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Product Information</h3>
                <p class="text-gray-600">Ask about our handcrafted items, materials, and crafting techniques</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg text-center hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shipping-fast text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Shipping & Delivery</h3>
                <p class="text-gray-600">Get information about shipping costs, delivery times, and packaging</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg text-center hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-phone text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Contact Support</h3>
                <p class="text-gray-600">Get our contact details and business hours instantly</p>
            </div>
        </div>

        <!-- Demo Chat Interface -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-amber-600 to-orange-600 text-white p-6">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-robot mr-3"></i>
                        Try Our Chat Assistant
                    </h3>
                    <p class="opacity-90 mt-2">Click the chat button in the bottom right corner to start chatting!</p>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-robot text-white"></i>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg flex-1">
                                <p class="text-gray-800">Hello! I'm here to help you with information about Swastik Art & Craft. What would you like to know?</p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <div class="bg-gradient-to-r from-amber-600 to-orange-600 text-white p-4 rounded-lg inline-block">
                                <p>Tell me about your products</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-robot text-white"></i>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg flex-1">
                                <p class="text-gray-800">We offer a wide range of handcrafted items including pottery, textiles, wood carvings, and traditional art pieces. Our products are made by skilled artisans using traditional techniques passed down through generations.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 text-center">
                        <button onclick="chatbot.toggle()" class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-8 py-3 rounded-full hover:from-amber-700 hover:to-orange-700 transition-all transform hover:scale-105">
                            <i class="fas fa-comments mr-2"></i>Start Chatting Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>