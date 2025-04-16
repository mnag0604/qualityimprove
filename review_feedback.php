<?php
session_start();
include "config.php"; // Database connection

// Check if manager is logged in
if (!isset($_SESSION["manager_id"])) {
    header("Location: manager_login.php");
    exit();
}

// Handle Delete Feedback
if (isset($_GET["delete"])) {
    $feedback_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM feedback WHERE feedback_id = $feedback_id");
    header("Location: review_feedback.php");
}

// Handle Response to Feedback
if (isset($_POST["respond_feedback"])) {
    $feedback_id = $_POST["feedback_id"];
    $response = $_POST["response"];

    mysqli_query($conn, "UPDATE feedback SET response='$response', status='Reviewed' WHERE feedback_id='$feedback_id'");
    header("Location: review_feedback.php");
}

// Fetch Feedback Data
$feedbacks = mysqli_query($conn, "SELECT * FROM feedback ORDER BY submitted_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Feedback - Quality Management</title>
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

    <!-- Review Feedback Section -->
    <div class="container">
        <h2 class="text-center mb-4">Review and Respond to Feedback</h2>

        <!-- Feedback Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Employee name</th>
                        <th>Feedback</th>
                        <th>Rating</th>
                        <th>Submitted At</th>
                        <th>Response</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($feedbacks)): ?>
                        <tr>
                            <td><?= $row["feedback_id"]; ?></td>
                            <td><?= $row["employee_id"]; ?></td>
                            <td><?= $row["feedback_text"]; ?></td>
                            <td>
                                <?php for ($i = 0; $i < $row["rating"]; $i++): ?>
                                    ★
                                <?php endfor; ?>
                            </td>
                            <td><?= $row["submitted_at"]; ?></td>
                            <td><?= $row["response"] ? $row["response"] : "<span class='text-muted'>No Response</span>"; ?></td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#respondFeedbackModal<?= $row['feedback_id']; ?>">Respond</button>
                                <a href="?delete=<?= $row["feedback_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>

                        <!-- Respond Feedback Modal -->
                        <div class="modal fade" id="respondFeedbackModal<?= $row['feedback_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Respond to Feedback</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="feedback_id" value="<?= $row['feedback_id']; ?>">
                                            <div class="mb-3">
                                                <label>Response</label>
                                                <textarea name="response" class="form-control" required><?= $row['response']; ?></textarea>
                                            </div>
                                            <button type="submit" name="respond_feedback" class="btn btn-primary">Submit Response</button>
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
