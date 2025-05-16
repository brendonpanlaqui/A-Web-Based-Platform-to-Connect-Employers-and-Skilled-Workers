<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

session_start();
// (Optional) ensure only admins can access
/*if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([]);
    exit;
}*/

$userId = $_GET['user_id'] ?? null;
if (!$userId) {
    echo json_encode([]);
    exit;
}

$sql = "
SELECT 
    r.id,
    u.first_name   AS reporter_first,
    u.last_name    AS reporter_last,
    r.reason,
    r.status,
    r.created_at
FROM reports r
JOIN users u ON r.reporter_id = u.id
WHERE r.reported_type = 'user'
  AND r.reported_id   = ?
ORDER BY r.created_at DESC
";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$reports = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reports[] = $row;
}

echo json_encode($reports);

mysqli_stmt_close($stmt);
mysqli_close($con);
