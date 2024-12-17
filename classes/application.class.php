<?php
require_once 'database.class.php';

class Application {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getApplicationDetails($id) {
        try {
            $sql = "SELECT sa.*, u.first_name, u.last_name, 
                          GROUP_CONCAT(s.subject_code) as subjects 
                   FROM student_applications sa
                   JOIN user u ON sa.user_id = u.id 
                   LEFT JOIN application_subjects s ON sa.id = s.application_id
                   WHERE sa.id = ?
                   GROUP BY sa.id";
            
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to get application details: " . $e->getMessage());
            throw new Exception("Failed to get application details");
        }
    }

    public function getPendingApplications() {
        try {
            $sql = "SELECT sa.*, CONCAT(u.first_name, ' ', u.last_name) as student_name 
                    FROM student_applications sa 
                    JOIN user u ON sa.user_id = u.id 
                    WHERE sa.status = 'Pending' 
                    ORDER BY sa.created_at DESC";
            
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to get pending applications: " . $e->getMessage());
            throw new Exception("Failed to get pending applications");
        }
    }

    public function processApplication($id, $status, $reason = null) {
        try {
            $this->db->connect()->beginTransaction();
            
            $sql = "UPDATE student_applications 
                   SET status = ?, 
                       processed_at = CURRENT_TIMESTAMP,
                       rejection_reason = ?
                   WHERE id = ?";
            
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute([$status, $reason, $id]);
            
            $this->db->connect()->commit();
            return true;
        } catch (Exception $e) {
            $this->db->connect()->rollBack();
            error_log("Failed to process application: " . $e->getMessage());
            throw new Exception("Failed to process application");
        }
    }

    public function getApprovedApplicationsByCourse() {
        try {
            $sql = "SELECT sa.*, 
                          CONCAT(u.first_name, ' ', u.last_name) as student_name,
                          c.course_name,
                          u.year_level
                   FROM student_applications sa 
                   JOIN user u ON sa.user_id = u.id 
                   JOIN course c ON u.course_id = c.id
                   WHERE sa.status = 'Approved'
                   ORDER BY c.course_name, u.year_level, sa.gwa ASC";
            
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to get approved applications: " . $e->getMessage());
            throw new Exception("Failed to get approved applications");
        }
    }

    public function getApplicationStats() {
        try {
            $sql = "SELECT 
                        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending,
                        SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) AS approved,
                        SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) AS rejected
                    FROM student_applications";
            
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch application stats: " . $e->getMessage());
            return ['pending' => 0, 'approved' => 0, 'rejected' => 0];
        }
    }

    public function getRecentApplications($limit = 5) {
        try {
            $sql = "SELECT sa.id, CONCAT(u.firstname, ' ', u.lastname) AS student_name, 
                           sa.status, sa.created_at
                    FROM student_applications sa
                    JOIN user u ON sa.user_id = u.id
                    ORDER BY sa.created_at DESC
                    LIMIT ?";
            
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch recent applications: " . $e->getMessage());
            return [];
        }
    }
    
    
}