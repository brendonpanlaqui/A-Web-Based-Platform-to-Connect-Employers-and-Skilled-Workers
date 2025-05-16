<?php
require_once __DIR__ . '/../config/database.php';  
session_start();

header('Content-Type: application/json');

// Check if user is logged in and has the 'worker' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    echo json_encode(['error' => 'Unauthorized access.']);
    http_response_code(401);
    exit();
}

$workerId = $_SESSION['user_id'];
$now = date("Y-m-d H:i:s");

// ✅ Handle job application submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobId = $_POST['job_id'] ?? null;
    $coverLetter = $_POST['cover_letter'] ?? '';

    if (!$jobId || !$coverLetter) {
        echo json_encode(['success' => false, 'error' => 'Job ID and cover letter are required.']);
        exit();
    }

    // Check if already applied
    $checkQuery = "SELECT id FROM job_applications WHERE worker_id = ? AND job_id = ?";
    $stmt = mysqli_prepare($con, $checkQuery);
    mysqli_stmt_bind_param($stmt, 'ii', $workerId, $jobId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo json_encode(['success' => false, 'error' => 'You have already applied for this job.']);
        exit();
    }

    // Insert application
    $insertQuery = "INSERT INTO job_applications (worker_id, job_id, cover_letter, status, date_applied) VALUES (?, ?, ?, 'pending', ?)";
    $stmt = mysqli_prepare($con, $insertQuery);
    mysqli_stmt_bind_param($stmt, 'iiss', $workerId, $jobId, $coverLetter, $now);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to submit application.']);
    }

    exit();
}

// ✅ Check if penalized
$penaltyCheck = "SELECT penalized_until FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $penaltyCheck);
mysqli_stmt_bind_param($stmt, 'i', $workerId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_assoc($result);

if ($userData && $userData['penalized_until'] && $userData['penalized_until'] > $now) {
    echo json_encode(['penalized' => true, 'until' => $userData['penalized_until']]);
    exit();
}

// ✅ Handle job listing fetch
$search = $_GET['search'] ?? '';
$type = $_GET['type'] ?? '';
$category = $_GET['category'] ?? '';

$query = "
  SELECT 
    jobs.id, 
    jobs.title, 
    jobs.type, 
    jobs.category,
    CONCAT(users.first_name, ' ', users.last_name) AS employer_name
  FROM jobs
  JOIN users ON jobs.employer_id = users.id
  WHERE jobs.status = 'open' 
    AND jobs.employer_id != ? 
    AND jobs.id NOT IN (
        SELECT job_id FROM job_applications WHERE worker_id = ?
    )
";

$params = [$workerId, $workerId];
$types = 'ii';

if ($search !== '') {
    $query .= " AND jobs.title LIKE ?";
    $params[] = '%' . $search . '%';
    $types .= 's';
}

if ($type !== '') {
    $query .= " AND jobs.type = ?";
    $params[] = $type;
    $types .= 's';
}

if ($category !== '') {
    $query .= " AND jobs.category = ?";
    $params[] = $category;
    $types .= 's';
}

$query .= " ORDER BY jobs.created_at DESC";
$stmt = mysqli_prepare($con, $query);
if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare statement.']);
    exit();
}

mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$jobs = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode(['jobs' => $jobs]);
exit();
