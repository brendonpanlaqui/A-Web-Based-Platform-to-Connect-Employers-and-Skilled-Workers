<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

$query = $_POST['query'] ?? '';
$searchTerm = "%$query%";

$sql = "SELECT id, employer_id, title, category, type, platform, location, time_estimate, expertise_level, salary, description, created_at, updated_at FROM jobs
        WHERE id = ? OR employer_id = ? OR title LIKE ? OR category LIKE ? OR type LIKE ? OR platform LIKE ? OR location LIKE ? OR time_estimate LIKE ? OR expertise_level LIKE ? OR salary LIKE ? OR description LIKE ? OR created_at LIKE ? OR updated_at LIKE ?";
$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    echo json_encode(["error" => "Query preparation failed: " . mysqli_error($con)]);
    exit();
}

mysqli_stmt_bind_param($stmt, "iisssssssssss", 
    $query, $query, 
    $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, 
    $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm
);


if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(["error" => "SQL execution failed: " . mysqli_error($con)]);
    exit();
}

$result = mysqli_stmt_get_result($stmt);
$posts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

echo json_encode($posts);

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
