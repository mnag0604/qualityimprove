<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle Add Process
if (isset($_POST["add_process"])) {
    $process_name = $_POST["process_name"];
    $description = $_POST["description"];
    $department = $_POST["department"];
    $status = $_POST["status"];

    $sql = "INSERT INTO processes (process_name, description, department, status, created_by) VALUES ('$process_name', '$description', '$department', '$status', '{$_SESSION['admin_id']}')";
    mysqli_query($conn, $sql);
    header("Location: manage_processes.php");
}

// Handle Delete Process
if (isset($_GET["delete"])) {
    $process_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM processes WHERE process_id = $process_id");
    header("Location: manage_processes.php");
}

// Handle Edit Process
if (isset($_POST["edit_process"])) {
    $process_id = $_POST["process_id"];
    $process_name = $_POST["process_name"];
    $description = $_POST["description"];
    $department = $_POST["department"];
    $status = $_POST["status"];

    mysqli_query($conn, "UPDATE processes SET process_name='$process_name', description='$description', department='$department', status='$status' WHERE process_id='$process_id'");
    header("Location: manage_processes.php");
}

// Fetch Processes Data
$processes = mysqli_query($conn, "SELECT * FROM processes");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Processes - Quality Management</title>
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
    <!-- Manage Processes Section -->
    <div class="container">
        <h2 class="text-center mb-4">Manage Business Processes</h2>

        <!-- Add Process Form -->
        <div class="mb-4">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="process_name" class="form-control" placeholder="Process Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="department" class="form-control" placeholder="Department" required>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_process" class="btn btn-success w-100">Add Process</button>
                </div>
            </form>
        </div>

        <!-- Processes Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Process Name</th>
                        <th>Description</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($processes)): ?>
                        <tr>
                            <td><?= $row["process_id"]; ?></td>
                            <td><?= $row["process_name"]; ?></td>
                            <td><?= $row["description"]; ?></td>
                            <td><?= $row["department"]; ?></td>
                            <td><?= $row["status"]; ?></td>
                            <td>
                                <a href="?delete=<?= $row["process_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProcessModal<?= $row['process_id']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Edit Process Modal -->
                        <div class="modal fade" id="editProcessModal<?= $row['process_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Process</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="process_id" value="<?= $row['process_id']; ?>">
                                            <div class="mb-3">
                                                <label>Process Name</label>
                                                <input type="text" name="process_name" class="form-control" value="<?= $row['process_name']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Description</label>
                                                <input type="text" name="description" class="form-control" value="<?= $row['description']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Department</label>
                                                <input type="text" name="department" class="form-control" value="<?= $row['department']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="Active" <?= ($row['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                    <option value="Inactive" <?= ($row['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="edit_process" class="btn btn-primary">Update</button>
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
