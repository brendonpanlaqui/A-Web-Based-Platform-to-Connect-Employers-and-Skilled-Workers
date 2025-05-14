<?php
// JobController.php
include '../config/database.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start session and check login
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('You must be logged in to post a job.'); window.history.back();</script>";
        exit();
    }

    $employer_id = $_SESSION['user_id'];

    // Get and sanitize form inputs
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $type = $_POST['type'];
    $platform = isset($_POST['platform']) ? trim($_POST['platform']) : NULL;
    $location = !empty($_POST['location']) ? trim($_POST['location']) : NULL;
    $time_estimate = trim($_POST['time_estimate']);
    $expertise_level = $_POST['expertise_level'];
    $salary = !empty($_POST['salary']) ? trim($_POST['salary']) : NULL;
    $description = trim($_POST['description']);

    // Validation
    if (empty($title) || empty($category) || empty($type) || empty($time_estimate) || empty($expertise_level) || empty($description)) {
        $errors[] = 'All required fields must be filled.';
    }

    if (!empty($salary) && !is_numeric($salary)) {
        $errors[] = 'Salary must be a numeric value.';
    }

    $valid_expertise_levels = ['Entry', 'Intermediate', 'Expert'];
    if (!in_array($expertise_level, $valid_expertise_levels)) {
        $errors[] = 'Invalid expertise level.';
    }

    if (!in_array($type, ['Online', 'Offline'])) {
        $errors[] = 'Invalid job type.';
    }

    // Check penalization
    $penaltyCheck = $con->prepare("SELECT penalized_until FROM users WHERE id = ?");
    $penaltyCheck->bind_param("i", $employer_id);
    $penaltyCheck->execute();
    $penaltyResult = $penaltyCheck->get_result();

    if ($penaltyRow = $penaltyResult->fetch_assoc()) {
        $penalizedUntil = $penaltyRow['penalized_until'];
        if ($penalizedUntil && strtotime($penalizedUntil) > time()) {
            $errors[] = 'You are currently penalized and cannot post a job until ' . date('F j, Y, g:i a', strtotime($penalizedUntil)) . '.';
        }
    }

    // If errors, show alert and go back
    if (!empty($errors)) {
        echo "<script>alert('" . implode("\\n", $errors) . "'); window.history.back();</script>";
        exit();
    }

    // Prepare insert query
    if ($location === NULL && $platform === NULL) {
        $stmt = $con->prepare("INSERT INTO jobs (employer_id, title, category, type, platform, location, time_estimate, expertise_level, salary, description) 
                               VALUES (?, ?, ?, ?, NULL, NULL, ?, ?, ?, ?)");
        $stmt->bind_param("issssssds", $employer_id, $title, $category, $type, $time_estimate, $expertise_level, $salary, $description);
    } elseif ($location !== NULL && $platform === NULL) {
        $stmt = $con->prepare("INSERT INTO jobs (employer_id, title, category, type, platform, location, time_estimate, expertise_level, salary, description) 
                               VALUES (?, ?, ?, ?, NULL, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssds", $employer_id, $title, $category, $type, $location, $time_estimate, $expertise_level, $salary, $description);
    } elseif ($location === NULL && $platform !== NULL) {
        $stmt = $con->prepare("INSERT INTO jobs (employer_id, title, category, type, platform, location, time_estimate, expertise_level, salary, description) 
                               VALUES (?, ?, ?, ?, ?, NULL, ?, ?, ?, ?)");
        $stmt->bind_param("issssssds", $employer_id, $title, $category, $type, $platform, $time_estimate, $expertise_level, $salary, $description);
    } else {
        $stmt = $con->prepare("INSERT INTO jobs (employer_id, title, category, type, platform, location, time_estimate, expertise_level, salary, description) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssds", $employer_id, $title, $category, $type, $platform, $location, $time_estimate, $expertise_level, $salary, $description);
    }

    // Execute and redirect or show error
    if ($stmt->execute()) {
        echo "<script>alert('Job posted successfully!'); window.location.href = '/A-Web-Based-Platform-to-Connect-Employers-and-Skilled-Workers/views/employer-dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to post job. Please try again later.'); window.history.back();</script>";
        error_log('Database error: ' . $stmt->error);
    }
}
?>
