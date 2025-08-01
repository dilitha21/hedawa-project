<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Hedawa Restaurant</title>
    <link rel="stylesheet" href="/hedawa/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="/hedawa/index.php">🍽️ Hedawa</a>
            </div>
            <ul class="nav-menu">
                <li><a href="/hedawa/index.php">Home</a></li>
                <li><a href="/hedawa/pages/bookings.php">Bookings</a></li>
                <li><a href="/hedawa/pages/order.php">Order</a></li>
                <li><a href="/hedawa/pages/contact.php">Contact</a></li>
                <li><a href="/hedawa/pages/cart.php">Cart</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'Staff'): ?>
                        <li><a href="/hedawa/pages/staff_orders.php">Staff Panel</a></li>
                        <?php if (isAdmin()): ?>
                            <li><a href="/hedawa/auth/staff_register.php">Register Staff</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <li><a href="/hedawa/auth/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="/hedawa/auth/login.php">Login</a></li>
                    <li><a href="/hedawa/auth/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="main-content">