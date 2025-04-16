<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle Add Notification
if (isset($_POST["add_notification"])) {
    $message = $_POST["message"];
    $user_id = $_POST["user_id"];

    $sql = "INSERT INTO notifications (user_id, message, status) VALUES ('$user_id', '$message', 'Unread')";
    mysqli_query($conn, $sql);
    header("Location: manage_notifications.php");
}

// Handle Delete Notification
if (isset($_GET["delete"])) {
    $notification_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM notifications WHERE notification_id = $notification_id");
    header("Location: manage_notifications.php");
}

// Handle Mark as Read
if (isset($_GET["mark_read"])) {
    $notification_id = $_GET["mark_read"];
    mysqli_query($conn, "UPDATE notifications SET status = 'Read' WHERE notification_id = $notification_id");
    header("Location: manage_notifications.php");
}

// Fetch Notifications Data
$notifications = mysqli_query($conn, "SELECT n.*, u.full_name AS recipient FROM notifications n 
                                      LEFT JOIN users u ON n.user_id = u.user_id 
                                      ORDER BY n.created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notifications - Quality Management</title>
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
        <li class="nav-item"><a class="nav-link text-white" href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manage_processes.php"><i class="fas fa-cogs"></i> Processes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manage_reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manage_kpis.php"><i class="fas fa-chart-line"></i> Manage KPIs</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manage_compliance.php"><i class="fas fa-shield-alt"></i> Manage Compliance</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manage_issues.php"><i class="fas fa-exclamation-triangle"></i> Manage Issues</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manage_training.php"><i class="fas fa-chalkboard-teacher"></i> Manage Training</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="view_feedback.php"><i class="fas fa-comments"></i> View Customer Feedback</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manage_notifications.php"><i class="fas fa-bell"></i> Send Notifications</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="admin_profile.php"><i class="fas fa-user-cog"></i> Profile Settings</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="logout.php"><i class="fas fa-user-cog"></i> Logout</a></li>>
    </div>

    <div class="content">

    <!-- Manage Notifications Section -->
    <div class="container">
        <h2 class="text-center mb-4">Manage Notifications</h2>

        <!-- Add Notification Form -->
        <div class="mb-4">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="message" class="form-control" placeholder="Notification Message" required>
                </div>
                <div class="col-md-4">
                    <input type="number" name="user_id" class="form-control" placeholder="User ID (Recipient)" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_notification" class="btn btn-success w-100">Send Notification</button>
                </div>
            </form>
        </div>

        <!-- Notifications Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Recipient</th>
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
                            <td><?= $row["recipient"]; ?></td>
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
