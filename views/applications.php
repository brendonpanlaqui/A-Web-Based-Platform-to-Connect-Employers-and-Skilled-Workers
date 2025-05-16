<?php
include('../includes/nav.php');

if ($_SESSION['role'] !== 'worker') {
    header("Location: ../views/login.php");
    exit();
}

$status = $_GET['status'] ?? 'pending';
$validStatuses = ['pending', 'accepted', 'rejected'];
if (!in_array($status, $validStatuses)) {
    header("Location: applications.php?status=pending");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applications - <?= ucfirst($status) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-white">

<header class="text-dark pt-5 pb-3 mt-4 mt-md-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h1 class="display-5 fw-bold">Applications · <?= ucfirst($status) ?></h1>
            <a href="javascript:history.back()" class="btn btn-secondary mb-3">Back</a>
        </div>
    </div>
</header>

<div class="container">
    <div id="cardContainer" class="row">
        <!-- Cards injected here -->
    </div>
</div>

<script>
    const applicationStatus = "<?= $status ?>";
</script>
<script src="../assets/js/applications.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
