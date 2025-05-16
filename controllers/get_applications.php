<?php
session_start();
require_once '../config/database.php'; // adjust path as needed

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$status = $_GET['status'] ?? 'pending';
$validStatuses = ['pending', 'rejected', 'accepted'];
if (!in_array($status, $validStatuses)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid status']);
    exit();
}

$userId = $_SESSION['user_id'];

$sql = "
    SELECT 
        j.title AS job_title,
        CONCAT(u.first_name, ' ', u.last_name) AS employer_name,
        j.category,
        j.salary,
        j.status AS job_status,
        a.date_applied,
        a.status AS application_status,
        a.cover_letter
    FROM job_applications a
    JOIN jobs j ON a.job_id = j.id
    JOIN users u ON j.employer_id = u.id
    WHERE a.worker_id = ? AND a.status = ?
    ORDER BY a.date_applied DESC
";


$stmt = $con->prepare($sql);
$stmt->bind_param("is", $userId, $status);
$stmt->execute();
$result = $stmt->get_result();

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

echo json_encode($applications);
?>
