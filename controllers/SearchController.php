<?php
// search_users.php

header('Content-Type: application/json');

// Include the database connection file
require_once __DIR__ . '/../config/database.php'; // Ensure this path is correct

// Check if the database connection is successful
if (!$con) {
    echo json_encode(["error" => "Failed to connect to the database: " . mysqli_connect_error()]);
    exit();
}

// Get the query from POST
$query = $_POST['query'] ?? '';
$searchTerm = "%$query%";

// Prepare the SQL statement
$sql = "SELECT id, first_name, last_name, email FROM users WHERE id = ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ?";
$stmt = mysqli_prepare($con, $sql);

// Check if statement preparation failed
if (!$stmt) {
    echo json_encode(["error" => "Query preparation failed: " . mysqli_error($con)]);
    exit();
}

// Bind parameters (3 strings)
mysqli_stmt_bind_param($stmt, "isss", $id, $searchTerm, $searchTerm, $searchTerm);

// Execute the query
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(["error" => "SQL execution failed: " . mysqli_error($con)]);
    exit();
}

// Get the result
$result = mysqli_stmt_get_result($stmt);

// Fetch the results into an array
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Return the JSON response
echo json_encode($users);

// Close the statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
