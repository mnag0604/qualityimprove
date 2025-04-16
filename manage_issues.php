<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle Add Issue
if (isset($_POST["add_issue"])) {
    $issue_title = $_POST["issue_title"];
    $description = $_POST["description"];
    $reported_by = $_SESSION["admin_id"];
    $assigned_to = $_POST["assigned_to"];
    $status = $_POST["status"];

    $sql = "INSERT INTO issues (issue_title, description, reported_by, assigned_to, status) 
            VALUES ('$issue_title', '$description', '$reported_by', '$assigned_to', '$status')";
    mysqli_query($conn, $sql);
    header("Location: manage_issues.php");
}

// Handle Delete Issue
if (isset($_GET["delete"])) {
    $issue_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM issues WHERE issue_id = $issue_id");
    header("Location: manage_issues.php");
}

// Handle Edit Issue
if (isset($_POST["edit_issue"])) {
    $issue_id = intval($_POST["issue_id"]); // Ensure it's an integer
    $issue_title = $_POST["issue_title"];
    $description = $_POST["description"];
    $assigned_to = intval($_POST["assigned_to"]); // Ensure it's an integer
    $status = $_POST["status"];

    // Debugging: Print received form data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Update Query
    $sql = "UPDATE issues SET issue_title='$issue_title', description='$description', 
            assigned_to='$assigned_to', status='$status' WHERE issue_id='$issue_id'";

    // Execute Query and Debug Errors
    if (mysqli_query($conn, $sql)) {
        echo "Issue updated successfully!";
        header("Location: manage_issues.php");
        exit();
    } else {
        die("Database Update Error: " . mysqli_error($conn));
    }
}


// Fetch Issues Data
$issues = mysqli_query($conn, "SELECT i.*, u1.full_name AS reporter, u2.full_name AS assignee 
                               FROM issues i
                               LEFT JOIN users u1 ON i.reported_by = u1.user_id
                               LEFT JOIN users u2 ON i.assigned_to = u2.user_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Issues - Quality Management</title>
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
    <!-- Manage Issues Section -->
    <div class="container">
        <h2 class="text-center mb-4">Manage Issues</h2>

        <!-- Add Issue Form -->
        <div class="mb-4">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="issue_title" class="form-control" placeholder="Issue Title" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="assigned_to" class="form-control" placeholder="Assigned To (User ID)" required>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="Open">Open</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Resolved">Resolved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_issue" class="btn btn-success w-100">Add Issue</button>
                </div>
            </form>
        </div>

        <!-- Issues Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Issue Title</th>
                        <th>Description</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($issues)): ?>
                        <tr>
                            <td><?= $row["issue_id"]; ?></td>
                            <td><?= $row["issue_title"]; ?></td>
                            <td><?= $row["description"]; ?></td>
                            <td><?= $row["assigned_to"]; ?></td>
                            <td><?= $row["status"]; ?></td>
                            <td>
                                <a href="?delete=<?= $row["issue_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editIssueModal<?= $row['issue_id']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Edit Issue Modal -->
                        <div class="modal fade" id="editIssueModal<?= $row['issue_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Issue</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                    <form method="POST">
    <input type="hidden" name="issue_id" value="<?= $row['issue_id']; ?>">
    <div class="mb-3">
        <label>Issue Title</label>
        <input type="text" name="issue_title" class="form-control" value="<?= $row['issue_title']; ?>" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <input type="text" name="description" class="form-control" value="<?= $row['description']; ?>" required>
    </div>
    <div class="mb-3">
        <label>Assigned To</label>
        <input type="number" name="assigned_to" class="form-control" value="<?= $row['assigned_to']; ?>" required>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select">
            <option value="Open" <?= ($row['status'] == 'Open') ? 'selected' : ''; ?>>Open</option>
            <option value="In Progress" <?= ($row['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
            <option value="Resolved" <?= ($row['status'] == 'Resolved') ? 'selected' : ''; ?>>Resolved</option>
        </select>
    </div>
    <button type="submit" name="edit_issue" class="btn btn-primary">Update</button>
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
