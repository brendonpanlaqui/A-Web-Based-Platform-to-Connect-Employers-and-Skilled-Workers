<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

$query = $_POST['query'] ?? '';
$searchTerm = "%$query%";

$sql = "SELECT id, worker_id, job_id, cover_letter, status, date_applied FROM job_applications
        WHERE id = ? OR worker_id = ? OR job_id = ? OR cover_letter LIKE ? OR status LIKE ? OR date_applied LIKE ?";

$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    echo json_encode(["error" => "Query preparation failed: " . mysqli_error($con)]);
    exit();
}

mysqli_stmt_bind_param($stmt, "iiisss", 
    $query, $query, $query, 
    $searchTerm, $searchTerm, $searchTerm, 
);

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(["error" => "SQL execution failed: " . mysqli_error($con)]);
    exit();
}

$result = mysqli_stmt_get_result($stmt);
$applications = [];

while ($row = mysqli_fetch_assoc($result)) {
    $applications[] = $row;
}

echo json_encode($applications);

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
