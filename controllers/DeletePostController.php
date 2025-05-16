<?php
include '../config/database.php';
session_start();

header('Content-Type: application/json');

$employerId = $_SESSION['user_id'] ?? null;

if (!$employerId) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get raw input and decode JSON
$data = json_decode(file_get_contents("php://input"), true);
$projectId = $data['project_id'] ?? null;

if (!$projectId) {
    echo json_encode(['error' => 'Project ID missing']);
    exit;
}

// Ensure the project belongs to the employer
$sql = "DELETE FROM jobs WHERE id = ? AND employer_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $projectId, $employerId);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => 'Project deleted']);
} else {
    echo json_encode(['error' => 'Failed to delete project or not authorized']);
}

$stmt->close();
$con->close();
