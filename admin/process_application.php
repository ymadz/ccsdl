<?php
session_start();
require_once('../classes/application.class.php');

// Set JSON response header
header('Content-Type: application/json');

// Check if user is logged in and has correct role
if (!isset($_SESSION['account']) || empty($_SESSION['account']['role_id'])) {
    error_log("Process application: Unauthorized access attempt");
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['applicationId']) || !isset($data['action'])) {
    error_log("Process application: Invalid request data");
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit;
}

try {
    $applicationObj = new Application();
    $result = false;

    switch ($data['action']) {
        case 'approve':
            $result = $applicationObj->processApplication(
                $data['applicationId'], 
                'Approved'
            );
            break;

        case 'reject':
            if (!isset($data['reason'])) {
                throw new Exception('Rejection reason is required');
            }
            $result = $applicationObj->processApplication(
                $data['applicationId'], 
                'Rejected',
                $data['reason']
            );
            break;

        default:
            throw new Exception('Invalid action');
    }
    
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Process application: Failed to process application ID " . $data['applicationId']);
        echo json_encode(['success' => false, 'message' => 'Failed to process application']);
    }
} catch (Exception $e) {
    error_log("Process application error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 