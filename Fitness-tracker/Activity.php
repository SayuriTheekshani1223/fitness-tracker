<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_login();

$user_id = $_SESSION['user_id'];

// Handle adding activity
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $activity = sanitize_input($_POST['activity']);
    $duration = (int)$_POST['duration'];
    $calories = (int)$_POST['calories'];
    $date = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO activities (user_id, activity, duration, calories_burned, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $activity, $duration, $calories, $date]);
    $success = "Activity added successfully!";
}

// Fetch user's activities
$stmt = $pdo->prepare("SELECT * FROM activities WHERE user_id = ? ORDER BY date DESC LIMIT 10");
$stmt->execute([$user_id]);
$activities = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>BalanceBeat - Activity</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">

      <!-- Brand -->
      <a class="navbar-brand fw-bold" href="Home.php">
        <i class="fa-solid fa-heart-pulse text-success"></i> BalanceBeat
      </a>

      <!-- Navigation Links -->
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="Home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active text-success" href="Activity.php">Activity</a></li>
        <li class="nav-item"><a class="nav-link" href="Progress.php">Progress</a></li>
        <li class="nav-item"><a class="nav-link" href="Goals.php">Goals</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      </ul>

      <!-- Logout Button -->
      <a href="auth/logout.php" class="btn btn-danger rounded-pill px-4">Logout</a>

    </div>
  </nav>

  <!-- PAGE TITLE -->
  <div class="container mt-5">
    <h2 class="fw-bold">Daily Activity Log</h2>
  </div>

  <!-- ACTIVITY LOG -->
  <div class="container mt-4">
    <div class="card p-4 shadow-sm border-0">

      <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php endif; ?>

      <div class="row">

        <!-- LEFT SIDE FORM -->
        <div class="col-md-6">
          <h4 class="mb-4">Log Your Activity</h4>

          <form action="Activity.php" method="post">
            <!-- Activity Type -->
            <div class="mb-3">
              <label class="form-label fw-semibold">
                <i class="fa-solid fa-dumbbell text-success me-2"></i>
                Activity Type
              </label>
              <input name="activity" type="text" class="form-control" placeholder="e.g., Running" required>
            </div>

            <!-- Duration -->
            <div class="mb-3">
              <label class="form-label fw-semibold">
                <i class="fa-regular fa-clock text-primary me-2"></i>
                Duration (minutes)
              </label>
              <input name="duration" type="number" class="form-control" required>
            </div>

            <!-- Calories -->
            <div class="mb-4">
              <label class="form-label fw-semibold">
                <i class="fa-solid fa-fire text-warning me-2"></i>
                Calories Burned
              </label>
              <input name="calories" type="number" class="form-control" required>
            </div>

            <!-- Add Activity Button -->
            <button type="submit" class="btn btn-success rounded-pill px-4 py-2">Add Activity</button>
          </form>
        </div>

        <!-- RIGHT SIDE IMAGE -->
        <div class="col-md-6 text-center">
          <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b"
               class="img-fluid rounded shadow-sm" alt="Exercise Image">
        </div>

      </div>

      <hr class="my-4">

      <!-- ACTIVITY HISTORY -->
      <h5 class="mb-4">Recent Activity History</h5>

      <div class="row g-4">
        <?php if (empty($activities)): ?>
          <p>No activities logged yet.</p>
        <?php else: ?>
          <?php foreach ($activities as $act): ?>
            <div class="col-md-6">
              <div class="card history-card p-3 border-0 shadow-sm">
                <div class="d-flex justify-content-between">
                  <div>
                    <i class="fa-solid fa-dumbbell text-success me-2"></i>
                    <?php echo htmlspecialchars($act['activity']); ?>
                  </div>
                  <span class="text-muted"><?php echo $act['date']; ?></span>
                </div>
                <p class="mb-1 mt-2">Duration: <?php echo $act['duration']; ?> min</p>
                <p class="text-muted">Calories: <?php echo $act['calories_burned']; ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>