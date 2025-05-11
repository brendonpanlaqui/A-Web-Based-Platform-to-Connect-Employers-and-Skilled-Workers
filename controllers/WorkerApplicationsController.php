<?php
session_start();
global $_SESSION;

// Check if the user is logged in and has the 'worker' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'job_portal');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$workerId = $_SESSION['user_id'];

// Fetch applications by current worker
$stmt = $conn->prepare("SELECT j.title AS job_title, j.category, a.date_applied, a.status
                       FROM job_applications a
                       JOIN jobs j ON a.job_id = j.id
                       WHERE a.worker_id = ?
                       ORDER BY a.date_applied DESC");

$stmt->bind_param('i', $workerId);
$stmt->execute();
$result = $stmt->get_result();

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = [
        'job_title' => $row['job_title'],
        'category' => $row['category'],
        'date_applied' => $row['date_applied'],
        'status' => $row['status'],
    ];
}

header('Content-Type: application/json');
echo json_encode($applications);
?>
