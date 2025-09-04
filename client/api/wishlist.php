<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$productId = intval($input['product_id'] ?? 0);
$userId = getUserId();

if (!$productId) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit();
}

try {
    if ($action === 'toggle') {
        // Check if product exists in wishlist
        $checkStmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = :user_id AND product_id = :product_id");
        $checkStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $exists = $checkStmt->fetch();
        
        if ($exists) {
            // Remove from wishlist
            $deleteStmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id");
            $deleteStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            echo json_encode(['success' => true, 'action' => 'removed']);
        } else {
            // Add to wishlist
            $insertStmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id, created_at) VALUES (:user_id, :product_id, NOW())");
            $insertStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            echo json_encode(['success' => true, 'action' => 'added']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>