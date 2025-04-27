<?php
// Start the session at the beginning of the PHP file
session_start();
// Check if the user is logged in and has an employer role
$isEmployer = isset($_SESSION['role']) && $_SESSION['role'] === 'employer';
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
                <?php if (!$isEmployer): ?>
                    <!-- Guest -->
                    <li class="nav-item"><a class="nav-link text-dark" href="login.php">Post</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="login.php">Apply</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="signup.php">Join</a></li>
                <?php else: ?>
                    <!-- Employer -->
                    <li class="nav-item"><a class="nav-link text-dark" href="post.php">Post</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a href="logout.php" class="btn btn-danger">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

