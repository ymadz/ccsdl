<?php
require_once 'database.class.php';

class Department {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllDepartments() {
        try {
            $sql = "SELECT * FROM department ORDER BY department_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching departments: " . $e->getMessage());
            return [];
        }
    }
} 