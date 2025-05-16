<?php
session_start();
require_once __DIR__ . '/../config/database.php';  

// Check if user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php");  
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-md bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand text-dark" href="admin-dashboard.php">Quest Hunt Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-dark" href="#">View Users</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="#">View Posts</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="#">View Applications</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="#">Manage Complaints</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Signout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <header class="text-dark py-5 mt-4 mt-md-5">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <h1 class="display-5 fw-bold">Dashboard &middot; Admin</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-3 mb-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card bg-secondary shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title text-white">View Users</h4>
                            </div>
                        </div>
                    </a>
                    
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <a href= "view_posts.html" class="text-decoration-none">
                        <div class="card bg-secondary shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title text-white">View Posts</h4>
                            </div>
                        </div>
                    </a>
                    
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <a href= "view_applications.html" class="text-decoration-none">
                        <div class="card bg-secondary shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title text-white">View Applications</h4>
                            </div>
                        </div>
                    </a>
                    
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <a href= "view_all_complaints.html" class="text-decoration-none">
                        <div class="card bg-secondary shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title text-white">Manage Complaints</h4>
                            </div>
                        </div>
                    </a>
                    
                </div>
            </div>
        </div>
    </header>
    <div class="container text-dark py-3">
        <div class="row mb-5">
            <div class="col-12">
                <form class="d-flex" role="search" id="searchForm">
                    <input class="form-control me-2 border-1 border-dark" type="search" placeholder="Search user" aria-label="Search" id="searchInput">
                    <button class="btn btn-danger" type="submit">Search</button> 
                </form>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-white border-dark border-1">
                            <tr>
                                <th scope="col" class="text-nowrap w-auto">Id</th>
                                <th scope="col" class="text-nowrap w-auto">Firstname</th>
                                <th scope="col" class="text-nowrap w-auto">Lastname</th>
                                <th scope="col" class="text-nowrap w-auto">Email</th>
                                <th scope="col" class="text-nowrap w-auto">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-white border-dark border-1" id="userTableBody">
        
                        </tbody>
                    </table>
                </div>
                
            </div>
            
        </div>
    </div>      
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
</body>
</html>