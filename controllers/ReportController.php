<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$reporter_id = $_SESSION['user_id'] ?? null;

if (!$reporter_id) {
    echo "You must be logged in to submit a report.";
    exit;
}

$reported_type = $_POST['reported_type'] ?? '';
$reported_id = $_POST['reported_id'] ?? '';
$reason = $_POST['reason'] ?? '';


if (!$reported_type || !$reported_id || !$reason) {
    echo "Missing required data.";
    exit;
}

$validTypes = ['user', 'job', 'message'];
if (!in_array($reported_type, $validTypes)) {
    echo "Invalid report type.";
    exit;
}

$stmt = $con->prepare("INSERT INTO reports (reporter_id, reported_type, reported_id, reason) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isis", $reporter_id, $reported_type, $reported_id, $reason);

if ($stmt->execute()) {
    echo "<div class='alert alert-success'>✅ Report submitted successfully.</div>";
} else {
    echo "<div class='alert alert-danger'>❌ Failed to submit report: " . htmlspecialchars($stmt->error) . "</div>";
}

$con->close();
?>
