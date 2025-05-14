<?php
require_once __DIR__ . '/../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../views/login.php");
    exit();
}

$jobId = $_GET['id'] ?? null;
if (!$jobId) {
    header("Location: ../views/worker-dashboard.php");
    exit();
}

$message = null;
$alreadyApplied = false;
$applicationStatus = null;
$someoneAccepted = false;
$workerId = $_SESSION['user_id'];

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

// Check if the current worker already applied and get the status
$checkApplyQuery = "SELECT status FROM job_applications WHERE job_id = ? AND worker_id = ?";
$stmt = mysqli_prepare($con, $checkApplyQuery);
mysqli_stmt_bind_param($stmt, 'ii', $jobId, $workerId);
mysqli_stmt_execute($stmt);
$resultCheck = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($resultCheck)) {
    $alreadyApplied = true;
    $applicationStatus = $row['status'];
}

// Check if someone else has been accepted for this job
$checkAcceptedQuery = "SELECT id FROM job_applications WHERE job_id = ? AND status = 'accepted'";
$stmt = mysqli_prepare($con, $checkAcceptedQuery);
mysqli_stmt_bind_param($stmt, 'i', $jobId);
mysqli_stmt_execute($stmt);
$acceptedResult = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($acceptedResult) > 0) {
    $someoneAccepted = true;
}

// Handle apply logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    if (!$alreadyApplied) {
        $applyQuery = "INSERT INTO job_applications (job_id, worker_id, status) VALUES (?, ?, 'pending')";
        $stmt = mysqli_prepare($con, $applyQuery);
        mysqli_stmt_bind_param($stmt, 'ii', $jobId, $workerId);
        mysqli_stmt_execute($stmt);
        header("Location: ../views/job-details.php?id=$jobId");
        exit();
    } elseif ($applicationStatus === 'rejected' && !$someoneAccepted) {
        // update existing rejected application to pending again
        $updateQuery = "UPDATE job_applications SET status = 'pending' WHERE job_id = ? AND worker_id = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'ii', $jobId, $workerId);
        mysqli_stmt_execute($stmt);
        header("Location: ../views/job-details.php?id=$jobId");
        exit();
    } else {
        $message = "You have already applied for this job.";
    }
}
