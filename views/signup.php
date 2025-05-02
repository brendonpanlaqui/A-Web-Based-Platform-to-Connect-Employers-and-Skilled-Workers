<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoftEng2</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    </head>
<body class="bg-white">
    <nav class="navbar navbar-expand-md bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand text-dark" href="index.php">Software Engineering 2</a>
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
                        <h2 class="display-4 fw-bold text-start text-md-center">Join the Community</h2>
                        <p class="text-md-center">
                            Already have an account?&nbsp;
                            <a class="text-dark" href="login.php"><span>Let's login</span></a>
                        </p>
                    </div>
                </div>
    </header>
    <div class="container d-flex justify-content-center">
        <div class="text-dark text-start">
            <form id="signupform" action="../controllers/RegisterController.php" method="POST" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="first_name" class="form-label ">Firstname</label>
                        <input type="text" class="form-control border-dark " id="first_name" name="first_name" required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="last_name" class="form-label ">Lastname</label>
                        <input type="text" class="form-control border-dark " id="last_name" name="last_name" required>
                    </div>     
                    <div class="col-12 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control border-dark" id="email" name="email" required>
                    </div>
                    <div class="col-12">
                        <label for="password" class="form-label ">Password</label>
                        <div class="input-group mb-3">
                            <input id="passwordInput" type="password" class="form-control border-dark" name="password" required>
                            <button type="button" class="input-group-text bg-white border-dark togglePassword" data-target="passwordInput" style="border-left: none; cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-group mb-3">
                                <input id="passwordConfirmation" type="password" class="form-control border-dark" name="password_confirmation" required>
                                <button type="button" class="input-group-text bg-white border-dark togglePassword" data-target="passwordConfirmation" style="border-left: none; cursor: pointer;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    <div class="col-12 mb-3">
                        <label for="type" class="form-label">Signing up as</label>
                        <select class="form-select border-dark" id="type" name="role" required>
                            <option value="employer">Employer</option>
                            <option value="worker">Worker</option>
                        </select>
                    </div>
                    </div>
                    <div class=" d-flex my-5 justify-content-center">
                        <button type="submit" class="btn btn-danger w-75">Create my Account</button>
                    </div>
                </div>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </form>
                                
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
