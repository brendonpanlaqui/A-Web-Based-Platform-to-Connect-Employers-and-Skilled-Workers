<?php
require_once __DIR__ . '/../config/database.php';  

// Check if user is logged in and has the 'worker' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../views/login.php");
    exit();
}

$workerId = $_SESSION['user_id'];
$message = "";

// 🛑 Check if the worker is penalized
$penaltyCheck = "SELECT penalized_until FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $penaltyCheck);
mysqli_stmt_bind_param($stmt, 'i', $workerId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_assoc($result);

$now = date("Y-m-d H:i:s");
$isPenalized = false;

if ($userData && $userData['penalized_until'] && $userData['penalized_until'] > $now) {
    $isPenalized = true;
    $penaltyEnd = date("F j, Y, g:i a", strtotime($userData['penalized_until']));
    $message = "You are penalized and cannot apply for jobs until <strong>$penaltyEnd</strong>.";
}

// Handle job application (block if penalized)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    if ($isPenalized) {
        $_SESSION['error'] = $message;
    } else {
        $jobId = $_POST['job_id'];

        // Check if already applied
        $checkQuery = "SELECT * FROM job_applications WHERE job_id = ? AND worker_id = ?";
        $stmt = mysqli_prepare($con, $checkQuery);
        mysqli_stmt_bind_param($stmt, 'ii', $jobId, $workerId);
        mysqli_stmt_execute($stmt);
        $checkResult = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($checkResult) == 0) {
            // Apply
            $applyQuery = "INSERT INTO job_applications (job_id, worker_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($con, $applyQuery);
            mysqli_stmt_bind_param($stmt, 'ii', $jobId, $workerId);
            mysqli_stmt_execute($stmt);
            $_SESSION['success'] = "Successfully applied for the job.";
        } else {
            $_SESSION['error'] = "You have already applied for this job.";
        }
    }

    header("Location: worker-dashboard.php");
    exit();
}

// Fetch job list
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'all';
$query = "SELECT * FROM jobs WHERE status = 'open'";

if ($search) {
    $query .= " AND title LIKE '%" . mysqli_real_escape_string($con, $search) . "%'";
}
if ($category != 'all') {
    $query .= " AND category = '" . mysqli_real_escape_string($con, $category) . "'";
}
$jobs = mysqli_fetch_all(mysqli_query($con, $query), MYSQLI_ASSOC);
?>