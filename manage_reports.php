<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle Add Report
if (isset($_POST["add_report"])) {
    $report_title = $_POST["report_title"];
    $description = $_POST["description"];
    $generated_by = $_SESSION["admin_id"];

    // Debug: Check if file is received
    if (!isset($_FILES["file"]) || $_FILES["file"]["error"] != 0) {
        die("File upload error: " . $_FILES["file"]["error"]);
    }

    // Ensure the upload directory exists
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Generate a unique file name
    $file_name = time() . "_" . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;

    // Debug: Check file path
    echo "File will be saved to: " . $target_file . "<br>";

    // Move the file and check if it works
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "File uploaded successfully.<br>";

        $sql = "INSERT INTO reports (report_title, description, generated_by, file_path) 
                VALUES ('$report_title', '$description', '$generated_by', '$target_file')";

        if (mysqli_query($conn, $sql)) {
            echo "Report added successfully.";
            header("Location: manage_reports.php");
            exit();
        } else {
            die("Database Insert Error: " . mysqli_error($conn));
        }
    } else {
        die("File move failed!");
    }
}



// Handle Delete Report
if (isset($_GET["delete"])) {
    $report_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM reports WHERE report_id = $report_id");
    header("Location: manage_reports.php");
}

// Handle Edit Report
if (isset($_POST["edit_report"])) {
    $report_id = $_POST["report_id"];
    $report_title = $_POST["report_title"];
    $description = $_POST["description"];

    mysqli_query($conn, "UPDATE reports SET report_title='$report_title', description='$description' WHERE report_id='$report_id'");
    header("Location: manage_reports.php");
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

    <!-- Manage Reports Section -->
    <div class="container">
        <h2 class="text-center mb-4">Manage Reports</h2>

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
                    <button type="submit" name="add_report" class="btn btn-success w-100">Add Report</button>
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
                            <td>
    <?php if (!empty($row["file_path"]) && file_exists($row["file_path"])): ?>
        <a href="<?= $row["file_path"]; ?>" target="_blank">View File</a>
    <?php else: ?>
        <span class="text-danger">File Not Found</span>
    <?php endif; ?>
</td>
                            <td>
                                <a href="?delete=<?= $row["report_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editReportModal<?= $row['report_id']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Edit Report Modal -->
                        <div class="modal fade" id="editReportModal<?= $row['report_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Report</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="report_id" value="<?= $row['report_id']; ?>">
                                            <div class="mb-3">
                                                <label>Report Title</label>
                                                <input type="text" name="report_title" class="form-control" value="<?= $row['report_title']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Description</label>
                                                <input type="text" name="description" class="form-control" value="<?= $row['description']; ?>" required>
                                            </div>
                                            <button type="submit" name="edit_report" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
                    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
