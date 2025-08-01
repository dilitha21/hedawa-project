<?php
$page_title = "Register";
include '../includes/db.php';
include '../includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $redirect = $_SESSION['role'] === 'Staff' ? '/hedawa/pages/staff_orders.php' : '/hedawa/index.php';
    header("Location: $redirect");
    exit();
}

$error = '';
if (isset($_SESSION['register_error'])) {
    $error = $_SESSION['register_error'];
    unset($_SESSION['register_error']);
}
?>

<div class="container">
    <h1 class="text-center text-green">Join Hedawa</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form action="/hedawa/process/process_register.php" method="POST">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo isset($_SESSION['form_name']) ? htmlspecialchars($_SESSION['form_name']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo isset($_SESSION['form_email']) ? htmlspecialchars($_SESSION['form_email']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="6">
            <small style="color: #666;">Minimum 6 characters</small>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        </div>
        
        <div class="form-group">
            <label for="role">I am a:</label>
            <select id="role" name="role" required>
                <option value="">Select your role</option>
                <option value="Customer" <?php echo (isset($_SESSION['form_role']) && $_SESSION['form_role'] === 'Customer') ? 'selected' : ''; ?>>Customer</option>
                <option value="Staff" <?php echo (isset($_SESSION['form_role']) && $_SESSION['form_role'] === 'Staff') ? 'selected' : ''; ?>>Staff Member</option>
            </select>
        </div>
        
        <div class="form-group text-center">
            <button type="submit" class="btn">Create Account</button>
        </div>
    </form>
    
    <div class="text-center mt-2">
        <p>Already have an account? <a href="/hedawa/auth/login.php" class="text-green">Login here</a></p>
    </div>
</div>

<?php 
// Clear form data
unset($_SESSION['form_name'], $_SESSION['form_email'], $_SESSION['form_role']);
include '../includes/footer.php'; 
?>