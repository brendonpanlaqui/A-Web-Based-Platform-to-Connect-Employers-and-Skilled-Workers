<?php
// RegisterController.php
include '../config/database.php'; // Adjust the path

header('Content-Type: application/json'); // Important to send JSON
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize inputs
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];
    $role = $_POST['role'];
    $first_name = ucwords(strtolower(trim($_POST['first_name'])));
    $last_name = ucwords(strtolower(trim($_POST['last_name'])));


    // Check for existing email
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = 'Email already exists.';
    }

    // Continue if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $email_verified_at = date('Y-m-d H:i:s');
        $stmt = $con->prepare("INSERT INTO users (first_name, last_name, email, password, role, email_verified_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $hashed_password, $role, $email_verified_at);
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'redirectUrl' => '/A-Web-Based-Platform-to-Connect-Employers-and-Skilled-Workers/views/login.php'
            ]);
            exit();
        } else {
            $errors[] = 'Failed to create account. Please try again later.';
            error_log('DB error: ' . $stmt->error);
        }
    }

    // Return errors if any
    echo json_encode([
        'success' => false,
        'errors' => $errors
    ]);
    exit;
}
?>
