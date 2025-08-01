<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /hedawa/auth/login.php');
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validation
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Please fill in all fields.';
    $_SESSION['form_email'] = $email;
    header('Location: /hedawa/auth/login.php');
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = 'Please enter a valid email address.';
    $_SESSION['form_email'] = $email;
    header('Location: /hedawa/auth/login.php');
    exit();
}

try {
    // Check if user exists (include is_admin in query)
    $stmt = $pdo->prepare("SELECT id, name, email, password, role, is_admin FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $_SESSION['login_error'] = 'Invalid email or password.';
        $_SESSION['form_email'] = $email;
        header('Location: /hedawa/auth/login.php');
        exit();
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        $_SESSION['login_error'] = 'Invalid email or password.';
        $_SESSION['form_email'] = $email;
        header('Location: /hedawa/auth/login.php');
        exit();
    }
    
    // Login successful - set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['is_admin'] = (bool)$user['is_admin'];
    
    // Role-based redirection
    if ($user['role'] === 'Staff') {
        header('Location: /hedawa/pages/staff_orders.php');
    } else {
        // Redirect to intended page or home
        $redirect = $_SESSION['redirect_after_login'] ?? '/hedawa/index.php';
        unset($_SESSION['redirect_after_login']);
        header("Location: $redirect");
    }
    exit();
    
} catch (PDOException $e) {
    $_SESSION['login_error'] = 'An error occurred. Please try again.';
    $_SESSION['form_email'] = $email;
    header('Location: /hedawa/auth/login.php');
    exit();
}
?>

<div class="container">
    <h1 class="text-center text-green">Login to Hedawa</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <form action="/hedawa/process/process_login.php" method="POST">
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo isset($_SESSION['form_email']) ? htmlspecialchars($_SESSION['form_email']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group text-center">
            <button type="submit" class="btn">Login</button>
        </div>
    </form>
    
    <div class="text-center mt-2">
        <p>Don't have an account? <a href="/hedawa/auth/register.php" class="text-green">Register here</a></p>
    </div>
</div>

<?php 
// Clear form data
unset($_SESSION['form_email']);
include '../includes/footer.php'; 
?>