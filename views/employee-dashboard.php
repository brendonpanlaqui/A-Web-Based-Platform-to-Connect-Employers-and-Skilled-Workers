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
        <title>SoftEng2</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body class="bg-white">
        <header class="text-dark py-5 mt-4 mt-md-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-3">
                        <h1 class="display-5 fw-bold">Dashboard &middot; Employee</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-3 mb-3">
                        <a href="applications.php?status=pending" class="text-decoration-none">
                            <div class="card bg-primary shadow-sm">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Pending Jobs:</h4>
                                    <h3 id="appliedCounter" class="card-title text-white">0</h3>
                                </div>
                            </div>
                        </a>
                        
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <a href="applications.php?status=rejected" class="text-decoration-none">
                            <div class="card bg-warning shadow-sm">
                                <div class="card-body">
                                    <h4 class="card-title text-dark">Rejected Jobs:</h4>
                                    <h3 id="ongoingCounter" class="card-title text-dark">0</h3>
                                </div>
                            </div>
                        </a>
                        
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <a href="applications.php?status=accepted" class="text-decoration-none">
                            <div class="card bg-success shadow-sm">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Employed Jobs:</h4>
                                    <h3 id="completedCounter" class="card-title text-white">0</h3>
                                </div>
                            </div>
                        </a>
                        
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <a href="apply_for_job.php" class="text-decoration-none">
                            <div class="card bg-danger shadow-sm">
                                <div class="card-body">
                                    <h4 class="card-title text-white text-center">Apply for a Job</h4>
                                    <h3 class="card-title text-white text-center">+</h3>
                                </div>
                            </div>
                        </a>
                        
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <a href="my_employers.php" class="text-decoration-none">
                            <div class="card bg-secondary shadow-sm">
                                <div class="card-body">
                                    <h4 class="card-title text-white">View Employers</h4>
                                </div>
                            </div>
                        </a>
                        
                    </div>
                </div>
            </div>
        </header>
        <div class="container text-dark py-3">
            <div class="row">
                <div class="container mb-3">
                    <h1 class="text-dark display-6 fw-bold">Recent Jobs</h1>
                </div>
            </div>
            <div class="row">
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-white border-dark border-1">
                                <tr>
                                    <th scope="col" class="text-nowrap w-auto">Category</th>
                                    <th scope="col" class="text-nowrap w-auto">Type</th>
                                    <th scope="col" class="text-nowrap w-auto">Date</th>
                                    <th scope="col" class="text-nowrap w-auto">Application</th>
                                </tr>
                            </thead>
                            <tbody class="table-white border-dark border-1" id="recentApplicationsTableBody">
            
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/employee-db.js"></script>
        <script>
            function confirmSwitchRole(role) {
                return confirm(`Are you sure you want to switch your role to ${role.toUpperCase()}?\nThis will change your dashboard and available features.`);
            }
        </script>
    </body>
    </html>