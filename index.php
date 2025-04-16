<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quality Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            background: linear-gradient(to right, #4e73df, #1cc88a);
            color: white;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .hero-section {
            text-align: center;
            padding: 50px;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.2rem;
        }
        .card {
            border: none;
            border-radius: 12px;
            transition: transform 0.3s ease-in-out;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card a {
            text-decoration: none;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.8) !important;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .footer {
            background: rgba(0, 0, 0, 0.8);
            text-align: center;
            padding: 15px;
            margin-top: 50px;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Quality Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container hero-section">
        <h1>Welcome to the Quality Management System</h1>
        <p>Ensuring efficiency, compliance, and performance tracking for your organization.</p>
    </div>

    <!-- Login Options -->
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card p-4 bg-light">
                    <h3>Admin Portal</h3>
                    <p>Manage users, compliance, KPIs, and reports.</p>
                    <a href="adminlogin.php" class="btn btn-primary">Admin Login</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 bg-light">
                    <h3>Manager Portal</h3>
                    <p>Track KPIs, manage tasks, and review issues.</p>
                    <a href="manager_login.php" class="btn btn-success">Manager Login</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 bg-light">
                    <h3>Employee Portal</h3>
                    <p>Submit feedback, complete training, and report issues.</p>
                    <a href="employee_login.php" class="btn btn-info">Employee Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- About the Project -->
    <div class="container mt-5">
        <h2 class="text-center">About the Project</h2>
        <p class="text-center">
            This Quality Management System (QMS) is designed to enhance workflow efficiency,
            track performance, and ensure compliance with industry standards.  
            The system allows **admins, managers, and employees** to work collaboratively
            in a structured and automated manner.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Quality Management System. All rights reserved.</p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
