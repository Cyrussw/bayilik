<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Connect to database
require_once '../inc/connect.php';

// Get product ID from URL parameter
$id = $_GET['id'];

// Get product from database
$sql = "SELECT * FROM products WHERE id = ? AND creator = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $_SESSION['user_id']]);
$product = $stmt->fetch();

// Check if product exists and is visible
if (!$product || !$product['isVisible']) {
    header('Location: dashboard.php');
    exit;
}

// Delete product from database
$sql = "UPDATE products SET isVisible = FALSE WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

// Redirect to dashboard
header('Location: dashboard.php');
exit;
