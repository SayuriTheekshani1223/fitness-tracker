<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $message = sanitize_input($_POST['message']);

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (!validate_email($email)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$name, $email, $message])) {
            $success = "Message sent successfully!";
        } else {
            $errors[] = "Failed to send message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>BalanceBeat - Contact</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">

      <a class="navbar-brand fw-bold" href="Home.php">
        <i class="fa-solid fa-heart-pulse text-success"></i> BalanceBeat
      </a>

      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link" href="Home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Activity.php">Activity</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Progress.php">Progress</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Goals.php">Goals</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-success" href="contact.php">Contact</a>
        </li>
      </ul>

      <?php if (is_logged_in()): ?>
        <a href="auth/logout.php" class="btn btn-danger rounded-pill px-4">Logout</a>
      <?php else: ?>
        <a href="auth/login.php" class="btn btn-success rounded-pill px-4">Sign In</a>
      <?php endif; ?>

    </div>
  </nav>

  <!-- PAGE TITLE -->
  <div class="container mt-5">
    <h2 class="fw-bold">Contact Us</h2>
  </div>

  <!-- CONTACT SECTION -->
  <div class="container mt-4">
    <div class="row g-4">

      <!-- LEFT SIDE FORM -->
      <div class="col-md-7">
        <div class="card p-4 h-100">

          <h5 class="mb-4">
            <i class="fa-solid fa-address-book text-success"></i>
            Contact
          </h5>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (isset($success)): ?>
            <div class="alert alert-success">
              <p><?php echo $success; ?></p>
            </div>
          <?php endif; ?>

          <form action="contact.php" method="post">
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text">
                  <i class="fa-regular fa-user"></i>
                </span>
                <input name="name" type="text" class="form-control" placeholder="Name" required>
              </div>
            </div>

            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text">
                  <i class="fa-regular fa-envelope"></i>
                </span>
                <input name="email" type="email" class="form-control" placeholder="Email" required>
              </div>
            </div>

            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text">
                  <i class="fa-regular fa-envelope"></i>
                </span>
                <textarea name="message" class="form-control" rows="5" placeholder="Message" required></textarea>
              </div>
            </div>

            <button type="submit" class="btn btn-success rounded-pill px-4">
              Send Message
            </button>
          </form>

        </div>
      </div>

      <!-- RIGHT SIDE -->
      <div class="col-md-5">

        <!-- IMAGE CARD -->
        <div class="card p-3 mb-4">
          <img src="https://images.pexels.com/photos/414029/pexels-photo-414029.jpeg"
               class="img-fluid rounded">
        </div>

        <!-- CONTACT DETAILS CARD -->
        <div class="card p-4">
          <div class="d-flex align-items-center mb-3">
            <i class="fa-regular fa-envelope me-3 fs-4"></i>
            <span>contact@balancebeat.com</span>
          </div>
          <div class="d-flex align-items-center">
            <i class="fa-solid fa-phone me-3 fs-4"></i>
            <span>+(011) 234-5678</span>
          </div>
        </div>

      </div>

    </div>
  </div>

  <script src="contact.js"></script>

</body>
</html>