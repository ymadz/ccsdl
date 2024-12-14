<?php
if (!extension_loaded('json')) {
    echo "JSON extension is not enabled!";
    // Optionally, attempt to load it dynamically
    // dl('json.so'); // Uncomment this line if you are on Unix-based systems
} else {
    echo "JSON extension is enabled!";
}
?>

<?php
header('Content-Type: application/json');

require_once '../classes/course.class.php';
require_once '../classes/department.class.php';

try {
    $role_id = $_POST['role_id'] ?? null;
    
    if (!$role_id) {
        throw new Exception('Role ID is required');
    }

    if ($role_id == 3) { // Student
        $courseObj = new Course();
        $options = $courseObj->getAllCourses();
        $type = 'course';
    } elseif ($role_id == 2) { // Staff
        $departmentObj = new Department();
        $options = $departmentObj->getAllDepartments();
        $type = 'department';
    } else {
        $options = [];
        $type = '';
    }

    // Make sure json_encode exists
    if (!function_exists('json_encode')) {
        throw new Exception('JSON functionality is not available');
    }

    echo json_encode([
        'success' => true,
        'type' => $type,
        'options' => $options
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
