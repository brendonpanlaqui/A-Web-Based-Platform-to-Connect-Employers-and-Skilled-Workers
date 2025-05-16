<?php
// controllers/ProjectController.php
include '../config/database.php';
session_start();

// Check for authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$employer_id = $_SESSION['user_id'];

$sql = "SELECT * FROM jobs WHERE employer_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $employer_id);
$stmt->execute();
$result = $stmt->get_result();

$projects = [];

while ($row = $result->fetch_assoc()) {
    $projects[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'category' => $row['category'],
        'type' => $row['type'],
        'time' => $row['time_estimate'],
        'expertise' => $row['expertise_level'],
        'datePosted' => $row['created_at'],
        'description' => $row['description'],
        'status' => $row['status'],
        'salary' => $row['salary'],
        'platform' => $row['platform'],
        'transactionMode' => $row['platform'],
        'location' => $row['location'],
    ];
}

$stmt->close();
$con->close();

echo json_encode($projects);
        