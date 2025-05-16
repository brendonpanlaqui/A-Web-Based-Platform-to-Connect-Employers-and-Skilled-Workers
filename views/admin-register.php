<?php
include('../includes/nav.php');


require_once __DIR__ . '/../config/database.php';

$secretKey = 'onlyAdmins';
// http://localhost/A-Web-Based-Platform-to-Connect-Employers-and-Skilled-Workers/views/admin-register.php?key=onlyAdmins

// Check access key from URL
if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    http_response_code(403);
    exit("⛔ Access denied. Invalid or missing key.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $access_key = $_POST['access_key'] ?? '';

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }


    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user with admin role
    $stmt = $con->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, 'admin')");
    if (!$stmt) {
        echo "Database error: " . $con->error;
        exit();
    }

    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

    if ($stmt->execute()) {
        header("Location: ../views/admin-dashboard.php");
        exit();
    } else {
        echo "Registration failed: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoftEng2</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-white">
    <header class="text-dark pt-5 pb-3 mt-4 mt-md-5">
        <div class="container d-flex flex-column">
            <div class="col-12">
                <h2 class="display-4 fw-bold text-start text-md-center">Register as Admin</h2>
            </div>
        </div>
    </header>

    <div class="container d-flex justify-content-center">
        <div class="text-dark text-start">
        <form id="loginform"action="admin-register.php?key=onlyAdmins" method="POST" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control border-dark" id="first_name" name="first_name" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control border-dark" id="last_name" name="last_name" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control border-dark" id="email" name="email" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control border-dark" name="password" required><br>
                    </div>
                <div class="d-flex my-5 justify-content-center">
                    <button type="submit" class="btn btn-danger w-75">Register</button>
                </div>
                
            </div>
        </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>