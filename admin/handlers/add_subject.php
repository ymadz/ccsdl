<?php
require_once('../../classes/database.class.php');
session_start();

header('Content-Type: application/json');

try {
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $db = new Database();
    
    // Log the POST data
    error_log("POST data: " . print_r($_POST, true));
    
    // Validate input
    $required_fields = [
        'course',
        'subject_code', 
        'descriptive_title', 
        'lec_units', 
        'lab_units', 
        'total_units', 
        'year_level', 
        'semester',
        'school_year'
    ];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("The field '$field' is required");
        }
    }
    
    // Determine table based on course
    $course = $_POST['course'];
    $table = '';
    
    switch($course) {
        case 'BSCS':
            $table = 'bscs_prospectus';
            break;
        case 'BSIT':
            $table = 'bsit_prospectus';
            break;
        case 'ACT-AD':
            $table = 'act_ad_prospectus';
            break;
        case 'ACT-NW':
            $table = 'act_nw_prospectus';
            break;
        default:
            throw new Exception("Invalid course selected: " . $course);
    }
    
    // Log the query and parameters
    $query = "INSERT INTO $table (
                subject_code, 
                descriptive_title, 
                prerequisite, 
                lec_units, 
                lab_units, 
                total_units, 
                year_level, 
                semester,
                school_year
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $params = [
        $_POST['subject_code'],
        $_POST['descriptive_title'],
        $_POST['prerequisite'] ?? null,
        $_POST['lec_units'],
        $_POST['lab_units'],
        $_POST['total_units'],
        $_POST['year_level'],
        $_POST['semester'],
        $_POST['school_year']
    ];
    
    error_log("Query: " . $query);
    error_log("Parameters: " . print_r($params, true));
    
    $db->execute($query, $params);
    echo json_encode(['status' => 'success']);
    
} catch (Exception $e) {
    error_log("Error in add_subject.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'details' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
} 