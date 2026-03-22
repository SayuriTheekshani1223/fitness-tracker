<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_login();

$user_id = $_SESSION['user_id'];

// Handle adding activity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_activity'])) {
    $activity = sanitize_input($_POST['activity']);
    $duration = (int)$_POST['duration'];
    $calories = (int)$_POST['calories'];
    $date = $_POST['date'];

    $stmt = $pdo->prepare("INSERT INTO activities (user_id, activity, duration, calories_burned, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $activity, $duration, $calories, $date]);
    $success = "Activity added successfully!";
}

// Fetch user's activities
$stmt = $pdo->prepare("SELECT * FROM activities WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$activities = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - BalanceBeat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="Home.php">
                <i class="fa-solid fa-heart-pulse text-success"></i> BalanceBeat
            </a>
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="Home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="Activity.php">Activity</a></li>
                <li class="nav-item"><a class="nav-link" href="Progress.php">Progress</a></li>
                <li class="nav-item"><a class="nav-link" href="Goals.php">Goals</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
            <a href="auth/logout.php" class="btn btn-danger rounded-pill px-4">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Welcome to your Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card p-4">
                    <h5>Add New Activity</h5>
                    <form action="dashboard.php" method="post">
                        <input type="hidden" name="add_activity" value="1">
                        <div class="mb-3">
                            <label>Activity:</label>
                            <input type="text" name="activity" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Duration (minutes):</label>
                            <input type="number" name="duration" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Calories Burned:</label>
                            <input type="number" name="calories" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Date:</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Add Activity</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <h5>Your Activities</h5>
                    <?php if (empty($activities)): ?>
                        <p>No activities yet.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($activities as $act): ?>
                                <li class="list-group-item">
                                    <strong><?php echo htmlspecialchars($act['activity']); ?></strong> - 
                                    <?php echo $act['duration']; ?> min, 
                                    <?php echo $act['calories_burned']; ?> cal, 
                                    <?php echo $act['date']; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>