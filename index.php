<?php
$page_title = "Home";
include 'includes/header.php';

$success = '';
if (isset($_SESSION['logout_success'])) {
    $success = $_SESSION['logout_success'];
    unset($_SESSION['logout_success']);
}
?>

<div class="container">
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <div class="text-center">
        <h1 class="text-green">Welcome to Hedawa Restaurant</h1>
        <p class="mb-2">Experience authentic Sri Lankan cuisine in a warm, welcoming atmosphere.</p>
        
        <div style="margin: 2rem 0;">
            <h2>Our Services</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 1rem;">
                <div style="padding: 1.5rem; background-color: var(--light-gray); border-radius: 8px;">
                    <h3>🏠 Room Bookings</h3>
                    <p>Comfortable accommodations for your stay</p>
                    <a href="pages/bookings.php" class="btn mt-2">Book Now</a>
                </div>
                <div style="padding: 1.5rem; background-color: var(--light-gray); border-radius: 8px;">
                    <h3>🍽️ Food Ordering</h3>
                    <p>Delicious meals delivered to your door</p>
                    <a href="pages/order.php" class="btn mt-2">Order Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>