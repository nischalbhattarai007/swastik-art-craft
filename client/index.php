<?php
require_once 'includes/db.php';
$pageTitle = 'Home - Swastik Art & Craft';

// Fetch featured products
try {
    $stmt = $conn->query("
        SELECT p.id, p.name, p.price, p.image_data, p.image_type, c.name as category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_featured = 1 AND p.is_active = 1
        LIMIT 8
    ");
    $featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featuredProducts = [];
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="../assets/images/bowl2.jpg" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    </div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 z-10">
        <div class="floating-element absolute top-20 left-10 w-20 h-20 bg-amber-400 rounded-full opacity-20 animate-float"></div>
        <div class="floating-element absolute top-40 right-20 w-16 h-16 bg-orange-400 rounded-full opacity-30 animate-float-delayed"></div>
        <div class="floating-element absolute bottom-32 left-1/4 w-12 h-12 bg-yellow-400 rounded-full opacity-25 animate-float-slow"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <div class="animate-fade-in-up">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-400">
                    Swastik Art & Craft
                </span>
            </h1>
            <p class="text-xl md:text-3xl mb-8 opacity-90 max-w-3xl mx-auto leading-relaxed">
                Discover Beautiful Handcrafted Art & Traditional Crafts Made with Love
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="products.php" class="group bg-gradient-to-r from-amber-500 to-orange-500 text-white px-10 py-4 rounded-full font-semibold text-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Shop Now
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="about.php" class="group border-2 border-white text-white px-10 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-amber-600 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-info-circle mr-2"></i>
                    Learn More
                </a>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-2xl opacity-70"></i>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-20 bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-20 w-40 h-40 bg-amber-300 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-60 h-60 bg-orange-300 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-yellow-300 rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                Featured <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-600">Products</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Handpicked items crafted with love and traditional techniques</p>
        </div>

        <?php if (!empty($featuredProducts)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" id="productsGrid">
                <?php foreach ($featuredProducts as $index => $product): ?>
                    <div class="product-card opacity-0 transform translate-y-8 group bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-4 border border-amber-100" 
                         style="animation-delay: <?= $index * 0.1 ?>s">
                        <a href="product-details.php?id=<?= $product['id'] ?>">
                            <div class="relative aspect-w-1 aspect-h-1 w-full h-72 bg-gradient-to-br from-amber-50 to-orange-50 overflow-hidden">
                                <?php if ($product['image_data']): ?>
                                    <img src="data:<?= $product['image_type'] ?>;base64,<?= base64_encode($product['image_data']) ?>" 
                                         alt="<?= htmlspecialchars($product['name']) ?>"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-400 to-orange-400">
                                        <i class="fas fa-image text-white text-5xl"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                
                                <!-- Floating Badge -->
                                <div class="absolute top-4 left-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                    Featured
                                </div>
                            </div>
                            <div class="p-6 bg-gradient-to-br from-white to-amber-50/30">
                                <h3 class="font-bold text-xl text-gray-900 mb-3 group-hover:text-amber-600 transition-colors line-clamp-2"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="text-sm text-gray-500 mb-4 bg-gray-100 px-3 py-1 rounded-full inline-block"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></p>
                                <div class="flex items-center justify-between">
                                    <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-600">Rs.<?= number_format($product['price'], 2) ?></p>
                                    <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <i class="fas fa-arrow-right text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-16">
                <a href="products.php" class="group inline-flex items-center bg-gradient-to-r from-amber-600 to-orange-600 text-white px-10 py-4 rounded-full font-semibold text-lg hover:from-amber-700 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    View All Products
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">No featured products available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- About Section -->
<section class="relative py-20 bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-10 left-10 w-32 h-32 bg-amber-400 rounded-lg opacity-10 rotate-45 animate-pulse"></div>
    <div class="absolute top-32 right-20 w-24 h-24 bg-orange-400 rounded-full opacity-15 animate-bounce"></div>
    <div class="absolute bottom-20 left-1/4 w-20 h-20 bg-yellow-400 rounded-full opacity-20 animate-ping"></div>
    <div class="absolute bottom-40 right-1/3 w-16 h-16 bg-amber-500 rounded-lg opacity-10 rotate-12 animate-pulse"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="animate-fade-in-left">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8 leading-tight">
                    About Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-600">Craft</span>
                </h2>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    At Swastik Art & Craft, we celebrate the rich tradition of Indian handicrafts. 
                    Each piece in our collection is carefully crafted by skilled artisans who have 
                    inherited their techniques through generations.
                </p>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    From intricate pottery to beautiful textiles, our products represent the 
                    finest quality and authentic craftsmanship that India has to offer.
                </p>
                <a href="about.php" class="group inline-flex items-center bg-gradient-to-r from-amber-600 to-orange-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:from-amber-700 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    Learn More
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            <div class="grid grid-cols-2 gap-6 animate-fade-in-right">
                <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl text-center transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-palette text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">Handcrafted</h3>
                    <p class="text-gray-600">Made with love and care by skilled artisans</p>
                </div>
                <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl text-center transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-award text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">Quality</h3>
                    <p class="text-gray-600">Premium materials and finest craftsmanship</p>
                </div>
                <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl text-center transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-shipping-fast text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">Fast Delivery</h3>
                    <p class="text-gray-600">Quick and safe shipping worldwide</p>
                </div>
                <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl text-center transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-heart text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">Authentic</h3>
                    <p class="text-gray-600">Traditional techniques passed down generations</p>
                </div>
            </div>
        </div>
    </div>
</section>



// Intersection Observer for scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate');
        }
    });
}, observerOptions);

// Observe product cards
document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card, index) => {
        card.classList.add('animate-on-scroll');
        setTimeout(() => {
            observer.observe(card);
        }, index * 100);
    });
    
    // Animate featured products section title
    const featuredTitle = document.querySelector('section h2');
    if (featuredTitle) {
        observer.observe(featuredTitle);
    }
});

// Smooth scroll behavior for better UX
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.parallax-bg');
    if (parallax) {
        parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});
</script>

<!-- Custom CSS for animations -->
<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes float-delayed {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

@keyframes float-slow {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-left {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fade-in-right {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
    animation: float-delayed 8s ease-in-out infinite;
}

.animate-float-slow {
    animation: float-slow 10s ease-in-out infinite;
}

.animate-fade-in-up {
    animation: fade-in-up 1s ease-out;
}

.animate-fade-in-left {
    animation: fade-in-left 1s ease-out;
}

.animate-fade-in-right {
    animation: fade-in-right 1s ease-out;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom gradient text */
.gradient-text {
    background: linear-gradient(135deg, #f59e0b, #ea580c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Product card animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-card {
    animation: slideInUp 0.6s ease-out forwards;
}

/* Intersection Observer Animation */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease-out;
}

.animate-on-scroll.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>


<?php include 'includes/footer.php'; ?>