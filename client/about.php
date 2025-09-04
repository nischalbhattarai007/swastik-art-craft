<?php
$pageTitle = 'About Us - Swastik Art & Craft';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-amber-600 to-orange-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-4">About Swastik Art & Craft</h1>
        <p class="text-xl opacity-90">Preserving traditions, creating beauty</p>
    </div>
</section>

<!-- About Content -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
                <p class="text-gray-600 mb-6">
                    Founded with a passion for preserving Nepal's rich artistic heritage, Swastik Art & Craft has been 
                    a beacon of traditional craftsmanship for years. Located in the heart of Kathmandu, we specialize 
                    in authentic handcrafted items that tell the story of our culture.
                </p>
                <p class="text-gray-600 mb-6">
                    Every piece in our collection is carefully selected and crafted by skilled artisans who have 
                    inherited their techniques through generations. From intricate wood carvings to beautiful textiles, 
                    our products represent the finest quality and authentic craftsmanship that Nepal has to offer.
                </p>
                <p class="text-gray-600">
                    We believe in supporting local communities and preserving traditional art forms while making 
                    them accessible to art lovers around the world.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <i class="fas fa-palette text-4xl text-amber-600 mb-4"></i>
                    <h3 class="font-semibold mb-2">Handcrafted</h3>
                    <p class="text-sm text-gray-600">Every item is made with love and traditional techniques</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <i class="fas fa-award text-4xl text-amber-600 mb-4"></i>
                    <h3 class="font-semibold mb-2">Quality</h3>
                    <p class="text-sm text-gray-600">Premium materials and exceptional craftsmanship</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <i class="fas fa-heart text-4xl text-amber-600 mb-4"></i>
                    <h3 class="font-semibold mb-2">Authentic</h3>
                    <p class="text-sm text-gray-600">Genuine traditional art forms and designs</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <i class="fas fa-users text-4xl text-amber-600 mb-4"></i>
                    <h3 class="font-semibold mb-2">Community</h3>
                    <p class="text-sm text-gray-600">Supporting local artisans and their families</p>
                </div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="bg-amber-50 p-8 rounded-lg">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                <p class="text-gray-600">
                    To preserve and promote Nepal's rich artistic heritage by providing a platform for traditional 
                    artisans to showcase their skills and connect with art enthusiasts worldwide. We strive to 
                    maintain the authenticity of traditional crafts while ensuring fair compensation for our artisans.
                </p>
            </div>
            <div class="bg-orange-50 p-8 rounded-lg">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                <p class="text-gray-600">
                    To become the leading destination for authentic Nepalese handicrafts, fostering cultural 
                    appreciation and supporting sustainable livelihoods for artisan communities. We envision a 
                    world where traditional art forms thrive alongside modern innovation.
                </p>
            </div>
        </div>

        <!-- Values -->
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Our Values</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-amber-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-amber-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Sustainability</h3>
                    <p class="text-gray-600">We use eco-friendly materials and sustainable practices in all our products.</p>
                </div>
                <div class="text-center">
                    <div class="bg-amber-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-amber-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Fair Trade</h3>
                    <p class="text-gray-600">We ensure fair compensation and working conditions for all our artisans.</p>
                </div>
                <div class="text-center">
                    <div class="bg-amber-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-amber-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Excellence</h3>
                    <p class="text-gray-600">We maintain the highest standards of quality in every product we offer.</p>
                </div>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-lg p-8 text-center text-white">
            <h2 class="text-2xl font-bold mb-4">Ready to Explore Our Collection?</h2>
            <p class="mb-6 opacity-90">Discover authentic handcrafted items that celebrate Nepal's artistic heritage.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="products.php" class="bg-white text-amber-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                    Browse Products
                </a>
                <a href="contact.php" class="border-2 border-white text-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-amber-600 transition-colors">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>