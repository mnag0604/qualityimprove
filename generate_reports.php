<?php
session_start();
include "config.php"; // Database connection

// Check if manager is logged in
if (!isset($_SESSION["manager_id"])) {
    header("Location: manager_login.php");
    exit();
}

// Handle Add Report
if (isset($_POST["add_report"])) {
    $report_title = $_POST["report_title"];
    $description = $_POST["description"];
    $generated_by = $_SESSION["manager_id"];

    // Handle File Upload
    $target_dir = "uploads/";
    $file_name = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

    $sql = "INSERT INTO reports (report_title, description, generated_by, file_path) 
            VALUES ('$report_title', '$description', '$generated_by', '$target_file')";
    mysqli_query($conn, $sql);
    header("Location: generate_reports.php");
}

// Handle Delete Report
if (isset($_GET["delete"])) {
    $report_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM reports WHERE report_id = $report_id");
    header("Location: generate_reports.php");
}

// Fetch Reports Data
$reports = mysqli_query($conn, "SELECT r.*, u.full_name AS author FROM reports r 
                                LEFT JOIN users u ON r.generated_by = u.user_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports - Quality Management</title>
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
        <li class="nav-item"><a class="nav-link text-white" href="manager_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manage_tasks.php"><i class="fas fa-tasks"></i> Manage Assigned Tasks</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="track_kpis.php"><i class="fas fa-chart-bar"></i> Monitor KPIs</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="review_feedback.php"><i class="fas fa-comments"></i> Review Customer Feedback</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="resolve_issues.php"><i class="fas fa-exclamation-triangle"></i> Manage Issues</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="assign_training.php"><i class="fas fa-chalkboard-teacher"></i> Assign & Track Training</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="generate_reports.php"><i class="fas fa-file-alt"></i> Generate Reports</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="view_notifications.php"><i class="fas fa-bell"></i> View Alerts & Notifications</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="manager_profile.php"><i class="fas fa-user-cog"></i> Profile Settings</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="logout.php"><i class="fas fa-user-cog"></i> Logout</a></li>>
    </div>

    <div class="content">

    <!-- Generate Reports Section -->
    <div class="container">
        <h2 class="text-center mb-4">Generate and Manage Reports</h2>

        <!-- Add Report Form -->
        <div class="mb-4">
            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="report_title" class="form-control" placeholder="Report Title" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-3">
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="add_report" class="btn btn-success w-100">Generate Report</button>
                </div>
            </form>
        </div>

        <!-- Reports Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Report Title</th>
                        <th>Description</th>
                        <th>Generated By</th>
                        <th>File</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($reports)): ?>
                        <tr>
                            <td><?= $row["report_id"]; ?></td>
                            <td><?= $row["report_title"]; ?></td>
                            <td><?= $row["description"]; ?></td>
                            <td><?= $row["author"]; ?></td>
                            <td><a href="<?= $row["file_path"]; ?>" target="_blank">View File</a></td>
                            <td>
                                <a href="?delete=<?= $row["report_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
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
