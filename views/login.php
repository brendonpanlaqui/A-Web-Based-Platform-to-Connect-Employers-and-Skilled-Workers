<?php
use Controllers\LoginController;
require_once __DIR__ . '/../controllers/LoginController.php';

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call the LoginController to authenticate
    $loginController = new LoginController();
    $error_message = $loginController->login($email, $password);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoftEng2</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-white">
    <nav class="navbar navbar-expand-md bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand text-dark" href="login.php">Quest Hunt</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-dark" href="login.php">Post</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="login.php">Apply</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="signup.php">Join</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="text-dark pt-5 pb-3 mt-4 mt-md-5">
        <div class="container d-flex flex-column">
            <div class="col-12">
                <h2 class="display-4 fw-bold text-start text-md-center">Login to your account</h2>
                <p class="text-md-center">
                    Don't have an account?&nbsp;
                    <a class="text-dark" href="signup.php"><span>Join now</span></a>
                </p>
            </div>
        </div>
    </header>

    <?php if ($error_message): ?>
        <div class="alert alert-danger text-center" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <div class="container d-flex justify-content-center">
        <div class="text-dark text-start">
            <!-- Set method="POST" here -->
            <form id="loginform" method="POST" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control border-dark" id="email" name="email" required>
                        <p class="text-danger small mt-1 error-message" id="email_error"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group mb-3">
                            <input id="passwordInput" type="password" class="form-control border-dark" name="password" required>
                            <button type="button" class="input-group-text bg-white border-dark togglePassword" data-target="passwordInput" style="border-left: none; cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <p class="text-danger small mt-1 error-message" id="password_error"></p>
                    </div>
                    <div class="d-flex my-5 justify-content-center">
                        <button type="submit" class="btn btn-danger w-75">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/login.js"></script>
</body>
</html>
