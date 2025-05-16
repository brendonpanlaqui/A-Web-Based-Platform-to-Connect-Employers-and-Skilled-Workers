<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

session_start();
/*if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([]);
    exit;
}*/

$sql = "
SELECT 
    r.id,
    r.reported_id, -- <-- This is the key addition
    reporter.first_name AS reporter_first,
    reporter.last_name AS reporter_last,
    reported.first_name AS reported_first,
    reported.last_name AS reported_last,
    r.reason,
    r.status,
    r.created_at

FROM reports r
JOIN users reporter ON r.reporter_id = reporter.id
JOIN users reported ON r.reported_type = 'user' AND r.reported_id = reported.id
WHERE r.reported_type = 'user'
ORDER BY r.created_at DESC
";

$result = $con->query($sql);

$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

echo json_encode($reports);

$con->close();
