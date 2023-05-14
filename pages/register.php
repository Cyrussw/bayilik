<?php
// Connect to database
require_once '../inc/connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    $errors = [];
    if (empty($name)) {
        $errors[] = 'Lütfen isim girin.';
    }
    if (empty($email)) {
        $errors[] = 'Lütfen E-Mail girin.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-Mail formatı hatalı.';
    } else {
        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            $errors[] = 'Email zaten kullanımda';
        }
    }
    if (empty($password)) {
        $errors[] = 'Lütfen şifre girin.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Şifreniz en az 8 karakterli olmalıdır.';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'Şifreniz eşleşmiyor.';
    }

    // If there are no errors, create the user
    if (empty($errors)) {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $password_hash]);

        // Redirect to login page
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <form class="form" action="register.php" method="post">
            <h2>Register</h2>
            <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="form-control">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>">
            </div>
            <div class="form-control">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
            </div>
            <div class="form-control">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-control">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>

</html>