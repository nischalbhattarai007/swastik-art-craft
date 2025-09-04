<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$productId) {
    header("Location: products.php");
    exit();
}

// Fetch product details
try {
    $stmt = $conn->prepare("
        SELECT p.*, c.name as category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.id = :id AND p.is_active = 1
    ");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header("Location: products.php");
        exit();
    }
    
    $pageTitle = htmlspecialchars($product['name']) . ' - Swastik Art & Craft';
    
    // Fetch related products
    $relatedStmt = $conn->prepare("
        SELECT id, name, price, image_data, image_type
        FROM products 
        WHERE category_id = :category_id AND id != :id AND is_active = 1
        ORDER BY RAND()
        LIMIT 4
    ");
    $relatedStmt->execute(['category_id' => $product['category_id'], 'id' => $productId]);
    $relatedProducts = $relatedStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if product is in wishlist
    $inWishlist = false;
    if (isLoggedIn()) {
        $wishlistStmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = :user_id AND product_id = :product_id");
        $wishlistStmt->execute(['user_id' => getUserId(), 'product_id' => $productId]);
        $inWishlist = $wishlistStmt->fetch() !== false;
    }
    
} catch (PDOException $e) {
    header("Location: products.php");
    exit();
}

include 'includes/header.php';
?>

<!-- Breadcrumb -->
<nav class="bg-gray-100 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="index.php" class="text-amber-600 hover:text-amber-700">Home</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li><a href="products.php" class="text-amber-600 hover:text-amber-700">Products</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li class="text-gray-600"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </div>
</nav>

<!-- Product Details -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div class="space-y-4">
                <div class="aspect-w-1 aspect-h-1 w-full h-96 bg-gray-200 rounded-lg overflow-hidden">
                    <?php if ($product['image_data']): ?>
                        <img src="data:<?= $product['image_type'] ?>;base64,<?= base64_encode($product['image_data']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-amber-400 to-orange-400">
                            <i class="fas fa-image text-white text-6xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <?php if ($product['is_featured']): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 mb-4">
                            <i class="fas fa-star mr-1"></i>Featured Product
                        </span>
                    <?php endif; ?>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <div class="flex items-center space-x-4 mb-6">
                        <span class="text-3xl font-bold text-amber-600">Rs.<?= number_format($product['price'], 2) ?></span>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?>
                        </span>
                    </div>
                </div>

                <?php if (!empty($product['description'])): ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                        <p class="text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <?php if (isLoggedIn()): ?>
                        <button onclick="toggleWishlist(<?= $productId ?>)" 
                                id="wishlistBtn"
                                class="w-full flex items-center justify-center px-6 py-3 border-2 rounded-lg font-medium transition-colors <?= $inWishlist ? 'border-red-500 text-red-500 bg-red-50' : 'border-gray-300 text-gray-700 hover:border-red-500 hover:text-red-500' ?>">
                            <i class="fas fa-heart mr-2"></i>
                            <span id="wishlistText"><?= $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' ?></span>
                        </button>
                    <?php else: ?>
                        <a href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="w-full flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:border-red-500 hover:text-red-500 transition-colors">
                            <i class="fas fa-heart mr-2"></i>
                            Login to Add to Wishlist
                        </a>
                    <?php endif; ?>
                    
                    <div class="flex space-x-4">
                        <a href="https://wa.me/9779821589863?text=Hi, I'm interested in <?= urlencode($product['name']) ?>" 
                           target="_blank"
                           class="flex-1 bg-green-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-600 transition-colors text-center">
                            <i class="fab fa-whatsapp mr-2"></i>WhatsApp Inquiry
                        </a>
                        <a href="contact.php" 
                           class="flex-1 bg-amber-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-amber-700 transition-colors text-center">
                            <i class="fas fa-envelope mr-2"></i>Contact Us
                        </a>
                    </div>
                </div>

                <!-- Product Meta -->
                <div class="border-t pt-6">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="font-medium text-gray-900">Category</dt>
                            <dd class="mt-1 text-gray-600"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-900">Availability</dt>
                            <dd class="mt-1 text-green-600">In Stock</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($relatedProducts)): ?>
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <a href="product-details.php?id=<?= $relatedProduct['id'] ?>">
                        <div class="aspect-w-1 aspect-h-1 w-full h-48 bg-gray-200">
                            <?php if ($relatedProduct['image_data']): ?>
                                <img src="data:<?= $relatedProduct['image_type'] ?>;base64,<?= base64_encode($relatedProduct['image_data']) ?>" 
                                     alt="<?= htmlspecialchars($relatedProduct['name']) ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-amber-400 to-orange-400">
                                    <i class="fas fa-image text-white text-3xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2"><?= htmlspecialchars($relatedProduct['name']) ?></h3>
                            <p class="text-lg font-bold text-amber-600">Rs.<?= number_format($relatedProduct['price'], 2) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
function toggleWishlist(productId) {
    fetch('api/wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'toggle',
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const btn = document.getElementById('wishlistBtn');
            const text = document.getElementById('wishlistText');
            
            if (data.action === 'added') {
                btn.className = 'w-full flex items-center justify-center px-6 py-3 border-2 border-red-500 text-red-500 bg-red-50 rounded-lg font-medium transition-colors';
                text.textContent = 'Remove from Wishlist';
            } else {
                btn.className = 'w-full flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 hover:border-red-500 hover:text-red-500 rounded-lg font-medium transition-colors';
                text.textContent = 'Add to Wishlist';
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php include 'includes/footer.php'; ?>