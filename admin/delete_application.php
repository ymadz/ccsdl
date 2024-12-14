<?php
session_start();
require_once('../classes/application.class.php');

// Check if user is logged in
if (!isset($_SESSION['account']) || empty($_SESSION['account']['role_id'])) {
    error_log("Delete application: Unauthorized access attempt");
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['applicationId'])) {
    error_log("Delete application: No application ID provided");
    echo json_encode(['success' => false, 'message' => 'Application ID is required']);
    exit;
}

$applicationObj = new Application();

try {
    $result = $applicationObj->deleteApplication($data['applicationId']);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Delete application: Failed to delete application ID " . $data['applicationId']);
        echo json_encode(['success' => false, 'message' => 'Failed to delete application']);
    }
} catch (Exception $e) {
    error_log("Delete application error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 