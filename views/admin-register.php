<?php
session_start();

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

<h2>Admin Only</h2>
<form action="admin-register.php?key=onlyAdmins" method="POST">
    <input type="text" name="first_name" placeholder="First Name" required><br>
    <input type="text" name="last_name" placeholder="Last Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register as Admin</button>
</form>