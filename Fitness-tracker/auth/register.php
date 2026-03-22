<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (!validate_email($email)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "Username or email already in use.";
    }

    if (empty($errors)) {
        $hashed_password = hash_password($password);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$username, $email, $hashed_password])) {
            $_SESSION['success'] = "Account created successfully. Please login.";
            header('Location: login.php');
            exit();
        }
        $errors[] = "Unable to complete registration. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | BalanceBeat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="bg-light">
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 440px;">
            <h3 class="mb-3 text-center">Create Your Account</h3>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($username) && $username !== 'root' ? htmlspecialchars($username) : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>

            <p class="text-center mt-3 mb-0">Already have an account? <a href="login.php">Login now</a></p>
        </div>
    </div>
</body>
</html>