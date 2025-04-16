<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

if (isset($_POST["add_training"])) {
    $training_name = $_POST["training_name"];
    $description = $_POST["description"];
    $assigned_to = intval($_POST["assigned_to"]); // Ensure it's an integer
    $completion_status = $_POST["completion_status"];

    // Debugging: Print received form data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Insert Query
    $sql = "INSERT INTO training (training_name, description, assigned_to, completion_status) 
            VALUES ('$training_name', '$description', '$assigned_to', '$completion_status')";

    // Execute Query and Debug Errors
    if (mysqli_query($conn, $sql)) {
        echo "Training added successfully!";
        header("Location: manage_training.php");
        exit();
    } else {
        die("Database Insert Error: " . mysqli_error($conn));
    }
}




// Handle Delete Training
if (isset($_GET["delete"])) {
    $training_id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM training WHERE training_id = $training_id");
    header("Location: manage_training.php");
}

// Handle Edit Training
if (isset($_POST["edit_training"])) {
    $training_id = intval($_POST["training_id"]); // Ensure it's an integer
    $training_name = $_POST["training_name"];
    $description = $_POST["description"];
    $assigned_to = intval($_POST["assigned_to"]);
    $completion_status = $_POST["completion_status"];

    // Debugging: Print received form data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Update Query
    $sql = "UPDATE training SET training_name='$training_name', description='$description', 
            assigned_to='$assigned_to', completion_status='$completion_status' 
            WHERE training_id='$training_id'";

    // Execute Query and Debug Errors
    if (mysqli_query($conn, $sql)) {
        echo "Training updated successfully!";
        header("Location: manage_training.php");
        exit();
    } else {
        die("Database Update Error: " . mysqli_error($conn));
    }
}


// Fetch Training Data
$training_data = mysqli_query($conn, "SELECT t.*, u.full_name AS trainee FROM training t 
                                      LEFT JOIN users u ON t.assigned_to = u.user_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Training - Quality Management</title>
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

    <!-- Manage Training Section -->
    <div class="container">
        <h2 class="text-center mb-4">Manage Training</h2>

        <!-- Add Training Form -->
        <div class="mb-4">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="training_name" class="form-control" placeholder="Training Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="assigned_to" class="form-control" placeholder="Assigned To (User ID)" required>
                </div>
                <div class="col-md-3">
                    <select name="completion_status" class="form-select">
                        <option value="Not Started">Not Started</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="add_training" class="btn btn-success w-100">Add Training</button>
                </div>
            </form>
        </div>

        <!-- Training Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Training Name</th>
                        <th>Description</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($training_data)): ?>
                        <tr>
                            <td><?= $row["training_id"]; ?></td>
                            <td><?= $row["training_name"]; ?></td>
                            <td><?= $row["description"]; ?></td>
                            <td><?= $row["trainee"]; ?></td>
                            <td><?= $row["completion_status"]; ?></td>
                            <td>
                                <a href="?delete=<?= $row["training_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTrainingModal<?= $row['training_id']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Edit Training Modal -->
<div class="modal fade" id="editTrainingModal<?= $row['training_id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Training</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="training_id" value="<?= $row['training_id']; ?>">
                    <div class="mb-3">
                        <label>Training Name</label>
                        <input type="text" name="training_name" class="form-control" value="<?= $row['training_name']; ?>" required>
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
                        <select name="completion_status" class="form-select">
                            <option value="Not Started" <?= ($row['completion_status'] == 'Not Started') ? 'selected' : ''; ?>>Not Started</option>
                            <option value="In Progress" <?= ($row['completion_status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Completed" <?= ($row['completion_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                    <button type="submit" name="edit_training" class="btn btn-primary">Update</button>
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
