<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Handle message deletion
if (isset($_GET['delete'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = :id");
        $stmt->execute(['id' => $_GET['delete']]);
        $success = "Message deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting message: " . $e->getMessage();
    }
}

// Fetch contact messages
try {
    $stmt = $conn->query("
        SELECT id, name, email, message, created_at 
        FROM contact_messages 
        ORDER BY created_at DESC
    ");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $messages = [];
    $error = "Failed to fetch messages: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Swastik Art & Craft Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">

<?php include "../assets/partials/navbar.php"; ?>

<div class="lg:ml-64 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Contact Messages</h1>
        <p class="text-gray-600 mt-2">Manage customer inquiries and messages</p>
    </div>

    <?php if (isset($success)): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
            <i class="fas fa-check-circle text-green-500 mr-2"></i>
            <span class="text-green-700"><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
            <span class="text-red-700"><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-envelope text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Messages</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($messages) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-calendar-day text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Today's Messages</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?= count(array_filter($messages, fn($m) => date('Y-m-d', strtotime($m['created_at'])) === date('Y-m-d'))) ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-calendar-week text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">This Week</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?= count(array_filter($messages, fn($m) => strtotime($m['created_at']) >= strtotime('-7 days'))) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="space-y-6">
        <?php foreach ($messages as $message): ?>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-r from-amber-400 to-orange-400 w-12 h-12 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($message['name']) ?></h3>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-envelope mr-1"></i>
                                <a href="mailto:<?= htmlspecialchars($message['email']) ?>" class="hover:text-blue-600">
                                    <?= htmlspecialchars($message['email']) ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">
                            <?= date('M j, Y g:i A', strtotime($message['created_at'])) ?>
                        </span>
                        <a href="?delete=<?= $message['id'] ?>" 
                           class="text-red-600 hover:text-red-800 p-2"
                           onclick="return confirm('Are you sure you want to delete this message?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                </div>
                
                <div class="mt-4 flex space-x-3">
                    <a href="mailto:<?= htmlspecialchars($message['email']) ?>" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-reply mr-2"></i>Reply
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($messages)): ?>
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-4"></i>
                    <p class="text-lg font-medium">No messages yet</p>
                    <p class="text-sm">Customer messages will appear here</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    </div>
</div>

<script src="../assets/js/common.js"></script>

</body>
</html>