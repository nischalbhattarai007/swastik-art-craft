<?php
require_once "../config/db.php";

if (!isset($_GET['id'])) {
    http_response_code(404);
    exit();
}

$product_id = intval($_GET['id']);

try {
    $stmt = $conn->prepare("SELECT image_data, image_type FROM products WHERE id = :id AND image_data IS NOT NULL");
    $stmt->execute(['id' => $product_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        header("Content-Type: " . $result['image_type']);
        header("Cache-Control: public, max-age=3600");
        echo $result['image_data'];
    } else {
        http_response_code(404);
    }
} catch (PDOException $e) {
    http_response_code(500);
}
?>