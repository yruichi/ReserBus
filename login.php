<?php
session_start();

// Hardcoded admin credentials
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin');

// Hardcoded sample customer credentials (for demo)
$sample_customers = [
    ['username' => 'customer', 'password' => 'customer', 'email' => 'customer@example.com'],
    ['username' => 'john', 'password' => 'pass123', 'email' => 'john@example.com'],
    ['username' => 'jane', 'password' => 'pass456', 'email' => 'jane@example.com'],
    ['username' => 'alex', 'password' => 'pass789', 'email' => 'alex@example.com']
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check admin
    if ($username == ADMIN_USERNAME && $password == ADMIN_PASSWORD) {
        $_SESSION['user_type'] = 'admin';
        header('Location: adminHomepage.php');
        exit();
    }

    // Check sample customers
    $is_customer = false;
    foreach ($sample_customers as $customer) {
        if ($username == $customer['username'] && $password == $customer['password']) {
            $is_customer = true;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $customer['email'];
            $_SESSION['password'] = $customer['password'];
            break;
        }
    }
    if ($is_customer) {
        $_SESSION['user_type'] = 'customer';
        header('Location: customerHomepage.php');
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
</body>
</html>