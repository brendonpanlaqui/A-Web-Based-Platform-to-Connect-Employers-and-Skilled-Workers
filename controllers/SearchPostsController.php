<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

$query = $_POST['query'] ?? '';
$searchTerm = "%$query%";

$sql = "
SELECT 
    jobs.id, 
    jobs.employer_id, 
    users.first_name, 
    users.last_name,
    jobs.title, 
    jobs.category, 
    jobs.type, 
    jobs.platform, 
    jobs.location, 
    jobs.time_estimate, 
    jobs.expertise_level, 
    jobs.salary, 
    jobs.description, 
    jobs.created_at, 
    jobs.updated_at
FROM jobs
JOIN users ON jobs.employer_id = users.id
WHERE 
    jobs.id = ? OR
    jobs.employer_id = ? OR
    jobs.title LIKE ? OR
    jobs.category LIKE ? OR
    jobs.type LIKE ? OR
    jobs.platform LIKE ? OR
    jobs.location LIKE ? OR
    jobs.time_estimate LIKE ? OR
    jobs.expertise_level LIKE ? OR
    jobs.salary LIKE ? OR
    jobs.description LIKE ? OR
    jobs.created_at LIKE ? OR
    jobs.updated_at LIKE ? OR
    users.first_name LIKE ? OR
    users.last_name LIKE ?
";
$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    echo json_encode(["error" => "Query preparation failed: " . mysqli_error($con)]);
    exit();
}

mysqli_stmt_bind_param(
    $stmt,
    "iisssssssssssss",
    $query, $query, 
    $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm,
    $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm,
    $searchTerm, $searchTerm, $searchTerm
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
