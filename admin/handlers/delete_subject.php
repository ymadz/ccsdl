<?php
require_once('../../classes/database.class.php');
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_POST['id'])) {
        throw new Exception('Missing subject ID');
    }

    $db = new Database();
    
    // Get the course from the POST data
    $course = $_POST['course'];
    $table = '';
    
    // Determine which table to use
    switch($course) {
        case 'bscs':
            $table = 'bscs_prospectus';
            break;
        case 'bsit':
            $table = 'bsit_prospectus';
            break;
        case 'act-ad':
            $table = 'act_ad_prospectus';
            break;
        case 'act-nw':
            $table = 'act_nw_prospectus';
            break;
        default:
            throw new Exception('Invalid course');
    }

    $query = "DELETE FROM $table WHERE id = ?";
    $db->execute($query, [$_POST['id']]);

    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} 