<?php
session_start();
include "config.php"; // Database connection

// Check if employee is logged in
if (!isset($_SESSION["employee_id"])) {
    header("Location: employee_login.php");
    exit();
}

$employee_id = $_SESSION["employee_id"];

// Handle Mark as Read
if (isset($_GET["mark_read"])) {
    $notification_id = $_GET["mark_read"];
    mysqli_query($conn, "UPDATE notifications SET status = 'Read' WHERE notification_id = $notification_id AND user_id = '$employee_id'");
    header("Location: employee_notifications.php");
}

// Handle Delete Notification
if (isset($_GET["delete"])) {
    $notification_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM notifications WHERE notification_id = $notification_id AND user_id = '$employee_id'");
    header("Location: employee_notifications.php");
}

// Fetch Notifications Data
$notifications = mysqli_query($conn, "SELECT * FROM notifications WHERE user_id = '$employee_id' ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Notifications - Quality Management</title>
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

    <!-- Employee Notifications Section -->
    <div class="container">
        <h2 class="text-center mb-4">My Notifications</h2>

        <!-- Notifications Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($notifications)): ?>
                        <tr>
                            <td><?= $row["notification_id"]; ?></td>
                            <td><?= $row["message"]; ?></td>
                            <td>
                                <?php if ($row["status"] == "Unread"): ?>
                                    <span class="badge bg-warning"><?= $row["status"]; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= $row["status"]; ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row["created_at"]; ?></td>
                            <td>
                                <?php if ($row["status"] == "Unread"): ?>
                                    <a href="?mark_read=<?= $row["notification_id"]; ?>" class="btn btn-info btn-sm">Mark as Read</a>
                                <?php endif; ?>
                                <a href="?delete=<?= $row["notification_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
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
