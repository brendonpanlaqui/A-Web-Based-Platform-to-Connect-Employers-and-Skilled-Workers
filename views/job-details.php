<?php include '../includes/nav.php'; ?>
<?php require_once '../controllers/JobDetailsController.php'; ?>

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

        <?php if ($message): ?>
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
                    <?php if (!$alreadyApplied): ?>
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
