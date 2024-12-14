<?php
require_once('../../classes/database.class.php');
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['account'])) {
        throw new Exception('Unauthorized access');
    }

    // Validate required fields
    $required_fields = ['id', 'subject_code', 'descriptive_title', 'lec_units', 'lab_units', 'year_level', 'semester'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            throw new Exception("Field '$field' is required");
        }
    }

    $db = new Database();
    
    // Get the active course from the form data
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

    // Prepare the update query
    $query = "UPDATE $table SET 
              subject_code = ?,
              descriptive_title = ?,
              prerequisite = ?,
              lec_units = ?,
              lab_units = ?,
              year_level = ?,
              semester = ?
              WHERE id = ?";

    $params = [
        strtoupper($_POST['subject_code']),
        $_POST['descriptive_title'],
        $_POST['prerequisite'] === 'None' ? null : $_POST['prerequisite'],
        floatval($_POST['lec_units']),
        floatval($_POST['lab_units']),
        intval($_POST['year_level']),
        $_POST['semester'],
        $_POST['id']
    ];

    // Execute the update
    $result = $db->execute($query, $params);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Subject updated successfully'
        ]);
    } else {
        throw new Exception('Failed to update subject');
    }

} catch (Exception $e) {
    error_log("Error in update_subject.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 