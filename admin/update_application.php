<?php
session_start();
require_once('../classes/application.class.php');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$applicationObj = new Application();

try {
    // Update application
    $result = $applicationObj->updateApplication(
        $data['application_id'],
        $data['school_year'],
        $data['semester'],
        $data['subject_ratings'],
        $_SESSION['account']['user_id']
    );
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Changes saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save changes']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
