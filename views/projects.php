<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <body class="bg-white">
        <nav class="navbar navbar-expand-md bg-white fixed-top shadow-sm">
            <div class="container">
                <a class="navbar-brand text-dark" href="index.php">Software Engineering 2</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link text-dark" href="#">Switch to Employee</a></li>
                        <li class="nav-item"><a class="nav-link text-primary" href="#" onclick="openPostModal()">Post</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="#">Profile</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <header class="text-dark pt-5 pb-3 mt-4 mt-md-5">
            <div class="container">
                    <h1 id="projectStatusTitle" class="display-5 fw-bold"></h1>
            </div>
        </header>
        <div class="container">
            <div id="cardContainer" class="row">

            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/projects.js"></script>
</body>
</html>