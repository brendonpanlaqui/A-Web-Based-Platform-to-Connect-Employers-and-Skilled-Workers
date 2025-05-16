<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

$query = $_POST['query'] ?? '';
$searchTerm = "%$query%";

$sql = "SELECT 
            ja.id, 
            ja.worker_id, 
            ja.job_id, 
            ja.cover_letter, 
            ja.status, 
            ja.date_applied,
            u.first_name, 
            u.last_name
        FROM job_applications ja
        JOIN users u ON ja.worker_id = u.id
        WHERE ja.id = ? 
           OR ja.worker_id = ? 
           OR ja.job_id = ? 
           OR ja.cover_letter LIKE ? 
           OR ja.status LIKE ? 
           OR ja.date_applied LIKE ? 
           OR u.first_name LIKE ? 
           OR u.last_name LIKE ?";

$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    echo json_encode(["error" => "Query preparation failed: " . mysqli_error($con)]);
    exit();
}

mysqli_stmt_bind_param($stmt, "iiisssss", 
    $query, $query, $query, 
    $searchTerm, $searchTerm, $searchTerm, 
    $searchTerm, $searchTerm
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
