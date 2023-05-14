<?php
// Connect to database
require_once '../inc/connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate form data
    $errors = [];
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    // If there are no errors, authenticate the user
    if (empty($errors)) {
        // Check if email exists in database
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Check if password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Start session and store user data
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Incorrect email or password';
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <div class="container">
        <form class="form" method="post">
            <h2>Login</h2>
            <?php if (!empty($errors)) : ?>
                <div class="errors">
                    <?php foreach ($errors as $error) : ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password">
            </div>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
</body>

</html>