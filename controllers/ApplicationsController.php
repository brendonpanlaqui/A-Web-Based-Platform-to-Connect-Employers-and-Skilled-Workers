<?php
// controllers/ApplicationsController.php
include '../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$employee_id = $_SESSION['user_id'];

// Assuming `applications` table has employee_id and job_id, and a `status` + `date_applied` column
$sql = "
    SELECT j.category, j.type, a.status, a.date_applied
    FROM job_applications a
    JOIN jobs j ON a.job_id = j.id
    WHERE a.worker_id = ?
    ORDER BY a.date_applied DESC
";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$applications = [];

while ($row = $result->fetch_assoc()) {
    $applications[] = [
        'category' => $row['category'],
        'type' => $row['type'],
        'status' => $row['status'],
        'dateApplied' => $row['date_applied']
    ];
}

$stmt->close();
$con->close();

echo json_encode($applications);
