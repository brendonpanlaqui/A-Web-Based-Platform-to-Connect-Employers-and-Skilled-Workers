<?php
session_start();
require_once '../config/database.php';

$workerId = $_SESSION['user_id'] ?? null;

if (!$workerId || $_SESSION['role'] !== 'worker') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Fetch employers where the worker has accepted applications
$sql = "
    SELECT 
        j.title AS job_title,
        j.status AS job_status,
        a.date_applied,
        a.worker_id,
        u.id AS employer_id,
        CONCAT(u.first_name, ' ', u.last_name) AS employer_name
    FROM job_applications a
    JOIN jobs j ON a.job_id = j.id
    JOIN users u ON j.employer_id = u.id
    WHERE a.worker_id = ? AND a.status = 'accepted'
    ORDER BY a.date_applied DESC
";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $workerId);
$stmt->execute();
$result = $stmt->get_result();

$employers = [];
while ($row = $result->fetch_assoc()) {
    $employers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($employers);
$con->close();
