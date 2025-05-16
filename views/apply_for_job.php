<?php include('../includes/nav.php'); ?>

<?php
if ($_SESSION['role'] !== 'worker') {
    header("Location: ../views/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for a Job</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-white">
    <header class="text-dark py-5 mt-4 mt-md-5">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <h1 class="display-5 fw-bold">Apply for a Job</h1>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-2">
                    <input type="text" id="searchInput" class="form-control border-dark" placeholder="Search job titles...">
                </div>
                <div class="col-md-3 mb-2">
                    <select id="filterType" class="form-select border-dark">
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="filterCategory" class="form-select border-dark">
                        <option value="">All Categories</option>
                        <!-- Categories will be dynamically inserted by JS -->
                    </select>
                </div>

            </div>
        </div>
    </header>

    <div class="container text-dark py-3">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-white border-dark border-1">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Type</th>
                        <th scope="col">Category</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="jobResultsTableBody">
                    <!-- Jobs will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Application Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyModalLabel">Submit Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="applicationForm">
                        <div class="mb-3">
                            <label for="coverLetter" class="form-label">Cover Letter</label>
                            <textarea class="form-control" id="coverLetter" rows="5" required></textarea>
                        </div>
                        <input type="hidden" id="selectedJobId">
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/apply-job.js"></script>
</body>
</html>
