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

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_POST['image'];

    // Update product in database
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $description, $price, $category, $image, $id]);

    // Redirect to dashboard
    header('Location: dashboard.php');
    exit;
}

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


// Get product details
$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$product = $stmt->fetch();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>

<body>
    <h1>Edit Product</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $product['name']; ?>"><br>
        <label for="description">Description:</label>
        <textarea name="description" id="description"><?php echo $product['description']; ?></textarea><br>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" value="<?php echo $product['price']; ?>"><br>
        <label for="category">Category:</label>
        <input type="text" name="category" id="category" value="<?php echo $product['category']; ?>"><br>
        <label for="image">Image URL:</label>
        <input type="text" name="image" id="image" value="<?php echo $product['image']; ?>"><br>
        <input type="submit" value="Update">
    </form>
</body>

</html>