<?php include '../includes/nav.php'; ?>

<?php
// views/job-details.php

require_once __DIR__ . '/../config/database.php';  

// Check if user is logged in and has the 'worker' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../views/login.php");  // Redirect to login if not authorized
    exit();
}

// Get job ID from URL
$jobId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$jobId) {
    header("Location: ../views/worker-dashboard.php");  // Redirect if no job ID is provided
    exit();
}

// Fetch job details from the database
$query = "SELECT * FROM jobs WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $jobId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$job = mysqli_fetch_assoc($result);

if (!$job) {
    header("Location: ../views/worker-dashboard.php");  // Redirect if job not found
    exit();
}

// Handle job application
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply'])) {
    $workerId = $_SESSION['user_id'];

    // Check if the worker already applied for the job
    $checkQuery = "SELECT * FROM job_applications WHERE job_id = ? AND worker_id = ?";
    $stmt = mysqli_prepare($con, $checkQuery);
    mysqli_stmt_bind_param($stmt, 'ii', $jobId, $workerId);
    mysqli_stmt_execute($stmt);
    $checkResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($checkResult) == 0) {
        // Apply for the job if not already applied
        $applyQuery = "INSERT INTO job_applications (job_id, worker_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($con, $applyQuery);
        mysqli_stmt_bind_param($stmt, 'ii', $jobId, $workerId);
        mysqli_stmt_execute($stmt);
        header("Location: job-details.php?id=" . $jobId);  // Redirect to avoid resubmission
        exit();
    } else {
        $message = "You have already applied for this job.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Job Details</h2>
        
        <!-- Display success or error message -->
        <?php if (isset($message)): ?>
            <div class="alert alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h4><?php echo htmlspecialchars($job['title']); ?></h4>
            </div>
            <div class="card-body">
                <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location'] ?? 'N/A'); ?></p>
                <p><strong>Salary:</strong> ₱<?php echo number_format($job['salary']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($job['type']); ?></p>
                <p><strong>Platform:</strong> <?php echo htmlspecialchars($job['platform'] ?? 'N/A'); ?></p>
                <p><strong>Time Estimate:</strong> <?php echo htmlspecialchars($job['time_estimate'] ?? 'N/A'); ?></p>
                <p><strong>Expertise Level:</strong> <?php echo htmlspecialchars($job['expertise_level'] ?? 'N/A'); ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            </div>
            <div class="card-footer">
                <form action="job-details.php?id=<?php echo $job['id']; ?>" method="POST">
                    <?php
                    // Check if worker has already applied for this job
                    $checkApplyQuery = "SELECT * FROM job_applications WHERE job_id = ? AND worker_id = ?";
                    $stmt = mysqli_prepare($con, $checkApplyQuery);
                    mysqli_stmt_bind_param($stmt, 'ii', $job['id'], $_SESSION['user_id']);
                    mysqli_stmt_execute($stmt);
                    $resultCheck = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($resultCheck) == 0): ?>
                        <button type="submit" name="apply" class="btn btn-primary">Apply for this Job</button>
                    <?php else: ?>
                        <span class="badge bg-secondary">You have already applied for this job</span>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <a href="worker-dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
