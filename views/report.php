<?php
session_start();
include('../includes/nav.php');

// Validate query parameters
$type = $_GET['type'] ?? null;
$id = $_GET['id'] ?? null;

$validTypes = ['user', 'job', 'message'];
if (!$type || !$id || !in_array($type, $validTypes)) {
    echo "<div class='alert alert-danger text-center mt-5'>Invalid report request.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report <?= htmlspecialchars(ucfirst($type)) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Report <?= htmlspecialchars(ucfirst($type)) ?></h2>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="alert alert-warning">
            You must be <a href="login.php">logged in</a> to submit a report.
        </div>
    <?php else: ?>
        <form action="../controllers/ReportController.php" method="POST">
            <input type="hidden" name="reported_type" value="<?= htmlspecialchars($type) ?>">
            <input type="hidden" name="reported_id" value="<?= htmlspecialchars($id) ?>">

            <div class="mb-3">
                <label for="reason" class="form-label">Reason for reporting:</label>
                <textarea name="reason" id="reason" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-danger">Submit Report</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
