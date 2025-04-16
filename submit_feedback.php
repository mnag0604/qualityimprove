<?php
session_start();
include "config.php"; // Database connection

// Check if employee is logged in
if (!isset($_SESSION["employee_id"])) {
    header("Location: employee_login.php");
    exit();
}

$employee_id = $_SESSION["employee_id"];

// Handle Submit Feedback
if (isset($_POST["submit_feedback"])) {
    $feedback_text = $_POST["feedback_text"];
    $rating = $_POST["rating"];

    $sql = "INSERT INTO feedback (employee_id, feedback_text, rating, submitted_at) 
            VALUES ('$employee_id', '$feedback_text', '$rating', NOW())";
    mysqli_query($conn, $sql);
    header("Location: submit_feedback.php");
}

// Handle Delete Feedback
if (isset($_GET["delete"])) {
    $feedback_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM feedback WHERE feedback_id = $feedback_id AND employee_id = '$employee_id'");
    header("Location: submit_feedback.php");
}

// Fetch Employee Submitted Feedback
$feedbacks = mysqli_query($conn, "SELECT * FROM feedback WHERE employee_id = '$employee_id' ORDER BY submitted_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - Quality Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
        }
        .sidebar a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 260px;
            padding: 40px 20px;
            width: 100%;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<div class="m-header">
            <a href="#" class="b-brand text-primary">
            </a>
        </div>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="text-center text-white">Quality Management</h2>
        <li class="nav-item"><a class="nav-link text-white" href="employee_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="view_tasks.php"><i class="fas fa-tasks"></i> Tasks</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="report_issues.php"><i class="fas fa-exclamation-triangle"></i> Report Issues</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="submit_feedback.php"><i class="fas fa-comments"></i> Submit Customer Feedback</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="complete_training.php"><i class="fas fa-chalkboard-teacher"></i> Complete Training</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="view_kpis.php"><i class="fas fa-chart-bar"></i> View KPI Progress</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="employee_notifications.php"><i class="fas fa-bell"></i> View Notifications</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="employee_profile.php"><i class="fas fa-user-cog"></i> Profile Settings</a></li>

            <li class="nav-item"><a class="nav-link text-white" href="logout.php"><i class="fas fa-user-cog"></i> Logout</a></li>>

    </div>

    <div class="content">
    <!-- Submit Feedback Section -->
    <div class="container">
        <h2 class="text-center mb-4">Submit Feedback</h2>

        <!-- Submit Feedback Form -->
        <div class="mb-4">
            <form method="POST" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="feedback_text" class="form-control" placeholder="Your Feedback" required>
                </div>
                <div class="col-md-2">
                    <select name="rating" class="form-select" required>
                        <option value="1">⭐</option>
                        <option value="2">⭐⭐</option>
                        <option value="3">⭐⭐⭐</option>
                        <option value="4">⭐⭐⭐⭐</option>
                        <option value="5">⭐⭐⭐⭐⭐</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="submit_feedback" class="btn btn-success w-100">Submit</button>
                </div>
            </form>
        </div>

        <!-- Submitted Feedback Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Feedback</th>
                        <th>Rating</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($feedbacks)): ?>
                        <tr>
                            <td><?= $row["feedback_id"]; ?></td>
                            <td><?= $row["feedback_text"]; ?></td>
                            <td>
                                <?php for ($i = 0; $i < $row["rating"]; $i++): ?>
                                    ⭐
                                <?php endfor; ?>
                            </td>
                            <td><?= $row["submitted_at"]; ?></td>
                            <td>
                                <a href="?delete=<?= $row["feedback_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
                                </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
