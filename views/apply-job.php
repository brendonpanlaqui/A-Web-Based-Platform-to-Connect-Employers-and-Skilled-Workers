<?php include '../includes/nav.php'; ?>

<?php
require_once '../config/database.php';

// Check if user is logged in and has the 'worker' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    header("Location: ../views/login.php");  // Redirect to login if not authorized
    exit();
}

$jobId = $_GET['job_id'] ?? null;
if (!$jobId) {
    header("Location: ../views/worker-dashboard.php");  // Redirect if no job ID is provided
    exit();
}

// Fetch job details to display on the page
$query = "SELECT * FROM jobs WHERE id = ? AND status = 'open'";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $jobId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$job = mysqli_fetch_assoc($result);

if (!$job) {
    header("Location: ../views/worker-dashboard.php");  // Redirect if job doesn't exist or isn't open
    exit();
}

// Handle the application process
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $workerId = $_SESSION['user_id'];

    // Check if the worker has already applied for this job
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
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "You have successfully applied for the job!";
            header("Location: ../views/worker-dashboard.php");  // Redirect after successful application
            exit();
        } else {
            $message = "Error applying for the job. Please try again.";
        }
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
    <title>Apply for Job</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Your main CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <h2 class="mb-4">Apply for Job</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location'] ?? 'N/A'); ?></p>
                <p><strong>Salary:</strong> ₱<?php echo number_format($job['salary']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($job['type']); ?></p>
                <p><strong>Platform:</strong> <?php echo htmlspecialchars($job['platform'] ?? 'N/A'); ?></p>
                <p><strong>Time Estimate:</strong> <?php echo htmlspecialchars($job['time_estimate']); ?></p>
                <p><strong>Expertise Level:</strong> <?php echo htmlspecialchars($job['expertise_level']); ?></p>
                <p><strong>Description:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            </div>
        </div>

        <form action="apply-job.php?job_id=<?php echo $jobId; ?>" method="POST" class="mt-4">
            <button type="submit" class="btn btn-primary">Apply for this Job</button>
        </form>

        <a href="../views/worker-dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>

    <script src="../assets/js/worker.js"></script>
</body>
</html>
