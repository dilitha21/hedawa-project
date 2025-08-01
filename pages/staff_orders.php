<?php
$page_title = "Staff Panel";
include '../includes/db.php';

// Only staff can access this page
requireStaff();
include '../includes/header.php';

$error = '';
if (isset($_GET['error']) && $_GET['error'] === 'admin_required') {
    $error = 'Admin privileges required to access that feature.';
}

// Get staff statistics
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_staff FROM users WHERE role = 'Staff'");
    $stmt->execute();
    $total_staff = $stmt->fetch()['total_staff'];
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_customers FROM users WHERE role = 'Customer'");
    $stmt->execute();
    $total_customers = $stmt->fetch()['total_customers'];
} catch (PDOException $e) {
    $total_staff = 0;
    $total_customers = 0;
}
?>

<div class="container">
    <h1 class="text-center text-green">Staff Panel</h1>
    <p class="text-center">Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>!</p>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <!-- Admin Features -->
    <?php if (isAdmin()): ?>
        <div style="background-color: #e8f5e8; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            <h2 style="color: var(--primary-green); margin-bottom: 1rem;">🔐 Admin Features</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <a href="/hedawa/auth/staff_register.php" class="btn">👥 Register New Staff</a>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Statistics -->
    <div style="margin-bottom: 2rem;">
        <h2>📊 System Statistics</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
            <div style="background-color: var(--accent-green); color: white; padding: 1.5rem; border-radius: 8px; text-align: center;">
                <h3><?php echo $total_staff; ?></h3>
                <p>Staff Members</p>
            </div>
            <div style="background-color: var(--primary-green); color: white; padding: 1.5rem; border-radius: 8px; text-align: center;">
                <h3><?php echo $total_customers; ?></h3>
                <p>Customers</p>
            </div>
        </div>
    </div>
    
    <!-- Staff Features -->
    <div>
        <h2>🛠️ Staff Tools</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 1rem;">
            <div style="padding: 1.5rem; background-color: var(--light-gray); border-radius: 8px;">
                <h3>📋 Manage Orders</h3>
                <p>View and manage customer food orders</p>
                <button class="btn mt-2" disabled>Coming Soon</button>
            </div>
            <div style="padding: 1.5rem; background-color: var(--light-gray); border-radius: 8px;">
                <h3>🏠 Manage Bookings</h3>
                <p>View and manage room bookings</p>
                <button class="btn mt-2" disabled>Coming Soon</button>
            </div>
        </div>
    </div>
    
    <!-- User Role Information -->
    <div style="margin-top: 2rem; padding: 1rem; background-color: #f0f8f0; border-radius: 8px;">
        <h3>Your Account Information:</h3>
        <ul style="margin-top: 0.5rem;">
            <li><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></li>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></li>
            <li><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></li>
            <li><strong>Admin:</strong> <?php echo isAdmin() ? 'Yes' : 'No'; ?></li>
        </ul>
    </div>
</div>

<?php include '../includes/footer.php'; ?>