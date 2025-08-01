<?php
$page_title = "Register New Staff";
include '../includes/db.php';

// Only admin can access this page
requireAdmin();
include '../includes/header.php';

$error = '';
if (isset($_SESSION['staff_register_error'])) {
    $error = $_SESSION['staff_register_error'];
    unset($_SESSION['staff_register_error']);
}

$success = '';
if (isset($_SESSION['staff_register_success'])) {
    $success = $_SESSION['staff_register_success'];
    unset($_SESSION['staff_register_success']);
}
?>

<div class="container">
    <h1 class="text-center text-green">Register New Staff Member</h1>
    <p class="text-center">Only admin users can register new staff members</p>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <form action="/hedawa/process/process_staff_register.php" method="POST">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo isset($_SESSION['staff_form_name']) ? htmlspecialchars($_SESSION['staff_form_name']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo isset($_SESSION['staff_form_email']) ? htmlspecialchars($_SESSION['staff_form_email']) : ''; ?>">
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
            <label for="admin_privileges">
                <input type="checkbox" id="admin_privileges" name="admin_privileges" value="1"
                       <?php echo (isset($_SESSION['staff_form_admin']) && $_SESSION['staff_form_admin']) ? 'checked' : ''; ?>>
                Grant admin privileges (can register other staff members)
            </label>
        </div>
        
        <div class="form-group text-center">
            <button type="submit" class="btn">Register Staff Member</button>
            <a href="/hedawa/pages/staff_orders.php" class="btn btn-secondary" style="margin-left: 1rem;">Cancel</a>
        </div>
    </form>
</div>

<?php 
// Clear form data
unset($_SESSION['staff_form_name'], $_SESSION['staff_form_email'], $_SESSION['staff_form_admin']);
include '../includes/footer.php'; 
?>