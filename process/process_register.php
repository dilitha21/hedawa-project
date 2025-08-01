<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /hedawa/auth/register.php');
    exit();
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = $_POST['role'] ?? '';

// Store form data for repopulating on error
$_SESSION['form_name'] = $name;
$_SESSION['form_email'] = $email;
$_SESSION['form_role'] = $role;

// Validation
if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
    $_SESSION['register_error'] = 'Please fill in all fields.';
    header('Location: /hedawa/auth/register.php');
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = 'Please enter a valid email address.';
    header('Location: /hedawa/auth/register.php');
    exit();
}

// Validate password length
if (strlen($password) < 6) {
    $_SESSION['register_error'] = 'Password must be at least 6 characters long.';
    header('Location: /hedawa/auth/register.php');
    exit();
}

// Check if passwords match
if ($password !== $confirm_password) {
    $_SESSION['register_error'] = 'Passwords do not match.';
    header('Location: /hedawa/auth/register.php');
    exit();
}

// Validate role
if (!in_array($role, ['Customer', 'Staff'])) {
    $_SESSION['register_error'] = 'Please select a valid role.';
    header('Location: /hedawa/auth/register.php');
    exit();
}

// Validate name (only letters and spaces)
if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
    $_SESSION['register_error'] = 'Name should only contain letters and spaces.';
    header('Location: /hedawa/auth/register.php');
    exit();
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $_SESSION['register_error'] = 'An account with this email already exists.';
        header('Location: /hedawa/auth/register.php');
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashed_password, $role]);
    
    // Clear form data
    unset($_SESSION['form_name'], $_SESSION['form_email'], $_SESSION['form_role']);
    
    // Set success message and redirect to login
    $_SESSION['register_success'] = 'Account created successfully! Please login.';
    header('Location: /hedawa/auth/login.php');
    exit();
    
} catch (PDOException $e) {
    $_SESSION['register_error'] = 'An error occurred while creating your account. Please try again.';
    header('Location: /hedawa/auth/register.php');
    exit();
}
?>