<?php
// RegisterController.php
include '../config/database.php'; // Adjust the path according to your structure

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs and sanitize them
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];
    $role = $_POST['role'];

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($password_confirmation)) {
        $errors[] = 'All fields are required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    // Password validation
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if (!preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must include at least one uppercase letter, one lowercase letter, and one number.';
    }

    if ($password !== $password_confirmation) {
        $errors[] = 'Passwords do not match.';
    }

    // Check if email already exists
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = 'Email already exists.';
    }

    // If no errors, proceed with user creation
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $con->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);
        if ($stmt->execute()) {
            // Redirect based on role
            if ($role === 'employer') {
                header("Location: /SOFTENG2/views/employer-dashboard.php");
            } else {
                header("Location: /SOFTENG2/views/worker-dashboard.php");
            }
            exit();
        } else {
            $errors[] = 'Failed to create account. Please try again later. ' . $stmt->error;
            // Optionally, log the error to a file
            error_log('Database error: ' . $stmt->error);
        }
    }
}
?>
