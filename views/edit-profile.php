<?php include '../includes/nav.php'; ?>

<?php
require_once '../config/database.php'; 

$userId = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
$role = strtolower($user['role']);
$defaultPhoto = '../uploads/default.png';
$profilePhoto = !empty($user['profile_photo']) ? '../uploads/' . $user['profile_photo'] : $defaultPhoto;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= ucfirst($role) ?> Edit Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- your main CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4 mt-5">Edit Profile</h2>
        <div class="row g-4">
            <!-- Card 1: Profile Preview -->
            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <div class="text-center">
                        <img src="<?= htmlspecialchars($profilePhoto) ?>" alt="Profile" class="img-fluid rounded-circle border border-danger mb-3" style="width: 120px;">
                    </div>
                    <h5><?= ucwords($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                    <p><strong><?= ucfirst($user['role']) ?></strong></p>
                </div>
            </div>

            <!-- Card 2: Edit Form -->
            <div class="col-md-8">
                <div class="card p-4">
                    <h4 class="mb-3">Update Profile</h4>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="../controllers/ProfileController.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($user['contact_number']) ?>">
                        </div>

                        <?php if ($role === 'worker'): ?>
                            <div class="mb-3">
                                <label class="form-label">Expertise <span class="text-danger">*</span></label>
                                <input type="text" name="expertise" class="form-control" value="<?= htmlspecialchars($user['expertise']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Education</label>
                                <textarea name="education" class="form-control"><?= htmlspecialchars($user['education']) ?></textarea>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control"><?= htmlspecialchars($user['bio']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-danger">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
