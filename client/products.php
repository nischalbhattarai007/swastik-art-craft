<?php
require_once 'includes/db.php';
$pageTitle = 'Products - Swastik Art & Craft';

// Pagination settings
$productsPerPage = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $productsPerPage;

// Search and filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build WHERE clause
$whereConditions = ['p.is_active = 1'];
$params = [];

if (!empty($search)) {
    $whereConditions[] = "p.name LIKE :search";
    $params['search'] = "%$search%";
}

if ($category > 0) {
    $whereConditions[] = "p.category_id = :category";
    $params['category'] = $category;
}

$whereClause = implode(' AND ', $whereConditions);

// Sort options
$orderBy = match($sortBy) {
    'price_low' => 'p.price ASC',
    'price_high' => 'p.price DESC',
    'name' => 'p.name ASC',
    'featured' => 'p.is_featured DESC, p.created_at DESC',
    default => 'p.created_at DESC'
};

// Get total count for pagination
$countStmt = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE $whereClause
");
$countStmt->execute($params);
$totalProducts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalProducts / $productsPerPage);

// Fetch products
$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, p.image_data, p.image_type, p.is_featured, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE $whereClause
    ORDER BY $orderBy
    LIMIT :limit OFFSET :offset
");

foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value);
}
$stmt->bindValue(':limit', $productsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories for filter
$catStmt = $conn->query("SELECT id, name FROM categories WHERE is_active = 1 ORDER BY name");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-amber-600 to-orange-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-4">Our Products</h1>
        <p class="text-xl opacity-90">Discover our collection of handcrafted art and traditional crafts</p>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="py-8 bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search products..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
            </div>
            
            <!-- Category Filter -->
            <div>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="0">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Sort -->
            <div>
                <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="newest" <?= $sortBy === 'newest' ? 'selected' : '' ?>>Newest First</option>
                    <option value="featured" <?= $sortBy === 'featured' ? 'selected' : '' ?>>Featured First</option>
                    <option value="price_low" <?= $sortBy === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_high" <?= $sortBy === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="name" <?= $sortBy === 'name' ? 'selected' : '' ?>>Name A-Z</option>
                </select>
            </div>
            
            <!-- Search Button -->
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <a href="products.php" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</section>

<!-- Products Grid -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Info -->
        <div class="flex justify-between items-center mb-8">
            <p class="text-gray-600">
                Showing <?= min($offset + 1, $totalProducts) ?>-<?= min($offset + $productsPerPage, $totalProducts) ?> 
                of <?= $totalProducts ?> products
            </p>
            <?php if (!empty($search) || $category > 0): ?>
                <div class="text-sm text-gray-500">
                    <?php if (!empty($search)): ?>
                        <span class="bg-amber-100 text-amber-800 px-2 py-1 rounded">Search: "<?= htmlspecialchars($search) ?>"</span>
                    <?php endif; ?>
                    <?php if ($category > 0): ?>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">
                            Category: <?= htmlspecialchars(array_column($categories, 'name', 'id')[$category] ?? 'Unknown') ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($products)): ?>
            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                        <div class="relative">
                            <a href="product-details.php?id=<?= $product['id'] ?>">
                                <div class="aspect-w-1 aspect-h-1 w-full h-48 bg-gray-200">
                                    <?php if ($product['image_data']): ?>
                                        <img src="data:<?= $product['image_type'] ?>;base64,<?= base64_encode($product['image_data']) ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-amber-400 to-orange-400">
                                            <i class="fas fa-image text-white text-4xl"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($product['is_featured']): ?>
                                        <div class="absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded text-xs font-medium">
                                            <i class="fas fa-star mr-1"></i>Featured
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            
                            <!-- Wishlist Star Button -->
                            <?php if (isLoggedIn()): ?>
                                <button onclick="toggleWishlist(<?= $product['id'] ?>)" 
                                        class="absolute top-2 right-2 bg-white bg-opacity-90 hover:bg-red-500 hover:text-white text-gray-600 p-2 rounded-full shadow-md transition-all duration-200 wishlist-btn" 
                                        data-product-id="<?= $product['id'] ?>">
                                    <i class="fas fa-heart text-sm"></i>
                                </button>
                            <?php else: ?>
                                <a href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" 
                                   class="absolute top-2 right-2 bg-white bg-opacity-90 hover:bg-red-500 hover:text-white text-gray-600 p-2 rounded-full shadow-md transition-all duration-200">
                                    <i class="fas fa-heart text-sm"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <a href="product-details.php?id=<?= $product['id'] ?>">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></p>
                                <p class="text-lg font-bold text-amber-600">Rs.<?= number_format($product['price'], 2) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-12 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        <?php if ($page > 1): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" 
                               class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                               class="px-3 py-2 border rounded-lg <?= $i === $page ? 'bg-amber-600 text-white border-amber-600' : 'border-gray-300 text-gray-700 hover:bg-gray-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" 
                               class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- No Products Found -->
            <div class="text-center py-16">
                <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-2xl font-semibold text-gray-700 mb-4">No products found</h3>
                <p class="text-gray-500 mb-8">Try adjusting your search criteria or browse all products</p>
                <a href="products.php" class="bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition-colors">
                    View All Products
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

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
            const btn = document.querySelector(`[data-product-id="${productId}"]`);
            if (btn) {
                if (data.action === 'added') {
                    btn.classList.add('bg-red-500', 'text-white');
                    btn.classList.remove('text-gray-600');
                } else {
                    btn.classList.remove('bg-red-500', 'text-white');
                    btn.classList.add('text-gray-600');
                }
            }
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php include 'includes/footer.php'; ?>