<?php
require_once "../../includes/authentication.php";
require_once "../../classes/database.class.php";

header('Content-Type: application/json');

if (!isset($_POST['year_level']) || !isset($_POST['semester'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

try {
    $db = new Database();
    
    // Debug the received parameters
    error_log('Received parameters: Year Level = ' . $_POST['year_level'] . ', Semester = ' . $_POST['semester']);
    
    // Let's see what tables are in your database
    $query = "SHOW TABLES LIKE '%prospectus%'";
    $tables = $db->fetchAll($query);
    error_log('Available tables: ' . print_r($tables, true));
    
    // Now let's see the structure of your prospectus table
    $query = "DESCRIBE bscs_prospectus";
    $columns = $db->fetchAll($query);
    error_log('Table structure: ' . print_r($columns, true));
    
    // Finally, let's try to fetch the subjects
    $query = "SELECT * FROM bscs_prospectus 
              WHERE year_level = ? AND semester = ? 
              ORDER BY subject_code";
    
    $params = [$_POST['year_level'], $_POST['semester']];
    $subjects = $db->fetchAll($query, $params);
    
    // Debug the results
    error_log('Query results: ' . print_r($subjects, true));
    
    if (empty($subjects)) {
        echo json_encode(['error' => 'No subjects found for Year ' . $_POST['year_level'] . ', ' . $_POST['semester'] . ' Semester']);
        exit;
    }
    
    echo json_encode($subjects);
} catch (Exception $e) {
    error_log('Database error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} 