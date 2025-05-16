<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Fetch the complaints from the database
$stmt = $con->prepare("SELECT reports.*, users.first_name, users.last_name
    FROM reports
    LEFT JOIN users ON reports.reporter_id = users.id");
$stmt->execute();
$result = $stmt->get_result();

$complaints = [];
while ($row = $result->fetch_assoc()) {
    // Convert NULL values to PHP's null
    foreach ($row as $key => $value) {
        if ($value === null) {
            $row[$key] = null; // Explicitly set NULL values to null
        }
    }
    $complaints[] = $row; // Add the report to the complaints array
}

echo json_encode($complaints);

$con->close();
?>
