<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update') {
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        header('Location: ../views/login.php');
        exit;
    }

    // Get current user data
    $query = "SELECT first_name, last_name, expertise, education, bio, contact_number, profile_photo, password FROM users WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $currentUser = mysqli_fetch_assoc($result);

    $new_password = trim($_POST['new_password'] ?? '');
    $new_password_confirmation = trim($_POST['new_password_confirmation'] ?? '');

    if ($new_password === '') {
        $hashedPassword = $currentUser['password'];
    } else {
        if ($new_password !== $new_password_confirmation) {
            $errors[] = "Passwords do not match.";
        }
        if (empty($errors)) {
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        }
    }

    // Preserve old data if new input is empty
    $first_name = !empty(trim($_POST['first_name'])) ? trim($_POST['first_name']) : $currentUser['first_name'];
    $last_name = !empty(trim($_POST['last_name'])) ? trim($_POST['last_name']) : $currentUser['last_name'];
    $expertise = isset($_POST['expertise']) ? trim($_POST['expertise']) : $currentUser['expertise'];
    $education = isset($_POST['education']) ? trim($_POST['education']) : $currentUser['education'];
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : $currentUser['bio'];
    $contact = isset($_POST['contact_number']) && $_POST['contact_number'] !== ''
    ? '0' . trim($_POST['contact_number'])
    : $currentUser['contact_number'];

    $profile_photo = $currentUser['profile_photo']; // Default to old photo filename

    // Handle file upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate safe and unique file name
        $originalName = basename($_FILES['profile_photo']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $uniqueName = time() . '_' . uniqid() . '.' . $extension;

        $targetPath = $uploadDir . $uniqueName;

        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath)) {
            $profile_photo = $uniqueName; // Save only the filename
        }
    }

    // Prepare update query
    $query = "UPDATE users SET first_name=?, last_name=?, expertise=?, education=?, bio=?, contact_number=?, profile_photo=?, password=? WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssssssssi", $first_name, $last_name, $expertise, $education, $bio, $contact, $profile_photo, $hashedPassword, $userId);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../views/profile.php?updated=true');
        exit;
    } else {
        echo "Error updating profile: " . mysqli_error($con);
    }
}
?>
