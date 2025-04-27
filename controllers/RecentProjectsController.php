<?php
// Connect to database
$conn = new mysqli('localhost', 'root', '', 'job_portal');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Fetch projects posted this month
$stmt = $conn->prepare("SELECT category, type, created_at, status FROM jobs WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?");
$stmt->bind_param('ii', $currentMonth, $currentYear);
$stmt->execute();
$result = $stmt->get_result();

// Initialize the projects array
$projects = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Format the date (optional)
        $formattedDate = date('Y-m-d', strtotime($row['created_at']));

        $projects[] = [
            'category' => $row['category'],
            'type' => $row['type'],
            'datePosted' => $formattedDate, // Use the formatted date
            'status' => $row['status'],
        ];
    }
} else {
    // If no results are found, return an empty array
    $projects = [];
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($projects);

// Close the database connection
$conn->close();
?>
