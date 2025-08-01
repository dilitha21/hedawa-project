<?php
$page_title = "Bookings";
include '../includes/db.php';

// Check if user is logged in, if not redirect to login
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = '/hedawa/pages/bookings.php';
    header('Location: /hedawa/auth/login.php');
    exit();
}

include '../includes/header.php';
?>

<div class="container">
    <h1 class="text-center text-green">Room Bookings</h1>
    <div class="alert alert-info">
        <p>Booking system will be implemented in the next part. You are successfully logged in as: <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>