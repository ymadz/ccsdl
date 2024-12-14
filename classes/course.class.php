<?php
require_once 'database.class.php';

class Course {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllCourses() {
        try {
            $sql = "SELECT * FROM course ORDER BY course_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching courses: " . $e->getMessage());
            return [];
        }
    }
} 