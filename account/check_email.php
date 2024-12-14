<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if JSON extension is loaded
if (!extension_loaded('json')) {
    die('JSON extension is not loaded');
}

// Required files
require_once '../classes/user.class.php';
require_once '../tools/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $userObj = new User();
    $email = clean_input($_POST['email']);
    
    // Check if email already exists in database
    $emailExists = $userObj->emailExists($email);
    
    // Validate email format
    $isValidFormat = filter_var($email, FILTER_VALIDATE_EMAIL);
    $isWmsuEmail = strpos(strtolower($email), '@wmsu.edu.ph') !== false;
    
    $result = [
        'valid' => false,
        'message' => ''
    ];
    
    if (!$isValidFormat) {
        $result['message'] = 'Please enter a valid email address';
    } elseif (!$isWmsuEmail) {
        $result['message'] = 'Please use a valid WMSU email address (@wmsu.edu.ph)';
    } elseif ($emailExists) {
        $result['message'] = 'This email is already registered';
    } else {
        $result['valid'] = true;
    }
    
    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}