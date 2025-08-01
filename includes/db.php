<?php
// Database configuration for standard MySQL port 3306
define('DB_HOST', 'localhost');
define('DB_NAME', 'hedawa_restaurant');
define('DB_USER', 'root');
define('DB_PASS', ''); // Usually empty for XAMPP/WAMP, or your MySQL password

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper function to check if user is staff
function isStaff() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Staff';
}

// Helper function to check if user is customer
function isCustomer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Customer';
}

// Helper function to check if user is admin
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Helper function to redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /hedawa/auth/login.php');
        exit();
    }
}

// Helper function to redirect if not staff
function requireStaff() {
    requireLogin();
    if (!isStaff()) {
        header('Location: /hedawa/index.php');
        exit();
    }
}

// Helper function to require admin access
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: /hedawa/pages/staff_orders.php?error=admin_required');
        exit();
    }
}
?>