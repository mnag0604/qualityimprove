<?php
session_start();
include "config.php"; // Database connection

// Check if manager is logged in
if (!isset($_SESSION["manager_id"])) {
    header("Location: manager_login.php");
    exit();
}

// Handle Add Task
if (isset($_POST["add_task"])) {
    $task_name = $_POST["task_name"];
    $description = $_POST["description"];
    $assigned_to = $_POST["assigned_to"];
    $status = $_POST["status"];

    $sql = "INSERT INTO tasks (task_name, description, assigned_to, status) 
            VALUES ('$task_name', '$description', '$assigned_to', '$status')";
    mysqli_query($conn, $sql);
    header("Location: manage_tasks.php");
}

// Handle Delete Task
if (isset($_GET["delete"])) {
    $task_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM tasks WHERE task_id = $task_id");
    header("Location: manage_tasks.php");
}

// Handle Edit Task
if (isset($_POST["edit_task"])) {
    $task_id = $_POST["task_id"];
    $task_name = $_POST["task_name"];
    $description = $_POST["description"];
    $assigned_to = $_POST["assigned_to"];
    $status = $_POST["status"];

    mysqli_query($conn, "UPDATE tasks SET task_name='$task_name', description='$description', 
                        assigned_to='$assigned_to', status='$status' WHERE task_id='$task_id'");
    header("Location: manage_tasks.php");
}

// Fetch Tasks Data
$tasks = mysqli_query($conn, "SELECT t.*, u.full_name AS employee FROM tasks t 
                              LEFT JOIN users u ON t.assigned_to = u.user_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks - Quality Management</title>
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
    <!-- Manage Tasks Section -->
    <div class="container">
        <h2 class="text-center mb-4">Manage Tasks</h2>

        <!-- Add Task Form -->
        <div class="mb-4">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="task_name" class="form-control" placeholder="Task Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="assigned_to" class="form-control" placeholder="Assigned To (User ID)" required>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_task" class="btn btn-success w-100">Add Task</button>
                </div>
            </form>
        </div>

        <!-- Tasks Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($tasks)): ?>
                        <tr>
                            <td><?= $row["task_id"]; ?></td>
                            <td><?= $row["task_name"]; ?></td>
                            <td><?= $row["description"]; ?></td>
                            <td><?= $row["employee"]; ?></td>
                            <td><?= $row["status"]; ?></td>
                            <td>
                                <a href="?delete=<?= $row["task_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTaskModal<?= $row['task_id']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Edit Task Modal -->
                        <div class="modal fade" id="editTaskModal<?= $row['task_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Task</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="task_id" value="<?= $row['task_id']; ?>">
                                            <div class="mb-3">
                                                <label>Task Name</label>
                                                <input type="text" name="task_name" class="form-control" value="<?= $row['task_name']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Description</label>
                                                <input type="text" name="description" class="form-control" value="<?= $row['description']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="Pending" <?= ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="In Progress" <?= ($row['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                    <option value="Completed" <?= ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="edit_task" class="btn btn-primary">Update</button>
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
