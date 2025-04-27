<?php
namespace Controllers;

session_start();
require_once __DIR__ . '/../config/database.php'; // Ensure the correct path to the database file

class LoginController {
    public function login($email, $password) {
        // Access the global $con variable from database.php
        global $con;

        // Prepare query to fetch user data by email
        $query = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($con, $query);
        
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
                // Set session variables upon successful login
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;

                // Redirect to the dashboard page after successful login
                header("Location: /SOFTENG2/views/employer-dashboard.php");
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
