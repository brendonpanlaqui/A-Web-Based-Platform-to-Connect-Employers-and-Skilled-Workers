<?php
// JobController.php
include '../config/database.php'; 

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs and sanitize them
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $type = $_POST['type'];
    $platform = isset($_POST['platform']) ? trim($_POST['platform']) : NULL;  // Handle undefined platform
    $location = !empty($_POST['location']) ? trim($_POST['location']) : NULL;  // If empty, set as NULL
    $time_estimate = trim($_POST['time_estimate']);
    $expertise_level = $_POST['expertise_level'];
    $salary = !empty($_POST['salary']) ? trim($_POST['salary']) : NULL;  // If empty, set as NULL
    $description = trim($_POST['description']);

    // Basic validation
    if (empty($title) || empty($category) || empty($type) || empty($time_estimate) || empty($expertise_level) || empty($description)) {
        $errors[] = 'All fields are required.';
    }

    // Check if salary is numeric if it's provided
    if (!empty($salary) && !is_numeric($salary)) {
        $errors[] = 'Salary must be a numeric value.';
    }

    // Validate expertise level
    $valid_expertise_levels = ['Entry', 'Immediate', 'Expert'];
    if (!in_array($expertise_level, $valid_expertise_levels)) {
        $errors[] = 'Invalid expertise level.';
    }

    // Validate type (Online or Offline)
    if (!in_array($type, ['Online', 'Offline'])) {
        $errors[] = 'Job type must be either "Online" or "Offline".';
    }

    // If no errors, proceed with job creation
    if (empty($errors)) {
        // Get employer's ID (assuming it's stored in session after login)
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $errors[] = 'You must be logged in to post a job.';
        } else {
            $employer_id = $_SESSION['user_id'];

            // Prepare SQL to insert job into database
            if ($location === NULL) {
                // If location is NULL
                if ($platform === NULL) {
                    // If platform is also NULL
                    $stmt = $con->prepare("INSERT INTO jobs (employer_id, title, category, type, platform, location, time_estimate, expertise_level, salary, description) 
                                           VALUES (?, ?, ?, ?, NULL, NULL, ?, ?, ?, ?)");
                    $stmt->bind_param("issssssds", $employer_id, $title, $category, $type, $time_estimate, $expertise_level, $salary, $description);
                } else {
                    // If platform is not NULL
                    $stmt = $con->prepare("INSERT INTO jobs (employer_id, title, category, type, platform, location, time_estimate, expertise_level, salary, description) 
                                           VALUES (?, ?, ?, ?, ?, NULL, ?, ?, ?, ?)");
                    $stmt->bind_param("issssssds", $employer_id, $title, $category, $type, $platform, $time_estimate, $expertise_level, $salary, $description);
                }
            } else {
                // If location is not NULL
                if ($platform === NULL) {
                    // If platform is NULL
                    $stmt = $con->prepare("INSERT INTO jobs (employer_id, title, category, type, platform, location, time_estimate, expertise_level, salary, description) 
                                           VALUES (?, ?, ?, ?, NULL, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("issssssds", $employer_id, $title, $category, $type, $location, $time_estimate, $expertise_level, $salary, $description);
                } else {
                    // If platform is not NULL
                    $stmt = $con->prepare("INSERT INTO jobs (employer_id, title, category, type, platform, location, time_estimate, expertise_level, salary, description) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("issssssds", $employer_id, $title, $category, $type, $platform, $location, $time_estimate, $expertise_level, $salary, $description);
                }
            }

            // Execute the query
            if ($stmt->execute()) {
                // Redirect to employer's dashboard or job listing page after success
                header("Location: /SOFTENG2/views/employer-dashboard.php");
                exit();
            } else {
                $errors[] = 'Failed to post job. Please try again later.';
                // Optionally, log the error to a file
                error_log('Database error: ' . $stmt->error);
            }
        }
    }
}
?>
