<?php
// Start the session at the very top if not already done
session_start();

// Make the $_SESSION superglobal accessible
global $_SESSION; // Declare it as global

// Check if the user is logged in and has the 'worker' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    http_response_code(403);  // Forbidden
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'job_portal');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch applications by current worker
$workerId = $_SESSION['user_id'];  // Access user_id from the session

// Query to fetch job applications for the current worker
$stmt = $conn->prepare("SELECT j.title AS job_title, j.category, a.date_applied, a.status
                       FROM job_applications a
                       JOIN jobs j ON a.job_id = j.id
                       WHERE a.worker_id = ?
                       ORDER BY a.date_applied DESC");

$stmt->bind_param('i', $workerId);
$stmt->execute();
$result = $stmt->get_result();

// Prepare the applications array
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
