<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$reporter_id = $_SESSION['user_id'] ?? null;

if (!$reporter_id) {
    echo json_encode(["error" => "You must be logged in to submit a report."]);
    exit;
}

$reported_type = $_POST['reported_type'] ?? '';
$reported_id = $_POST['reported_id'] ?? '';
$reason = $_POST['reason'] ?? '';

if (!$reported_type || !$reported_id || !$reason) {
    echo json_encode(["error" => "Missing required data."]);
    exit;
}

$validTypes = ['user', 'job'];
if (!in_array($reported_type, $validTypes)) {
    echo json_encode(["error" => "Invalid report type."]);
    exit;
}

$stmt = $con->prepare("INSERT INTO reports (reporter_id, reported_type, reported_id, reason) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isis", $reporter_id, $reported_type, $reported_id, $reason);

if ($stmt->execute()) {
    echo json_encode(["success" => "✅ Report submitted successfully."]);
} else {
    echo json_encode(["error" => "❌ Failed to submit report: " . htmlspecialchars($stmt->error)]);
}

$con->close();
?>
