<?php
require_once "../../includes/authentication.php";
check_user_login();
require_once "../../classes/database.class.php";

header('Content-Type: application/json');

try {
    $db = new Database();
    
    $course = $_POST['course'] ?? '';
    $year_level = $_POST['year_level'] ?? '';
    $semester = $_POST['semester'] ?? '';

    if (!$course || !$year_level || !$semester) {
        throw new Exception('Missing required parameters');
    }

    // Determine which table to query based on course
    $table = strtolower(str_replace('-', '_', $course)) . '_prospectus';
    
    $query = "SELECT id, subject_code, descriptive_title, lec_units, lab_units 
              FROM {$table} 
              WHERE year_level = ? 
              AND semester = ? 
              ORDER BY subject_code";
              
    $subjects = $db->fetchAll($query, [$year_level, $semester]);

    echo json_encode(['subjects' => $subjects]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} 