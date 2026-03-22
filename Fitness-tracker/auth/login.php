<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
    $user = $stmt->fetch();

    if ($user && verify_password($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ../dashboard.php');
        exit();
    }

    $error = "Invalid username/email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | BalanceBeat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="bg-light">
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 420px;">
            <h3 class="mb-3 text-center">Login</h3>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username or Email</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($usernameOrEmail) ? htmlspecialchars($usernameOrEmail) : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>

            <p class="text-center mt-3 mb-0">New here? <a href="register.php">Create an account</a></p>
        </div>
    </div>
</body>
</html>