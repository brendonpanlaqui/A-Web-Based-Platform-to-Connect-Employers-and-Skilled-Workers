<?php
header('Content-Type: application/json');
require_once '../config/database.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    echo json_encode(['success' => false, 'error' => 'No search query provided.']);
    exit;
}

// Use prepared statement to avoid SQL injection
$stmt = $con->prepare("SELECT id, title, category, created_at, type FROM jobs WHERE title LIKE ? OR category LIKE ?");
$searchTerm = "%$q%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $jobs = [];

    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }

    echo json_encode(['success' => true, 'jobs' => $jobs]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database query failed.']);
}

$stmt->close();
$con->close();
