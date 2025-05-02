<?php include '../includes/nav.php'; ?>

<?php
// Include your database connection, session, etc.
require_once '../config/database.php'; // Adjust path if needed

$userId = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worker Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- your main CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4 mt-5">Edit Profile</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="../controllers/ProfileController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update">
            <div class="mb-3">
                <label class="form-label">First Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Last Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Specialties:</label>
                <input type="text" name="specialties" class="form-control" value="<?php echo htmlspecialchars($user['specialties']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Bio:</label>
                <textarea name="bio" class="form-control"><?php echo htmlspecialchars($user['bio']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Number:</label>
                <input type="text" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($user['contact_number']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Photo:</label>
                <input type="file" name="profile_photo" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Save Changes</button>
        </form>
    </div>

</body>
</html>
