<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_login();

$user_id = $_SESSION['user_id'];

// Get weekly data for chart
$week_days = [];
$steps_data = [];
$calories_data = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $week_days[] = date('D', strtotime($date));

    $stmt = $pdo->prepare("SELECT SUM(duration * 100) as steps, SUM(calories_burned) as calories FROM activities WHERE user_id = ? AND date = ?");
    $stmt->execute([$user_id, $date]);
    $data = $stmt->fetch();

    $steps_data[] = (int)($data['steps'] ?? 0);
    $calories_data[] = (int)($data['calories'] ?? 0);
}

// Get today's progress
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT SUM(duration * 100) as steps_today FROM activities WHERE user_id = ? AND date = ?");
$stmt->execute([$user_id, $today]);
$today_data = $stmt->fetch();
$steps_today = $today_data['steps_today'] ?? 0;

// Get yesterday's progress
$yesterday = date('Y-m-d', strtotime('-1 day'));
$stmt = $pdo->prepare("SELECT SUM(calories_burned) as calories_yesterday FROM activities WHERE user_id = ? AND date = ?");
$stmt->execute([$user_id, $yesterday]);
$yesterday_data = $stmt->fetch();
$calories_yesterday = $yesterday_data['calories_yesterday'] ?? 0;

// Get user's goals
$stmt = $pdo->prepare("SELECT * FROM goals WHERE user_id = ?");
$stmt->execute([$user_id]);
$goals = $stmt->fetch();

if (!$goals) {
    $goals = ['daily_steps' => 8000, 'weekly_calories' => 1500];
}

$steps_percentage = min(100, ($steps_today / $goals['daily_steps']) * 100);
$calories_percentage = min(100, ($calories_yesterday / ($goals['weekly_calories'] / 7)) * 100); // Daily average
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>BalanceBeat - Progress</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <link rel="stylesheet" href="style.css">

</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">

      <!-- Brand -->
      <a class="navbar-brand fw-bold" href="Home.php">
        <i class="fa-solid fa-heart-pulse text-success"></i> BalanceBeat
      </a>

      <!-- Navigation Links -->
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link" href="Home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Activity.php">Activity</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-success" href="Progress.php">Progress</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Goals.php">Goals</a>
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
    <h2 class="fw-bold">Progress Overview</h2>
  </div>

  <!-- WEEKLY CHART -->
  <div class="container mt-4">
    <div class="card p-4">
      <h5 class="mb-4">Weekly Progress</h5>
      <canvas id="progressChart"></canvas>
    </div>
  </div>

  <!-- PROGRESS BARS -->
  <div class="container mt-4">
    <div class="row g-4">

      <!-- Steps Progress -->
      <div class="col-md-6">
        <div class="card p-4">

          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>
              <i class="fa-solid fa-shoe-prints text-success icon"></i>
              Steps Progress
            </h5>
            <span class="text-muted">Today</span>
          </div>

          <div class="progress mb-3">
            <div class="progress-bar bg-success" style="width:<?php echo $steps_percentage; ?>%"></div>
          </div>

          <div class="d-flex justify-content-between">
            <span class="text-muted"><?php echo number_format($steps_today); ?> / <?php echo number_format($goals['daily_steps']); ?> steps</span>
            <h4 class="text-success"><?php echo round($steps_percentage); ?>%</h4>
          </div>

        </div>
      </div>

      <!-- Calories Progress -->
      <div class="col-md-6">
        <div class="card p-4">

          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>
              <i class="fa-solid fa-fire text-warning icon"></i>
              Calories Progress
            </h5>
            <span class="text-muted">Yesterday</span>
          </div>

          <div class="progress mb-3">
            <div class="progress-bar bg-warning" style="width:<?php echo $calories_percentage; ?>%"></div>
          </div>

          <div class="d-flex justify-content-between">
            <span class="text-muted"><?php echo number_format($calories_yesterday); ?> / <?php echo number_format(round($goals['weekly_calories'] / 7)); ?> calories</span>
            <h4 class="text-warning"><?php echo round($calories_percentage); ?>%</h4>
          </div>

        </div>
      </div>

    </div>
  </div>

  <script>
    // Weekly Progress Chart
    const ctx = document.getElementById('progressChart').getContext('2d');
    const progressChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($week_days); ?>,
        datasets: [{
          label: 'Steps',
          data: <?php echo json_encode($steps_data); ?>,
          borderColor: 'rgb(25, 135, 84)',
          backgroundColor: 'rgba(25, 135, 84, 0.1)',
          tension: 0.4
        }, {
          label: 'Calories',
          data: <?php echo json_encode($calories_data); ?>,
          borderColor: 'rgb(255, 193, 7)',
          backgroundColor: 'rgba(255, 193, 7, 0.1)',
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Weekly Activity Progress'
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>

</body>
</html>