<?php
require_once('../../classes/database.class.php');
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_POST['id']) || !isset($_POST['course'])) {
        throw new Exception('Missing required parameters');
    }

    $db = new Database();
    $id = $_POST['id'];
    $course = strtolower($_POST['course']);
    
    // Determine table based on course
    $table = '';
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
    
    // Fetch subject data with explicit column selection
    $query = "SELECT 
                id,
                subject_code,
                descriptive_title,
                prerequisite,
                lec_units,
                lab_units,
                year_level,
                semester
              FROM $table 
              WHERE id = ?";
             
    $subject = $db->fetchSingle($query, [$id]);
    
    if (!$subject) {
        throw new Exception('Subject not found');
    }

    // Format the response data
    $response = [
        'status' => 'success',
        'data' => [
            'id' => $subject['id'],
            'subject_code' => $subject['subject_code'],
            'descriptive_title' => $subject['descriptive_title'],
            'prerequisite' => $subject['prerequisite'],
            'lec_units' => number_format($subject['lec_units'], 1),
            'lab_units' => number_format($subject['lab_units'], 1),
            'year_level' => $subject['year_level'],
            'semester' => $subject['semester']
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    error_log("Error in get_subject.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 