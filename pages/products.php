<?php
// Connect to database
require_once '../inc/connect.php';

session_start();

// Get the user's products
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM products WHERE creator = ? AND isVisible = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();

// HTML for displaying products
$html = '';
foreach ($products as $product) {
    $html .= '<div class="product">';
    $html .= '<h2>' . $product['name'] . '</h2>';
    $html .= '<p>' . $product['description'] . '</p>';
    $html .= '<p>Price: $' . $product['price'] . '</p>';
    $html .= '<a href="edit_product.php?id=' . $product['id'] . '">Edit</a>';
    $html .= '<a href="delete_product.php?id=' . $product['id'] . '">Delete</a>';
    $html .= '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Products</title>
</head>

<body>
    <h1>My Products</h1>
    <a href="add_product.php">Add Product</a>
    <?php echo $html; ?>
</body>

</html>