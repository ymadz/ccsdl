<?php
require_once('../../classes/database.class.php');
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_GET['term']) || !isset($_GET['course'])) {
        throw new Exception('Missing required parameters');
    }

    $db = new Database();
    $term = $_GET['term'];
    $course = strtolower($_GET['course']);
    
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
    
    // Search for matching subjects
    $query = "SELECT DISTINCT subject_code, descriptive_title 
             FROM $table 
             WHERE subject_code LIKE ? 
             OR descriptive_title LIKE ? 
             LIMIT 10";
             
    $params = ["%$term%", "%$term%"];
    $results = $db->fetchAll($query, $params);
    
    // Format results for autocomplete
    $suggestions = array();
    foreach ($results as $row) {
        $suggestions[] = array(
            'value' => $row['subject_code'],
            'label' => $row['subject_code'] . ' - ' . $row['descriptive_title']
        );
    }
    
    echo json_encode($suggestions);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 