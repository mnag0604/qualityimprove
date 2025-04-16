<?php
session_start();
include "config.php"; // Database connection

// Check if employee is logged in
if (!isset($_SESSION["employee_id"])) {
    header("Location: employee_login.php");
    exit();
}

$employee_id = $_SESSION["employee_id"];

// Fetch Assigned KPIs with NULL handling
$kpis = mysqli_query($conn, "SELECT * FROM kpis WHERE assigned_to = '$employee_id' AND assigned_to IS NOT NULL ORDER BY updated_at DESC");

// Debugging: Check if query fails
if (!$kpis) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View KPIs - Quality Management</title>
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
   

        <!-- View KPIs Section -->
        <div class="container mt-4">
            <h2 class="text-center mb-4">My Key Performance Indicators (KPIs)</h2>

            <!-- KPIs Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>KPI Name</th>
                            <th>Description</th>
                            <th>Target Value</th>
                            <th>Achieved Value</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($kpis)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["kpi_id"]); ?></td>
                                <td><?= htmlspecialchars($row["kpi_name"]); ?></td>
                                <td><?= htmlspecialchars($row["description"]); ?></td>
                                <td><?= htmlspecialchars($row["target_value"]); ?></td>
                                <td><?= htmlspecialchars($row["achieved_value"] ?? 0); // Default to 0 if NULL ?></td>
                                <td>
                                    <?php 
                                    $status_class = "bg-secondary"; // Default style
                                    if ($row["status"] == "On Track") {
                                        $status_class = "bg-success";
                                    } elseif ($row["status"] == "Needs Improvement") {
                                        $status_class = "bg-warning";
                                    } elseif ($row["status"] == "Critical") {
                                        $status_class = "bg-danger";
                                    }
                                    ?>
                                    <span class="badge <?= $status_class; ?>"><?= htmlspecialchars($row["status"] ?? "N/A"); ?></span>
                                </td>
                                <td><?= htmlspecialchars($row["updated_at"]); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include "includes/footer.php"; ?>
    </div>
                                </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
