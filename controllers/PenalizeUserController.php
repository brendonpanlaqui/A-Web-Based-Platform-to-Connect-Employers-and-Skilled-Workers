<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing user ID.']);
    exit;
}

$penalizedUntil = date('Y-m-d H:i:s', strtotime('+7 days'));

$stmt = $con->prepare("UPDATE users SET penalized_until = ? WHERE id = ?");
$stmt->bind_param("si", $penalizedUntil, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User penalized for 7 days.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error.']);
}
$stmt->close();
$con->close();
