<?php
// Connect to database
include '../config/database.php';

session_start();

$query = "SELECT category, type, created_at, status FROM jobs";
$result = $con->query($query);

$projects = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formattedDate = date('Y-m-d', strtotime($row['created_at']));
        $projects[] = [
            'category' => $row['category'],
            'type' => $row['type'],
            'datePosted' => $formattedDate,
            'status' => $row['status'],
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($projects);

$con->close();
?>
