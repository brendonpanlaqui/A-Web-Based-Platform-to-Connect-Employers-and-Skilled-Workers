<?php
header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

$userId = intval($_GET['id']);

require '../config/database.php';

$con->begin_transaction();

try {
    // Delete related posts (jobs) first
    $deleteJobsQuery = "DELETE FROM jobs WHERE employer_id = ?";
    $stmt = $con->prepare($deleteJobsQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Then delete the user
    $deleteUserQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $con->prepare($deleteUserQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Commit transaction if both deletions are successful
    $con->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // If there was an error, rollback the transaction
    $con->rollback();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// Close the connection
$con->close();
?>
