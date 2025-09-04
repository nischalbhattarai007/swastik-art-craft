<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login to access wishlist
requireLogin();

$pageTitle = 'My Wishlist - Swastik Art & Craft';

// Fetch wishlist items
try {
    $stmt = $conn->prepare("
        SELECT w.id as wishlist_id, p.id, p.name, p.price, p.image_data, p.image_type, 
               c.name as category_name, w.created_at as added_date
        FROM wishlist w
        JOIN products p ON w.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE w.user_id = :user_id AND p.is_active = 1
        ORDER BY w.created_at DESC
    ");
    $stmt->execute(['user_id' => getUserId()]);
    $wishlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $wishlistItems = [];
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-amber-600 to-orange-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-4">My Wishlist</h1>
        <p class="text-xl opacity-90">Your favorite products saved for later</p>
    </div>
</section>

<!-- Wishlist Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (!empty($wishlistItems)): ?>
            <!-- Wishlist Stats -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                <?= count($wishlistItems) ?> item<?= count($wishlistItems) !== 1 ? 's' : '' ?> in your wishlist
                            </h2>
                            <p class="text-gray-600">Total value: Rs.<?= number_format(array_sum(array_column($wishlistItems, 'price')), 2) ?></p>
                        </div>
                        <div class="text-right">
                            <button onclick="clearWishlist()" class="text-red-600 hover:text-red-800 text-sm">
                                <i class="fas fa-trash mr-1"></i>Clear All
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wishlist Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($wishlistItems as $item): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                        <div class="relative">
                            <a href="product-details.php?id=<?= $item['id'] ?>">
                                <div class="aspect-w-1 aspect-h-1 w-full h-48 bg-gray-200">
                                    <?php if ($item['image_data']): ?>
                                        <img src="data:<?= $item['image_type'] ?>;base64,<?= base64_encode($item['image_data']) ?>" 
                                             alt="<?= htmlspecialchars($item['name']) ?>"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-amber-400 to-orange-400">
                                            <i class="fas fa-image text-white text-4xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            
                            <!-- Remove Button -->
                            <button onclick="removeFromWishlist(<?= $item['id'] ?>)" 
                                    class="absolute top-2 right-2 bg-white bg-opacity-90 hover:bg-red-500 hover:text-white text-red-500 p-2 rounded-full shadow-md transition-all duration-200">
                                <i class="fas fa-heart text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="p-4">
                            <a href="product-details.php?id=<?= $item['id'] ?>">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-amber-600 transition-colors">
                                    <?= htmlspecialchars($item['name']) ?>
                                </h3>
                            </a>
                            <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($item['category_name'] ?? 'Uncategorized') ?></p>
                            <div class="flex items-center justify-between">
                                <p class="text-lg font-bold text-amber-600">Rs.<?= number_format($item['price'], 2) ?></p>
                                <p class="text-xs text-gray-400">Added <?= date('M j', strtotime($item['added_date'])) ?></p>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-4 flex space-x-2">
                                <a href="https://wa.me/9779821589863?text=Hi, I'm interested in <?= urlencode($item['name']) ?>" 
                                   target="_blank"
                                   class="flex-1 bg-green-500 text-white px-3 py-2 rounded text-sm text-center hover:bg-green-600 transition-colors">
                                    <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                                </a>
                                <a href="product-details.php?id=<?= $item['id'] ?>" 
                                   class="flex-1 bg-amber-600 text-white px-3 py-2 rounded text-sm text-center hover:bg-amber-700 transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <!-- Empty Wishlist -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-heart text-6xl text-gray-300 mb-6"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Your wishlist is empty</h3>
                    <p class="text-gray-500 mb-8">Start adding products you love to your wishlist and keep track of them here.</p>
                    <a href="products.php" class="bg-amber-600 text-white px-8 py-3 rounded-lg hover:bg-amber-700 transition-colors">
                        <i class="fas fa-shopping-bag mr-2"></i>Browse Products
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function removeFromWishlist(productId) {
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
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function clearWishlist() {
    if (confirm('Are you sure you want to remove all items from your wishlist?')) {
        // You can implement this functionality if needed
        alert('Clear all functionality can be implemented based on requirements');
    }
}
</script>

<?php include 'includes/footer.php'; ?>