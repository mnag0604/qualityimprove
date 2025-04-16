<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch insights from the database
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users"))['count'];
$total_kpis = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM kpis"))['count'];
$total_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM reports"))['count'];
$total_issues = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM issues"))['count'];
$total_feedback = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM feedback"))['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quality Management</title>
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

        <!-- Dashboard Content -->
        <div class="container dashboard-container">
            <h2 class="text-center mb-4">Admin Dashboard</h2>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white text-center p-3">
                        <h4>Total Users</h4>
                        <h2><?= $total_users; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white text-center p-3">
                        <h4>Total KPIs</h4>
                        <h2><?= $total_kpis; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white text-center p-3">
                        <h4>Total Reports</h4>
                        <h2><?= $total_reports; ?></h2>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-danger text-white text-center p-3">
                        <h4>Total Issues</h4>
                        <h2><?= $total_issues; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white text-center p-3">
                        <h4>Total Feedback</h4>
                        <h2><?= $total_feedback; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
