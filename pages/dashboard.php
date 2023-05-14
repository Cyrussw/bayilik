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

// Get user's name
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get user's products
$sql = "SELECT * FROM products WHERE creator = ? AND isVisible = 1"; // Filter by isVisible column
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();

// Check if product exists and is visible
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$product = $stmt->fetch();
if (!$product || !$product['isVisible']) {
    // Redirect user to dashboard if product does not exist or is not visible
    header('Location: dashboard.php');
    exit;
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo $user['name']; ?>!</p>
    <h2>My Products</h2>
    <ul>
        <?php foreach ($products as $product) : ?>
            <li>
                <?php echo $product['name']; ?>
                <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                <a href="delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="add_product.php">Add Product</a>
    <br>
    <a href="logout.php">Logout</a>
</body>

</html>
