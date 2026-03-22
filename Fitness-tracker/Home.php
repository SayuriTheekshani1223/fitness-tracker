<?php
session_start();
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>BalanceBeat - Home</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome icons library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="style.css">

</head>

<body>

<!-- Navbar Section -->
<nav class="navbar navbar-expand-lg navbar-light bg-white">
  <div class="container">

    <!-- Brand Logo -->
    <a class="navbar-brand fw-bold" href="Home.php">
      <i class="fa-solid fa-heart-pulse text-success"></i> BalanceBeat
    </a>
        
    <!-- Navigation Links -->
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link active text-success" href="Home.php">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="<?php echo is_logged_in() ? 'Activity.php' : 'auth/login.php'; ?>">Activity</a></li>
      <li class="nav-item"><a class="nav-link" href="Progress.php">Progress</a></li>
      <li class="nav-item"><a class="nav-link" href="Goals.php">Goals</a></li>
      <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
    </ul>
        
    <!-- Sign In/Logout Button -->
    <?php if (is_logged_in()): ?>
      <a href="dashboard.php" class="btn btn-primary rounded-pill px-4 me-2">Dashboard</a>
      <a href="auth/logout.php" class="btn btn-danger rounded-pill px-4">Logout</a>
    <?php else: ?>
      <a href="auth/login.php" class="btn btn-success rounded-pill px-4">Sign In</a>
    <?php endif; ?>

  </div>
</nav>

<!-- Hero Section -->
<section class="hero">
  <div class="container text-center">

    <!-- Main Heading -->
    <h1 class="display-5 fw-bold">
      Track Your Fitness,<br>
      Balance Your Life
    </h1>

    <!--Description -->
    <p class="lead">
      Stay active, monitor progress, and achieve your goals.
    </p>

    <!-- Call Action Buttons -->
    <div class="mt-4">
      <a href="Activity.php" class="btn btn-success btn-lg px-4 me-3 rounded-pill">Get Started</a>
      <a href="Progress.php" class="btn btn-light btn-lg px-4 rounded-pill">View Progress</a>
    </div>

  </div>
</section>

<!-- Features Section -->
<section class="py-5">
  <div class="container">
    <div class="row g-4">

      <!-- Feature Card 1: Track Activities -->
      <div class="col-md-3">
        <div class="card feature-card text-center p-3 h-100">
          <!-- Icon -->
          <i class="fa-solid fa-shoe-prints text-success fa-2x"></i>

          <h5 class="mt-3">Track Your Activities</h5>
          <p>Log your daily workouts, steps, and exercises with detailed metrics. Monitor calories burned and workout duration in real-time.</p>
          <a href="Activity.php" class="btn btn-success btn-sm mt-2">Start Tracking</a>
        </div>
      </div>

      <!-- Feature Card 2: Visual Progress -->
      <div class="col-md-3">
        <div class="card feature-card text-center p-3 h-100">
          <i class="fa-solid fa-chart-column text-primary fa-2x"></i>
          <h5 class="mt-3">Visualize Your Progress</h5>
          <p>View interactive charts and graphs showing your fitness journey. Track trends over time and celebrate your achievements.</p>
          <a href="Progress.php" class="btn btn-primary btn-sm mt-2">View Charts</a>
        </div>
      </div>

      <!-- Feature Card 3: Goal Setting -->
      <div class="col-md-3">
        <div class="card feature-card text-center p-3 h-100">
          <i class="fa-solid fa-bullseye text-danger fa-2x"></i>
          <h5 class="mt-3">Set Personal Goals</h5>
          <p>Define your daily step targets and weekly calorie goals. Get personalized progress tracking to stay motivated and on track.</p>
          <a href="Goals.php" class="btn btn-danger btn-sm mt-2">Set Goals</a>
        </div>
      </div>

      <!-- Feature Card 4: Weekly Reports -->
      <div class="col-md-3">
        <div class="card feature-card text-center p-3 h-100">
          <i class="fa-solid fa-calendar-check text-warning fa-2x"></i>
          <h5 class="mt-3">Weekly Insights</h5>
          <p>Get comprehensive reports on your fitness performance. Analyze patterns, identify improvements, and plan your next fitness phase.</p>
          <a href="Progress.php" class="btn btn-warning btn-sm mt-2">View Reports</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Footer Section -->
<footer class="bg-white border-top py-3 mt-5">
  <div class="container position-relative text-center">
    
    <!-- All Rights Reserved Text in the Center -->
    <span class="text-muted">
      © 2026 BalanceBeat. All rights reserved.
    </span>
    
    <!-- Contact Link on the Right -->
    <a href="contact.php"
       class="text-success text-decoration-none position-absolute end-0 top-50 translate-middle-y">
       Contact
    </a>
    
  </div>
</footer>

</body>
</html>