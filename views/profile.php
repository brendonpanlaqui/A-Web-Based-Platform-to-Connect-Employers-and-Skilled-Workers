
<?php
require_once '../config/database.php'; 
include '../includes/nav.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = isset($_GET['user_id']) && is_numeric($_GET['user_id']) 
            ? intval($_GET['user_id']) 
            : $_SESSION['user_id'];


$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user) {
    $defaultPhoto = '../uploads/default.png';
    $first_name = htmlspecialchars($user['first_name']);
    $last_name = htmlspecialchars($user['last_name']);
    $email = htmlspecialchars($user['email']);
    $role = ucfirst($user['role']);
    $expertise = !empty($user['expertise']) ? htmlspecialchars($user['expertise']) : 'Unknown';
    $education = !empty($user['education']) ? htmlspecialchars($user['education']) : 'N/A';
    $bio = !empty($user['bio']) ? htmlspecialchars($user['bio']) : 'No bio available';
    $contactNumber = !empty($user['contact_number']) ? htmlspecialchars($user['contact_number']) : 'N/A';
    $profilePhoto = !empty($user['profile_photo']) ? '../uploads/' . $user['profile_photo'] : $defaultPhoto;
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $role ?> Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- your main CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 mt-5">Profile</h2>
        <div class="container my-5">
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="card p-5">
                        <div class="text-center">
                            <div class="mx-auto border border-danger rounded-circle overflow-hidden" 
                                style="width: 120px; height: 120px;">
                                <img src="<?= htmlspecialchars($profilePhoto) ?>" alt="Profile Picture" 
                                    class="w-100 h-100" 
                                    style="object-fit: cover;">
                            </div>
                        </div>
                        <h3 class="mt-3 fw-bold"><?= $first_name . " " . $last_name ?></h3>
                        <p class="text-muted"><strong><?= $role ?></strong> | <?= ucwords(strtolower(htmlspecialchars($expertise ?? 'Unknown'))) ?></p>
                        <?php if ($userId === $_SESSION['user_id']): ?>
                            <a href="edit-profile.php" class="btn btn-danger w-100 mt-2 mb-2">Edit Profile</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card p-4">
                        <h4 class="fw-bold">Profile Information</h4><hr>
                        <ul class="list-unstyled">
                            <li class="mb-1"><strong>Email:</strong> <?= $email ?></li>
                            <li class="mb-1"><strong>Contact Number:</strong> 0<?= $contactNumber ?></li>
                            <?php if (strtolower($user['role']) === 'worker'): ?>
                                <li class="mb-1"><strong>Expertise:</strong> <?= ucwords(strtolower(htmlspecialchars($expertise ?? 'Unknown')))?></li>
                                <li class="mb-1"><strong>Education:</strong> <?= $education ?></li>
                                <li class="mb-1"><strong>Bio:</strong> <?= $bio ?></li>
                            <?php elseif (strtolower($user['role']) === 'employer'): ?>
                                <li class="mb-1"><p class="text-muted">Employers don’t have expertise or bios.</p></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($con);
?>
