<?php include('../includes/nav.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applicants</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4">All Employees</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-secondary">
                    <tr>
                        <th>Worker Name</th>
                        <th>Job Title</th>
                        <th>Date Applied</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="applicationsTableBody">
                    <tr><td colspan="5">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../assets/js/employee.js"></script>
</body>
</html>
