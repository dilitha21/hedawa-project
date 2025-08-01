<?php
session_start();

// Destroy all session data
session_destroy();

// Start a new session to show logout message
session_start();
$_SESSION['logout_success'] = 'You have been logged out successfully.';

// Redirect to home page
header('Location: /hedawa/index.php');
exit();
?>