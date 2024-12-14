<?php
require_once "../includes/authentication.php";
require_once "../classes/application.class.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$applicationObj = new Application();

try {
    // Validate session and role
    if (!isset($_SESSION['account']) || $_SESSION['account']['role_id'] != 2) {
        throw new Exception("Unauthorized access");
    }

    switch ($data['action']) {
        case 'approve':
            $result = $applicationObj->processApplication($data['applicationId'], 'Approved');
            break;
            
        case 'reject':
            if (empty($data['reason'])) {
                throw new Exception('Rejection reason is required');
            }
            $result = $applicationObj->processApplication($data['applicationId'], 'Rejected', $data['reason']);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 