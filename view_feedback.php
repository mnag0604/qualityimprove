<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle Delete Feedback
if (isset($_GET["delete"])) {
    $feedback_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM feedback WHERE feedback_id = $feedback_id");
    header("Location: view_feedback.php");
}

// Fetch Feedback Data
$feedbacks = mysqli_query($conn, "
    SELECT f.feedback_id, f.feedback_text, f.rating, f.submitted_at, 
           u.full_name AS employee_name, u.email AS employee_email
    FROM feedback f
    LEFT JOIN users u ON f.employee_id = u.user_id
    ORDER BY f.submitted_at DESC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback - Quality Management</title>
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
    <!-- View Feedback Section -->
    <div class="container">
        <h2 class="text-center mb-4">Customer Feedback</h2>

        <!-- Feedback Table -->
        <div class="table-container">
        <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Employee Name</th> <!-- Changed from Customer to Employee -->
            <th>Email</th>
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
                <td><?= $row["employee_name"]; ?></td> <!-- Correct Employee Name -->
                <td><?= $row["employee_email"]; ?></td> <!-- Correct Employee Email -->
                <td><?= $row["feedback_text"]; ?></td>
                <td class="rating">
                    <?php for ($i = 0; $i < $row["rating"]; $i++): ?>
                        ★
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
