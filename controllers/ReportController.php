<?php
session_start();
require_once '../config/database.php';

// Ensure the user is logged in
$reporter_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$reporter_id) {
    echo "<script>alert('You must be logged in to submit a report.'); window.location.href = '../views/login.php';</script>";
    exit;
}

$reported_type = $_POST['reported_type'] ?? '';
$reported_id = $_POST['reported_id'] ?? '';
$reason = $_POST['reason'] ?? '';

if (!$reported_type || !$reported_id || !$reason) {
    echo "<script>alert('Missing required data.'); window.history.back();</script>";
    exit;
}

$validTypes = ['user', 'job'];
if (!in_array($reported_type, $validTypes)) {
    echo "<script>alert('Invalid report type.'); window.history.back();</script>";
    exit;
}

// Check if the user has reported the same user/job within the last 7 days
$sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
$stmt = $con->prepare("SELECT * FROM reports WHERE reporter_id = ? AND reported_type = ? AND reported_id = ? AND created_at >= ?");
$stmt->bind_param("isis", $reporter_id, $reported_type, $reported_id, $sevenDaysAgo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('You have already reported this $reported_type within the last 7 days.'); window.history.back();</script>";
    exit;
}

// Insert the new report
$stmt = $con->prepare("INSERT INTO reports (reporter_id, reported_type, reported_id, reason) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isis", $reporter_id, $reported_type, $reported_id, $reason);

if ($stmt->execute()) {
    $redirect = ($user_role === 'employer') ? '../views/employer-dashboard.php' : '../views/employee-dashboard.php';
    echo "<script>alert('✅ Report submitted successfully.'); window.location.href = '$redirect';</script>";
} else {
    echo "<script>alert('❌ Failed to submit report: " . htmlspecialchars($stmt->error) . "'); window.history.back();</script>";
}

$con->close();
?>
