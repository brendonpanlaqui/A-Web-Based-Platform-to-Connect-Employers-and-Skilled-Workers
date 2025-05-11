<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Ensure the user is logged in
$reporter_id = $_SESSION['user_id'] ?? null;
if (!$reporter_id) {
echo "You must be logged in to submit a report.";
exit;
}

$reported_type = $_POST['reported_type'] ?? '';
$reported_id = $_POST['reported_id'] ?? '';
$reason = $_POST['reason'] ?? '';

// Validate required fields
if (!$reported_type || !$reported_id || !$reason) {
echo "Missing required data.";
exit;
}

$validTypes = ['user', 'job', 'message'];
if (!in_array($reported_type, $validTypes)) {
echo "Invalid report type.";
exit;
}

// Check if the user has reported the same user within the last 7 days
$sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
$stmt = $con->prepare("SELECT * FROM reports WHERE reporter_id = ? AND reported_type = ? AND reported_id = ? AND created_at >= ?");
$stmt->bind_param("isis", $reporter_id, $reported_type, $reported_id, $sevenDaysAgo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
echo "You have already reported this user within the last 7 days.";
exit;
}

// Insert the new report
$stmt = $con->prepare("INSERT INTO reports (reporter_id, reported_type, reported_id, reason) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isis", $reporter_id, $reported_type, $reported_id, $reason);

if ($stmt->execute()) {
echo "<div class='alert alert-success'>✅ Report submitted successfully.</div>";
} else {
echo "<div class='alert alert-danger'>❌ Failed to submit report: " . htmlspecialchars($stmt->error) . "</div>";
}

$con->close();
?>
