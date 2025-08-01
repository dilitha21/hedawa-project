<?php
include '../includes/db.php';

// Only admin can register staff
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /hedawa/auth/staff_register.php');
    exit();
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$admin_privileges = isset($_POST['admin_privileges']) && $_POST['admin_privileges'] === '1';

// Store form data for repopulating on error
$_SESSION['staff_form_name'] = $name;
$_SESSION['staff_form_email'] = $email;
$_SESSION['staff_form_admin'] = $admin_privileges;

// Validation
if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['staff_register_error'] = 'Please fill in all fields.';
    header('Location: /hedawa/auth/staff_register.php');
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['staff_register_error'] = 'Please enter a valid email address.';
    header('Location: /hedawa/auth/staff_register.php');
    exit();
}

// Validate password length
if (strlen($password) < 6) {
    $_SESSION['staff_register_error'] = 'Password must be at least 6 characters long.';
    header('Location: /hedawa/auth/staff_register.php');
    exit();
}

// Check if passwords match
if ($password !== $confirm_password) {
    $_SESSION['staff_register_error'] = 'Passwords do not match.';
    header('Location: /hedawa/auth/staff_register.php');
    exit();
}

// Validate name (only letters and spaces)
if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
    $_SESSION['staff_register_error'] = 'Name should only contain letters and spaces.';
    header('Location: /hedawa/auth/staff_register.php');
    exit();
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $_SESSION['staff_register_error'] = 'An account with this email already exists.';
        header('Location: /hedawa/auth/staff_register.php');
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new staff user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, is_admin) VALUES (?, ?, ?, 'Staff', ?)");
    $stmt->execute([$name, $email, $hashed_password, $admin_privileges]);
    
    // Clear form data
    unset($_SESSION['staff_form_name'], $_SESSION['staff_form_email'], $_SESSION['staff_form_admin']);
    
    // Set success message
    $privilege_text = $admin_privileges ? ' with admin privileges' : '';
    $_SESSION['staff_register_success'] = "Staff member '{$name}' has been registered successfully{$privilege_text}!";
    header('Location: /hedawa/auth/staff_register.php');
    exit();
    
} catch (PDOException $e) {
    $_SESSION['staff_register_error'] = 'An error occurred while registering the staff member. Please try again.';
    header('Location: /hedawa/auth/staff_register.php');
    exit();
}
?>