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

    // Check if the email already exists in the database
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = 'Email already exists.';
    }

    // If there are no errors, hash the password and save to the database
    if (empty($errors)) {
        // Hash the password for secure storage
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the query to insert user data into the database
        $stmt = $con->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);
        
        if ($stmt->execute()) {
            // Redirect based on role
            $response = [
                'success' => true,
                'redirectUrl' => '/A-Web-Based-Platform-to-Connect-Employers-and-Skilled-Workers/views/login.php'
            ];
            echo json_encode($response);
            exit();
        } else {
            $errors[] = 'Failed to create account. Please try again later. ' . $stmt->error;
            // Optionally, log the error to a file
            error_log('Database error: ' . $stmt->error);
        }
    }
}
?>
