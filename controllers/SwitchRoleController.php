<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$newRole = $_GET['role'] ?? '';
$validRoles = ['employer', 'worker'];

if (!in_array($newRole, $validRoles)) {
    die('Invalid role');
}

$userId = $_SESSION['user_id'];

$stmt = $con->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->bind_param("si", $newRole, $userId);
$stmt->execute();

$_SESSION['role'] = $newRole;

// Redirect to appropriate dashboard
$redirect = $newRole === 'worker' ? 'employee-dashboard.php' : 'employer-dashboard.php';
header("Location: ../views/$redirect");
exit();
