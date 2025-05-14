<?php include '../includes/nav.php'; ?>

<?php require_once __DIR__ . '/../controllers/WorkerController.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worker Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- your main CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if ($isPenalized): ?>
            <div class="alert alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>
        <!-- Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="card mb-4 mt-5">
            <div class="card-body">
                <h4>Welcome, <?php echo $_SESSION['first_name']; ?>!</h4>
                <p>You are logged in as a <strong>worker</strong>.</p>
            </div>
        </div>

        <div class="mt-4">
            <h2 class="mb-4">Available Jobs</h2>

            <!-- Search and Category Filter -->
            <form method="GET" action="worker-dashboard.php" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by title..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="all">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php echo ($category == $cat) ? 'selected' : ''; ?>><?php echo ucfirst($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="worker-dashboard.php" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>

            <table class="table table-striped">
                <thead>
                    <tr>
                    <th>Title</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Salary</th>
                        <th>Type</th>
                        <th>Platform</th>
                        <th>Time Estimate</th>
                        <th>Expertise Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($jobs): ?>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><a href="job-details.php?id=<?php echo $job['id']; ?>"><?php echo htmlspecialchars($job['title']); ?></a></td>
                                <td><?php echo htmlspecialchars($job['category']); ?></td>
                                <td><?php echo htmlspecialchars($job['location'] ?? 'N/A'); ?></td>
                                <td>₱<?php echo number_format($job['salary']); ?></td>
                                <td><?php echo htmlspecialchars($job['type']); ?></td>
                                <td><?php echo htmlspecialchars($job['platform'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($job['time_estimate'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php echo ($job['type'] == 'online' || $job['type'] == 'offline') ? 'N/A' : htmlspecialchars($job['expertise_level']); ?>
                                </td>
                                <td>
                                    <?php
                                $checkApplyQuery = "SELECT * FROM job_applications WHERE job_id = ? AND worker_id = ?";
                                $stmt = mysqli_prepare($con, $checkApplyQuery);
                                mysqli_stmt_bind_param($stmt, 'ii', $job['id'], $workerId);
                                mysqli_stmt_execute($stmt);
                                $resultCheck = mysqli_stmt_get_result($stmt);
                                $application = mysqli_fetch_assoc($resultCheck);

                                // Check if any worker is already accepted for this job
                                $acceptedQuery = "SELECT * FROM job_applications WHERE job_id = ? AND status = 'accepted'";
                                $stmtAccepted = mysqli_prepare($con, $acceptedQuery);
                                mysqli_stmt_bind_param($stmtAccepted, 'i', $job['id']);
                                mysqli_stmt_execute($stmtAccepted);
                                $resultAccepted = mysqli_stmt_get_result($stmtAccepted);
                                $isAccepted = mysqli_num_rows($resultAccepted) > 0;

                                if (!$application || ($application['status'] === 'rejected' && !$isAccepted)):
                                    if ($isPenalized): ?>
                                        <button class="btn btn-secondary btn-sm" disabled>Penalized</button>
                                    <?php else: ?>
                                        <a href="job-details.php?id=<?php echo $job['id']; ?>" class="btn btn-success btn-sm">Apply</a>
                                    <?php endif;
                                else: ?>
                                    <span class="badge bg-secondary">Already Applied</span>
                                <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No jobs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            <h2>Your Applied Jobs</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch applied jobs
                    $workerId = $_SESSION['user_id'];
                    $appliedQuery = "SELECT j.title, ja.status, j.id FROM job_applications ja INNER JOIN jobs j ON ja.job_id = j.id WHERE ja.worker_id = ?";
                    $stmt = mysqli_prepare($con, $appliedQuery);
                    mysqli_stmt_bind_param($stmt, 'i', $workerId);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td>
                                    <a href="job-details.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View Details</a>
                                </td>
                            </tr>
                            <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">You haven't applied for any jobs yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- <div class="mt-5">
            <h2>Recommended Jobs</h2>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($job['category']); ?></p>
                            <a href="job-details.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary">Apply Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
    
    <script src="../assets/js/worker.js"></script> 
</body>
</html>
