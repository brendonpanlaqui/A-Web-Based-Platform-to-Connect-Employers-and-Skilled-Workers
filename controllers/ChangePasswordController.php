<?php
session_start();
include_once '../config/database.php'; // Or wherever your DB connection is

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $user_id = $_SESSION['user_id']; // Ensure session contains this
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New passwords do not match.";
        header("Location: ../views/profile.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password_hash);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_password, $db_password_hash)) {
        $_SESSION['error'] = "Incorrect current password.";
        header("Location: ../views/profile.php");
        exit();
    }

    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_hashed_password, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['success'] = "Password updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update password.";
    }

    $update_stmt->close();
    $conn->close();

    header("Location: ../views/profile.php");
    exit();
}
