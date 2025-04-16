<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle Add Compliance Rule
if (isset($_POST["add_compliance"])) {
    $rule_name = $_POST["rule_name"];
    $description = $_POST["description"];
    $status = $_POST["status"];
    $assigned_to = $_POST["assigned_to"];

    $sql = "INSERT INTO compliance (rule_name, description, status, assigned_to) 
            VALUES ('$rule_name', '$description', '$status', '$assigned_to')";
    mysqli_query($conn, $sql);
    header("Location: manage_compliance.php");
}

// Handle Delete Compliance Rule
if (isset($_GET["delete"])) {
    $compliance_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM compliance WHERE compliance_id = $compliance_id");
    header("Location: manage_compliance.php");
}

// Handle Edit Compliance Rule
if (isset($_POST["edit_compliance"])) {
    $compliance_id = $_POST["compliance_id"];
    $rule_name = $_POST["rule_name"];
    $description = $_POST["description"];
    $status = $_POST["status"];
    $assigned_to = $_POST["assigned_to"];

    mysqli_query($conn, "UPDATE compliance SET rule_name='$rule_name', description='$description', 
                        status='$status', assigned_to='$assigned_to' WHERE compliance_id='$compliance_id'");
    header("Location: manage_compliance.php");
}

// Fetch Compliance Data
$compliance_rules = mysqli_query($conn, "SELECT c.*, u.full_name FROM compliance c 
                                         LEFT JOIN users u ON c.assigned_to = u.user_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Compliance - Quality Management</title>
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
    <!-- Manage Compliance Section -->
    <div class="container">
        <h2 class="text-center mb-4">Manage Compliance Rules</h2>

        <!-- Add Compliance Form -->
        <div class="mb-4">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="rule_name" class="form-control" placeholder="Rule Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="Compliant">Compliant</option>
                        <option value="Non-Compliant">Non-Compliant</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="assigned_to" class="form-control" placeholder="Assigned To (User ID)" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_compliance" class="btn btn-success w-100">Add Rule</button>
                </div>
            </form>
        </div>

        <!-- Compliance Rules Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Rule Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($compliance_rules)): ?>
                        <tr>
                            <td><?= $row["compliance_id"]; ?></td>
                            <td><?= $row["rule_name"]; ?></td>
                            <td><?= $row["description"]; ?></td>
                            <td><?= $row["status"]; ?></td>
                            <td><?= $row["full_name"]; ?></td>
                            <td>
                                <a href="?delete=<?= $row["compliance_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editComplianceModal<?= $row['compliance_id']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Edit Compliance Modal -->
                        <div class="modal fade" id="editComplianceModal<?= $row['compliance_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Compliance Rule</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="compliance_id" value="<?= $row['compliance_id']; ?>">
                                            <div class="mb-3">
                                                <label>Rule Name</label>
                                                <input type="text" name="rule_name" class="form-control" value="<?= $row['rule_name']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Description</label>
                                                <input type="text" name="description" class="form-control" value="<?= $row['description']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="Compliant" <?= ($row['status'] == 'Compliant') ? 'selected' : ''; ?>>Compliant</option>
                                                    <option value="Non-Compliant" <?= ($row['status'] == 'Non-Compliant') ? 'selected' : ''; ?>>Non-Compliant</option>
                                                    <option value="Pending" <?= ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="edit_compliance" class="btn btn-primary">Update</button>
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
