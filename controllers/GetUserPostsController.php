<?php
require '../config/database.php';

header('Content-Type: application/json');

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    echo json_encode(['error' => 'Invalid user ID.']);
    exit;
}

$user_id = intval($_GET['user_id']);

// Get user info
$user_stmt = $con->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    echo json_encode(['error' => 'User not found.']);
    exit;
}

$user = $user_result->fetch_assoc();
$user_stmt->close();

// Get posts
$post_stmt = $con->prepare("SELECT id, title, category, type, platform, location, time_estimate, expertise_level, salary, description, created_at, updated_at FROM jobs WHERE employer_id = ?");
$post_stmt->bind_param("i", $user_id);
$post_stmt->execute();
$post_result = $post_stmt->get_result();

$posts = [];
while ($row = $post_result->fetch_assoc()) {
    $posts[] = $row;
}

$post_stmt->close();
$con->close();

echo json_encode(['user' => $user, 'posts' => $posts]);
