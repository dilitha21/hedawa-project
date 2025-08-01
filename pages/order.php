<?php
$page_title = "Order Food";
include '../includes/db.php';

// Check if user is logged in, if not redirect to login
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = '/hedawa/pages/order.php';
    header('Location: /hedawa/auth/login.php');
    exit();
}

include '../includes/header.php';
?>

<div class="container">
    <h1 class="text-center text-green">Food Ordering</h1>
    <div class="alert alert-info">
        <p>Food ordering system will be implemented in the next part. You are successfully logged in as: <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>