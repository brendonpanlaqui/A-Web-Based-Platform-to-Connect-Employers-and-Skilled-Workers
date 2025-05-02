<?php
session_start();

$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// CSRF Token (if needed for forms)
$csrf_token = bin2hex(random_bytes(32)); // Generate a CSRF token
$_SESSION['csrf_token'] = $csrf_token; // Store the token in session for future validation
?>

<nav class="navbar navbar-expand-md bg-white fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand text-dark" href="index.php">Quest Hunt</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <!-- Guest User -->
                    <li class="nav-item"><a class="nav-link text-dark" href="login.php">Post</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="login.php">Apply</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="signup.php">Join</a></li>
                <?php else: ?>
                    <!-- Admin User -->
                    <?php if ($user_role === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link text-dark" href="/admin/dashboard">Admin Dashboard</a></li>
                    <?php endif; ?>
                    <!-- Employer User -->
                    <?php if ($user_role === 'employer'): ?>
                        <li class="nav-item"><a class="nav-link text-dark" href="employer-dashboard.php">My Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="post.php">Post</a></li>
                    <?php endif; ?>
                     <!-- Worker User -->
                    <?php if ($user_role === 'worker'): ?>
                        <li class="nav-item"><a class="nav-link text-dark" href="worker-dashboard.php">My Dashboard</a></li>
                    <?php endif; ?>
                    
                    <li class="nav-item"><a class="nav-link text-dark" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a href="logout.php" class="btn btn-danger">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

