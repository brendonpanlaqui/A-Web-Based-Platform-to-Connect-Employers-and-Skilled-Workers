<?php
include '../config/database.php';

session_start();

$employerId = $_SESSION['user_id'] ?? null;

if (!$employerId) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle action (accept/reject) for a job application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['action'])) {
    $applicationId = $_POST['application_id'];
    $action = $_POST['action'];

    if (!in_array($action, ['accepted', 'rejected'])) {
        echo json_encode(['error' => 'Invalid action']);
        exit;
    }

    // Begin a transaction to ensure both application and job status are updated
    mysqli_begin_transaction($con);

    try {
        // Update the application status
        $sql = "UPDATE job_applications SET status = ? WHERE id = ? AND job_id IN (SELECT id FROM jobs WHERE employer_id = ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sii", $action, $applicationId, $employerId);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update application status");
        }

        // If the action is accepted, update the job status to ongoing
        if ($action === 'accepted') {
            // Get the job_id of the application
            $sql = "SELECT job_id FROM job_applications WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $applicationId);
            $stmt->execute();
            $stmt->bind_result($jobId);
            $stmt->fetch();
            $stmt->close();

            // Update the job status to ongoing
            $sql = "UPDATE jobs SET status = 'ongoing' WHERE id = ? AND employer_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ii", $jobId, $employerId);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update job status to 'ongoing'");
            }
        }

        // Commit the transaction
        mysqli_commit($con);
        echo json_encode(['success' => 'Application status and job status updated']);
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        mysqli_roll_back($con);
        echo json_encode(['error' => $e->getMessage()]);
    }

    $stmt->close();
    exit;
}

// Get all applications for jobs posted by this employer
$sql = "
    SELECT 
        a.id AS application_id,
        a.status AS application_status,
        a.date_applied,
        j.title AS job_title,
        CONCAT(u.first_name, ' ', u.last_name) AS worker_name
    FROM job_applications a
    JOIN jobs j ON a.job_id = j.id
    JOIN users u ON a.worker_id = u.id
    WHERE j.employer_id = ?
    ORDER BY a.date_applied DESC
";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $employerId);
$stmt->execute();
$result = $stmt->get_result();

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

header('Content-Type: application/json');
echo json_encode($applications);
$con->close();
?>
