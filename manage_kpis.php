<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle Add KPI
if (isset($_POST["add_kpi"])) {
    $kpi_name = $_POST["kpi_name"];
    $description = $_POST["description"];
    $target_value = $_POST["target_value"];
    $achieved_value = $_POST["achieved_value"];
    $department = $_POST["department"];
    $status = $_POST["status"];

    $sql = "INSERT INTO kpis (kpi_name, description, target_value, achieved_value, department, status) 
            VALUES ('$kpi_name', '$description', '$target_value', '$achieved_value', '$department', '$status')";
    mysqli_query($conn, $sql);
    header("Location: manage_kpis.php");
}

// Handle Delete KPI
if (isset($_GET["delete"])) {
    $kpi_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM kpis WHERE kpi_id = $kpi_id");
    header("Location: manage_kpis.php");
}

// Handle Edit KPI
if (isset($_POST["edit_kpi"])) {
    $kpi_id = $_POST["kpi_id"];
    $kpi_name = $_POST["kpi_name"];
    $description = $_POST["description"];
    $target_value = $_POST["target_value"];
    $achieved_value = $_POST["achieved_value"];
    $department = $_POST["department"];
    $status = $_POST["status"];

    mysqli_query($conn, "UPDATE kpis SET kpi_name='$kpi_name', description='$description', target_value='$target_value', 
                        achieved_value='$achieved_value', department='$department', status='$status' WHERE kpi_id='$kpi_id'");
    header("Location: manage_kpis.php");
}

// Fetch KPI Data
$kpis = mysqli_query($conn, "SELECT * FROM kpis");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage KPIs - Quality Management</title>
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

    <!-- Manage KPIs Section -->
    <div class="container">
        <h2 class="text-center mb-4">Manage KPIs</h2>

        <!-- Add KPI Form -->
        <div class="mb-4">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="kpi_name" class="form-control" placeholder="KPI Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="target_value" class="form-control" placeholder="Target Value" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="achieved_value" class="form-control" placeholder="Achieved Value" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="department" class="form-control" placeholder="Department" required>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="On Track">On Track</option>
                        <option value="Needs Improvement">Needs Improvement</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_kpi" class="btn btn-success w-100">Add KPI</button>
                </div>
            </form>
        </div>

        <!-- KPIs Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>KPI Name</th>
                        <th>Description</th>
                        <th>Target</th>
                        <th>Achieved</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($kpis)): ?>
                        <tr>
                            <td><?= $row["kpi_id"]; ?></td>
                            <td><?= $row["kpi_name"]; ?></td>
                            <td><?= $row["description"]; ?></td>
                            <td><?= $row["target_value"]; ?></td>
                            <td><?= $row["achieved_value"]; ?></td>
                            <td><?= $row["department"]; ?></td>
                            <td><?= $row["status"]; ?></td>
                            <td>
                                <a href="?delete=<?= $row["kpi_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKPIModal<?= $row['kpi_id']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Edit KPI Modal -->
                        <div class="modal fade" id="editKPIModal<?= $row['kpi_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit KPI</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="kpi_id" value="<?= $row['kpi_id']; ?>">
                                            <input type="text" name="kpi_name" class="form-control" value="<?= $row['kpi_name']; ?>" required>
                                            <input type="text" name="description" class="form-control" value="<?= $row['description']; ?>" required>
                                            <button type="submit" name="edit_kpi" class="btn btn-primary">Update</button>
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
