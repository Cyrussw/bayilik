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

// Define variables and set to empty values
$name = $description = $price = '';
$errors = array();

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (empty($_POST['name'])) {
        $errors[] = 'Name is required';
    } else {
        $name = trim($_POST['name']);
    }

    if (empty($_POST['description'])) {
        $errors[] = 'Description is required';
    } else {
        $description = trim($_POST['description']);
    }

    if (empty($_POST['price'])) {
        $errors[] = 'Price is required';
    } else {
        $price = trim($_POST['price']);
        if (!is_numeric($price)) {
            $errors[] = 'Price must be a number';
        }
    }

    // If no errors, add product to database
    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];
        $sql = "INSERT INTO products (name, description, price, creator) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $price, $user_id]);
        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>

<body>
    <h1>Add Product</h1>
    <?php if (!empty($errors)) : ?>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <p>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
        </p>
        <p>
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
        </p>
        <p>
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>">
        </p>
        <button type="submit">Add Product</button>
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
    <br>
    <a href="logout.php">Logout</a>
</body>

</html>