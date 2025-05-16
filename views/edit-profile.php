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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        #profile_photo {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4 mt-5">Edit Profile</h2>
        <form id="editProfileForm" action="../controllers/ProfileController.php" method="POST" enctype="multipart/form-data">
            <div class="row g-4">
                <!-- Card 1: Profile Preview -->
                <div class="col-md-4">
                    <div class="card p-4 text-center">
                        <div class="text-center mb-3">
                            <div class="mx-auto border border-danger rounded-circle overflow-hidden" style="width: 120px; height: 120px;">
                                <img src="<?= htmlspecialchars($profilePhoto) ?>" alt="Profile" 
                                    class="w-100 h-100" 
                                    style="object-fit: cover;">
                            </div>
                        </div>
                        <h5><?= ucwords($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                        <p><strong><?= ucfirst($user['role']) ?></strong></p>
                        <div>
                            <label for="profile_photo" class="form-label fw-semibold">
                                <i class="bi bi-upload me-2"></i>Upload Profile Photo
                            </label>
                            <input class="form-control" type="file" id="profile_photo" name="profile_photo" accept="image/*">
                        </div>
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
                            <div class="input-group mb-3">
                                <span class="input-group-text">+639</span>
                                <input type="text" id="contact_number" name="contact_number" class="form-control" value="<?= htmlspecialchars(substr($user['contact_number'], 4)) ?>">
                            </div>
                            <p class="text-danger small mt-1 error-message" id="contact_number_error"></p>
                        </div>

                        <?php if ($role === 'worker'): ?>
                            <div class="mb-3">
                                <label class="form-label">Expertise</label>
                                <input type="text" name="expertise" class="form-control" value="<?= htmlspecialchars($user['expertise']) ?>">
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
                            <label for="new_password" class="form-label">New Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                            <div class="input-group mb-3">
                                <input id="new_password" type="password" name="new_password" class="form-control">
                                <button type="button" class="input-group-text bg-white togglePassword" data-target="new_password" style="border-left: none; cursor: pointer;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <p class="text-danger small mt-1 error-message" id="new_password_error"></p>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <div class="input-group mb-3">
                                <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="form-control">
                                <button type="button" class="input-group-text bg-white togglePassword" data-target="new_password_confirmation" style="border-left: none; cursor: pointer;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <p class="text-danger small mt-1 error-message" id="new_password_confirmation_error"></p>  
                        </div>

                        <button type="submit" class="btn btn-danger">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/change.js"></script>
</body>
</html>
