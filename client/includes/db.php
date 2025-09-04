<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=swastik_art_craft", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>