<?php
require_once __DIR__ . '/../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../views/login.php");
    exit();
}

$jobId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$jobId) {
    header("Location: ../views/worker-dashboard.php");
    exit();
}

$message = null;

// Fetch job details
$query = "SELECT * FROM jobs WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $jobId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$job = mysqli_fetch_assoc($result);

if (!$job) {
    header("Location: ../views/worker-dashboard.php");
    exit();
}

// Handle apply logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    $workerId = $_SESSION['user_id'];

    $checkQuery = "SELECT * FROM job_applications WHERE job_id = ? AND worker_id = ?";
    $stmt = mysqli_prepare($con, $checkQuery);
    mysqli_stmt_bind_param($stmt, 'ii', $jobId, $workerId);
    mysqli_stmt_execute($stmt);
    $checkResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($checkResult) == 0) {
        $applyQuery = "INSERT INTO job_applications (job_id, worker_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($con, $applyQuery);
        mysqli_stmt_bind_param($stmt, 'ii', $jobId, $workerId);
        mysqli_stmt_execute($stmt);
        header("Location: ../views/job-details.php?id=$jobId");
        exit();
    } else {
        $message = "You have already applied for this job.";
    }
}

// Check if already applied for button display
$alreadyApplied = false;
$checkApplyQuery = "SELECT * FROM job_applications WHERE job_id = ? AND worker_id = ?";
$stmt = mysqli_prepare($con, $checkApplyQuery);
mysqli_stmt_bind_param($stmt, 'ii', $jobId, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$resultCheck = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($resultCheck) > 0) {
    $alreadyApplied = true;
}
