<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle Add User
if (isset($_POST["add_user"])) {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = md5($_POST["password"]);
    $role = $_POST["role"];
    
    $sql = "INSERT INTO users (full_name, email, password_hash, role) VALUES ('$full_name', '$email', '$password', '$role')";
    mysqli_query($conn, $sql);
    header("Location: manage_users.php");
}

if (isset($_GET["delete"])) {
    $user_id = intval($_GET["delete"]); // Ensure it's an integer

    // Attempt to delete
    $sql = "DELETE FROM users WHERE user_id = $user_id";
    if (mysqli_query($conn, $sql)) {
        // Redirect back to the page after successful deletion
        header("Location: manage_users.php");
        exit();
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}




// Handle Edit User
if (isset($_POST["edit_user"])) {
    $user_id = $_POST["user_id"];
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    
    mysqli_query($conn, "UPDATE users SET full_name='$full_name', email='$email', role='$role' WHERE user_id='$user_id'");
    header("Location: manage_users.php");
}

// Fetch Users Data
$users = mysqli_query($conn, "SELECT * FROM users");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Quality Management</title>
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

    <!-- Manage Users Section -->
    <div class="container">
        <h2 class="text-center mb-4">Manage Users</h2>

        <!-- Add User Form -->
        <div class="mb-4">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-2">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select">
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="Employee">Employee</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_user" class="btn btn-success w-100">Add User</button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td><?= $row["user_id"]; ?></td>
                            <td><?= $row["full_name"]; ?></td>
                            <td><?= $row["email"]; ?></td>
                            <td><?= $row["role"]; ?></td>
                            <td>
                            <a href="manage_users.php?delete=<?= $row["user_id"]; ?>" class="btn btn-danger btn-sm" 
   onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $row['user_id']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal<?= $row['user_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="user_id" value="<?= $row['user_id']; ?>">
                                            <div class="mb-3">
                                                <label>Full Name</label>
                                                <input type="text" name="full_name" class="form-control" value="<?= $row['full_name']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="<?= $row['email']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Role</label>
                                                <select name="role" class="form-select">
                                                    <option value="Admin" <?= ($row['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                                    <option value="Manager" <?= ($row['role'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                                                    <option value="Employee" <?= ($row['role'] == 'Employee') ? 'selected' : ''; ?>>Employee</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="edit_user" class="btn btn-primary">Update</button>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
