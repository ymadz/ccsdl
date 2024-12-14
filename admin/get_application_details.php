<?php
session_start();
require_once('../classes/application.class.php');

header('Content-Type: application/json');

if (!isset($_SESSION['account']) || empty($_SESSION['account']['role_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'No application ID provided']);
    exit;
}

$applicationObj = new Application();

try {
    $application = $applicationObj->getApplicationDetails($_GET['id']);
    echo json_encode([
        'success' => true,
        'student_name' => $application['student_name'] ?? 'N/A',
        'school_year' => $application['school_year'],
        'semester' => $application['semester'],
        'adviser' => $application['adviser'] ?? 'N/A',
        'subjects' => $application['subjects']
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 