<?php
// controllers/ProjectController.php
include '../config/database.php';

session_start();

// Fetch all projects from the database (or based on status if needed)
$projectStatus = isset($_GET['status']) ? $_GET['status'] : 'open'; // default status is 'open'

$sql = "SELECT * FROM jobs WHERE status = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $projectStatus);
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
        'date' => $row['created_at'], 
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

// Output the data as JSON for the frontend
echo json_encode($projects);
