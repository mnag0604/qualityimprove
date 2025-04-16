<?php
session_start();
include "config.php"; // Database connection

// Check if employee is logged in
if (!isset($_SESSION["employee_id"])) {
    header("Location: employee_login.php");
    exit();
}

$employee_id = $_SESSION["employee_id"];
$employee = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$employee_id'"));

// Handle Profile Update
if (isset($_POST["update_profile"])) {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    // Handle Profile Picture Upload
    if ($_FILES["profile_pic"]["name"] != "") {
        $target_dir = "uploads/";
        $file_name = basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);

        mysqli_query($conn, "UPDATE users SET full_name='$full_name', email='$email', phone='$phone', profile_pic='$target_file' WHERE user_id='$employee_id'");
    } else {
        mysqli_query($conn, "UPDATE users SET full_name='$full_name', email='$email', phone='$phone' WHERE user_id='$employee_id'");
    }

    $_SESSION["employee_name"] = $full_name; // Update session with new name
    header("Location: employee_profile.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile - Quality Management</title>
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
        <li class="nav-item"><a class="nav-link text-white" href="employee_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="view_tasks.php"><i class="fas fa-tasks"></i> Tasks</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="report_issues.php"><i class="fas fa-exclamation-triangle"></i> Report Issues</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="submit_feedback.php"><i class="fas fa-comments"></i> Submit Customer Feedback</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="complete_training.php"><i class="fas fa-chalkboard-teacher"></i> Complete Training</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="view_kpis.php"><i class="fas fa-chart-bar"></i> View KPI Progress</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="employee_notifications.php"><i class="fas fa-bell"></i> View Notifications</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="employee_profile.php"><i class="fas fa-user-cog"></i> Profile Settings</a></li>

            <li class="nav-item"><a class="nav-link text-white" href="logout.php"><i class="fas fa-user-cog"></i> Logout</a></li>

    </div>

    <div class="content">

    <!-- Employee Profile Section -->
    <div class="profile-container text-center">
        <h3>Employee Profile</h3>
        <img src="<?= ($employee['profile_pic']) ? $employee['profile_pic'] : 'uploads/default.jpg'; ?>" class="profile-pic" alt="Profile Picture">
        <h4><?= $employee["full_name"]; ?></h4>
        <p><strong>Email:</strong> <?= $employee["email"]; ?></p>
        <p><strong>Phone:</strong> <?= $employee["phone"] ?: "Not Set"; ?></p>

        <!-- Edit Profile Button -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?= $employee['full_name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $employee['email']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= $employee['phone']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>Profile Picture</label>
                            <input type="file" name="profile_pic" class="form-control">
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-success w-100">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
