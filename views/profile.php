<?php include '../includes/nav.php'; ?>

<?php
// Include the database connection file
require_once '../config/database.php'; // Adjust the path if needed

// Check if the user is logged in (session should contain user ID)
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Prepare the SQL query to fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);

// Bind parameters and execute
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);

// Get result
$result = mysqli_stmt_get_result($stmt);

// Fetch user data
$user = mysqli_fetch_assoc($result);

// Check if user data exists
if ($user) {
    // Default profile photo path
    $defaultPhoto = '../uploads/default.png';

    // Get the user details
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
    // Handle case where user does not exist
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $role ?> Dashboard</title>
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
                            <img src="<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profile Picture" class="img-fluid rounded-circle border border-danger" width="120" height="120">
                        </div>
                        <h3 class="mt-3 fw-bold"><?= $first_name . " " . $last_name ?></h3>
                        <p class="text-muted"><strong><?= $role ?></strong> | <?= ucwords(strtolower(htmlspecialchars($expertise ?? 'Unknown'))) ?></p>
                        <a href="edit-profile.php" class="btn btn-danger w-100 mt-2 mb-2">Edit Profile</a>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card p-4">
                        <h4 class="fw-bold">Profile Information</h4><hr>
                        <ul class="list-unstyled">
                            <li class="mb-1"><strong>Email:</strong> <?= $email ?></li>
                            <li class="mb-1"><strong>Contact Number:</strong> <?= $contactNumber ?></li>
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
</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>
