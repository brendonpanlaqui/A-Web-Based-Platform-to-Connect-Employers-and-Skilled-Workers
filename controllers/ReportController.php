<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Ensure the user is logged in
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
    echo json_encode(["success" => "✅ Report submitted successfully."]);
echo "<div class='alert alert-success'>✅ Report submitted successfully.</div>";
} else {
    echo json_encode(["error" => "❌ Failed to submit report: " . htmlspecialchars($stmt->error)]);
echo "<div class='alert alert-danger'>❌ Failed to submit report: " . htmlspecialchars($stmt->error) . "</div>";
}

$con->close();
?>
