<?php
require_once "../includes/authentication.php";
require_once "../classes/database.class.php";
check_user_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new Database();
        $conn = $db->connect();
        
        // Start transaction
        $conn->beginTransaction();
        
        // Calculate total rating
        $totalRating = 0;
        $subjectCount = count($_POST['subjects']);
        foreach ($_POST['subjects'] as $subject) {
            $totalRating += floatval($subject['rating']);
        }
        $averageRating = $totalRating / $subjectCount;
        
        // Insert main application
        $sql = "INSERT INTO student_applications (user_id, school_year, semester, adviser, total_rating) 
                VALUES (:user_id, :school_year, :semester, :adviser, :total_rating)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':user_id' => $_SESSION['account']['user_id'],
            ':school_year' => $_POST['school_year'],
            ':semester' => $_POST['semester'],
            ':adviser' => $_POST['adviser'],
            ':total_rating' => $averageRating
        ]);
        
        $applicationId = $conn->lastInsertId();
        
        // Insert subjects
        $sql = "INSERT INTO application_subjects (application_id, subject_code, subject_name, is_laboratory, rating) 
                VALUES (:application_id, :subject_code, :subject_name, :is_laboratory, :rating)";
        
        $stmt = $conn->prepare($sql);
        
        foreach ($_POST['subjects'] as $subject) {
            $stmt->execute([
                ':application_id' => $applicationId,
                ':subject_code' => $subject['code'],
                ':subject_name' => $subject['name'],
                ':is_laboratory' => $subject['is_laboratory'],
                ':rating' => $subject['rating']
            ]);
        }
        
        // Commit transaction
        $conn->commit();
        
        header("Location: user.home.php?success=application_submitted");
        exit;
        
    } catch (Exception $e) {
        if (isset($conn)) {
            $conn->rollBack();
        }
        error_log($e->getMessage());
        header("Location: apply.php?error=submission_failed");
        exit;
    }
} 