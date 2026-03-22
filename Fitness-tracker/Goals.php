<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_login();

$user_id = $_SESSION['user_id'];

// Handle saving goals
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_goals'])) {
    $daily_steps = (int)$_POST['daily_steps'];
    $weekly_calories = (int)$_POST['weekly_calories'];

    $stmt = $pdo->prepare("INSERT INTO goals (user_id, daily_steps, weekly_calories) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE daily_steps = VALUES(daily_steps), weekly_calories = VALUES(weekly_calories)");
    $stmt->execute([$user_id, $daily_steps, $weekly_calories]);
    $success = "Goals saved successfully!";
}

// Get user's goals
$stmt = $pdo->prepare("SELECT * FROM goals WHERE user_id = ?");
$stmt->execute([$user_id]);
$goals = $stmt->fetch();

if (!$goals) {
    $goals = ['daily_steps' => 8000, 'weekly_calories' => 1500];
}

// Calculate progress
// Get today's steps and this week's calories
$today = date('Y-m-d');
$week_start = date('Y-m-d', strtotime('monday this week'));

$stmt = $pdo->prepare("SELECT SUM(duration * 100) as steps_today, SUM(calories_burned) as calories_week FROM activities WHERE user_id = ? AND date >= ?");
$stmt->execute([$user_id, $week_start]);
$progress = $stmt->fetch();

$steps_today = $progress['steps_today'] ?? 0;
$calories_week = $progress['calories_week'] ?? 0;

$steps_percentage = min(100, ($steps_today / $goals['daily_steps']) * 100);
$calories_percentage = min(100, ($calories_week / $goals['weekly_calories']) * 100);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>BalanceBeat - Goals</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
          <a class="nav-link active text-success" href="Goals.php">Goals</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>
      </ul>

      <a href="auth/logout.php" class="btn btn-danger rounded-pill px-4">Logout</a>

    </div>
  </nav>

  <!-- PAGE TITLE -->
  <div class="container mt-5">
    <h2 class="fw-bold">Set Your Fitness Goals</h2>
  </div>

  <!-- GOAL FORM -->
  <div class="container mt-4">
    <div class="card p-4">
      <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php endif; ?>

      <form action="Goals.php" method="post">
        <input type="hidden" name="save_goals" value="1">
        <div class="row align-items-center">

          <div class="col-md-6">
            <h5 class="mb-4">
              <i class="fa-solid fa-flag text-success"></i>
              Set Your Goals
            </h5>

            <label class="form-label">Daily Steps Goal</label>
            <input name="daily_steps" type="number" class="form-control mb-3" value="<?php echo $goals['daily_steps']; ?>" required>

            <label class="form-label">Weekly Calories Goal</label>
            <div class="input-group mb-4">
              <input name="weekly_calories" type="number" class="form-control" value="<?php echo $goals['weekly_calories']; ?>" required>
              <span class="input-group-text">/week</span>
            </div>

            <button type="submit" class="btn btn-success rounded-pill px-4">
              Save Goals
            </button>
          </div>

          <div class="col-md-6 text-center">
            <img src="https://images.unsplash.com/photo-1583454110551-21f2fa2afe61"
                 class="img-fluid rounded">
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- CURRENT GOALS -->
  <div class="container mt-4">
    <h4 class="mb-3">Current Goals & Progress</h4>

    <div class="row g-4">

      <!-- Steps Card -->
      <div class="col-md-6">
        <div class="card p-4">
          <h5>
            <i class="fa-solid fa-shoe-prints text-success"></i>
            Steps Goal: <?php echo number_format($goals['daily_steps']); ?>/day
          </h5>

          <div class="progress mt-3">
            <div class="progress-bar bg-success" style="width:<?php echo $steps_percentage; ?>%"></div>
          </div>

          <p class="mt-2 text-muted">Today's progress: <?php echo number_format($steps_today); ?> steps (<?php echo round($steps_percentage); ?>%)</p>
        </div>
      </div>

      <!-- Calories Card -->
      <div class="col-md-6">
        <div class="card p-4">
          <h5>
            <i class="fa-solid fa-fire text-warning"></i>
            Calories Goal: <?php echo number_format($goals['weekly_calories']); ?>/week
          </h5>

          <div class="progress mt-3">
            <div class="progress-bar bg-warning" style="width:<?php echo $calories_percentage; ?>%"></div>
          </div>

          <p class="mt-2 text-muted">This week's progress: <?php echo number_format($calories_week); ?> calories (<?php echo round($calories_percentage); ?>%)</p>
        </div>
      </div>

    </div>
  </div>

</body>
</html>