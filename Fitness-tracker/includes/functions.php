<?php
// Helper functions

// Sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hash password
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header('Location: auth/login.php');
        exit();
    }
}
?>