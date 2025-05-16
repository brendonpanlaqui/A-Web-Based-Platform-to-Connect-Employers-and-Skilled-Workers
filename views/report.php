<?php
include('../includes/nav.php');

$type = $_GET['type'] ?? null;
$id = $_GET['id'] ?? null;

$validTypes = ['user', 'job'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container text-dark pt-5 pb-3 mt-4 mt-md-5">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h2 class="mb-4">Report <?= htmlspecialchars(ucfirst($type)) ?></h2>
        <a href="javascript:history.back()" class="btn btn-secondary mb-3">Back</a>
    </div>

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
