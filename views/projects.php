<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <body class="bg-white">
        <?php include '../includes/nav.php'; ?>

        <header class="text-dark pt-5 pb-3 mt-4 mt-md-5">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h1 id="projectStatusTitle" class="display-5 fw-bold mb-0"></h1>
                    <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
                </div>
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