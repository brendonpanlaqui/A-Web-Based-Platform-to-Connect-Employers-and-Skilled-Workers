<?php
namespace Controllers;

session_start();
require_once __DIR__ . '/../config/database.php'; // Ensure the correct path to the database file

class LoginController {

    // Login function to authenticate user
    public function login($email, $password) {
        // Access the global $con variable from database.php
        global $con;

        // Sanitize user input
        $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Clean email input

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }

        // Prepare query to fetch user data by email
        $query = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($con, $query);

        if (!$stmt) {
            return "Database error.";
        }

        // Bind the email parameter
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $user_id, $db_email, $hashed_password);

        // Check if email exists in the database
        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_fetch($stmt);
            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                // Set session variables upon successful login
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;

                // Redirect to the appropriate page
                $redirectUrl = '/A-Web-Based-Platform-to-Connect-Employers-and-Skilled-Workers/views/employer-dashboard.php';
                header("Location: $redirectUrl");
                exit();
            } else {
                return "Invalid password.";
            }
        } else {
            return "No account found with this email.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
}

?>