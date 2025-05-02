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
    $defaultPhoto = 'default.png';

    // Get the user details
    $first_name = htmlspecialchars($user['first_name']);
    $last_name = htmlspecialchars($user['last_name']);
    $email = htmlspecialchars($user['email']);
    $role = ucfirst($user['role']);
    $specialties = !empty($user['specialties']) ? htmlspecialchars($user['specialties']) : 'N/A';
    $bio = !empty($user['bio']) ? htmlspecialchars($user['bio']) : 'No bio available';
    $contactNumber = !empty($user['contact_number']) ? htmlspecialchars($user['contact_number']) : 'N/A';
    $profilePhoto = !empty($user['profile_photo']) ? 'storage/' . $user['profile_photo'] : $defaultPhoto;
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
    <title>Worker Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- your main CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4 mt-5">User Profile</h2>
        <div class="card">
            <div class="card-body">
                <!-- Profile Photo -->
                <img src="<?php echo $profilePhoto; ?>" alt="Profile Photo" class="img-thumbnail" width="150">
                
                <!-- User Information -->
                <p><strong>Name:</strong> <?php echo $first_name . " " . $last_name; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Role:</strong> <?php echo $role; ?></p>
                <p><strong>Specialties:</strong> <?php echo $specialties; ?></p>
                <p><strong>Bio:</strong> <?php echo $bio; ?></p>
                <p><strong>Contact:</strong> <?php echo $contactNumber; ?></p>
                
                <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>

    <script src="path/to/your/bootstrap.bundle.js"></script> <!-- Replace with actual path -->
</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>
