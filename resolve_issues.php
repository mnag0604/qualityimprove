<?php
session_start();
include "config.php"; // Database connection

// Check if manager is logged in
if (!isset($_SESSION["manager_id"])) {
    header("Location: manager_login.php");
    exit();
}

// Handle Resolve Issue
if (isset($_POST["resolve_issue"])) {
    $issue_id = $_POST["issue_id"];
    $resolution_notes = $_POST["resolution_notes"];
    $status = "Resolved";

    mysqli_query($conn, "UPDATE issues SET status='$status', resolution_notes='$resolution_notes' WHERE issue_id='$issue_id'");
    header("Location: resolve_issues.php");
}

// Handle Delete Issue
if (isset($_GET["delete"])) {
    $issue_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM issues WHERE issue_id = $issue_id");
    header("Location: resolve_issues.php");
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
    <title>Resolve Issues - Quality Management</title>
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

    <!-- Resolve Issues Section -->
    <div class="container">
        <h2 class="text-center mb-4">Resolve Reported Issues</h2>

        <!-- Issues Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Issue Title</th>
                        <th>Description</th>
                        <th>Reported By</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Resolution Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($issues)): ?>
                        <tr>
                            <td><?= $row["issue_id"]; ?></td>
                            <td><?= $row["issue_title"]; ?></td>
                            <td><?= $row["description"]; ?></td>
                            <td><?= $row["reporter"]; ?></td>
                            <td><?= $row["assignee"]; ?></td>
                            <td>
                                <?php if ($row["status"] == "Resolved"): ?>
                                    <span class="badge bg-success"><?= $row["status"]; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-warning"><?= $row["status"]; ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row["resolution_notes"] ? $row["resolution_notes"] : "<span class='text-muted'>Not Resolved</span>"; ?></td>
                            <td>
                                <?php if ($row["status"] != "Resolved"): ?>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#resolveIssueModal<?= $row['issue_id']; ?>">Resolve</button>
                                <?php endif; ?>
                                <a href="?delete=<?= $row["issue_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>

                        <!-- Resolve Issue Modal -->
                        <div class="modal fade" id="resolveIssueModal<?= $row['issue_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Resolve Issue</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="issue_id" value="<?= $row['issue_id']; ?>">
                                            <div class="mb-3">
                                                <label>Resolution Notes</label>
                                                <textarea name="resolution_notes" class="form-control" required></textarea>
                                            </div>
                                            <button type="submit" name="resolve_issue" class="btn btn-primary">Mark as Resolved</button>
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
