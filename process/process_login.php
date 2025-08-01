<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /hedawa/auth/login.php');
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validation
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Please fill in all fields.';
    $_SESSION['form_email'] = $email;
    header('Location: /hedawa/auth/login.php');
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = 'Please enter a valid email address.';
    $_SESSION['form_email'] = $email;
    header('Location: /hedawa/auth/login.php');
    exit();
}

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $_SESSION['login_error'] = 'Invalid email or password.';
        $_SESSION['form_email'] = $email;
        header('Location: /hedawa/auth/login.php');
        exit();
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        $_SESSION['login_error'] = 'Invalid email or password.';
        $_SESSION['form_email'] = $email;
        header('Location: /hedawa/auth/login.php');
        exit();
    }
    
    // Login successful - set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    
    // Role-based redirection
    if ($user['role'] === 'Staff') {
        header('Location: /hedawa/pages/staff_orders.php');
    } else {
        // Redirect to intended page or home
        $redirect = $_SESSION['redirect_after_login'] ?? '/hedawa/index.php';
        unset($_SESSION['redirect_after_login']);
        header("Location: $redirect");
    }
    exit();
    
} catch (PDOException $e) {
    $_SESSION['login_error'] = 'An error occurred. Please try again.';
    $_SESSION['form_email'] = $email;
    header('Location: /hedawa/auth/login.php');
    exit();
}
?>