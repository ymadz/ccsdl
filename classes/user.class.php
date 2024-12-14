<?php

require_once 'database.class.php';

class User
{
    public $identifier = '';
    public $firstname = '';
    public $middlename = '';
    public $lastname = '';
    public $course = '';
    public $department = '';

    public $email = '';

    protected $db;

    private $role_id;

    function __construct()
    {
        $this->db = new Database();
    }

    public function store()
    {
        try {
            $sql = "INSERT INTO user (
                identifier, 
                firstname, 
                middlename, 
                lastname, 
                email, 
                course_id, 
                department_id
            ) VALUES (
                :identifier,
                :firstname,
                :middlename,
                :lastname,
                :email,
                :course_id,
                :department_id
            )";

            $stmt = $this->db->connect()->prepare($sql);
            
            // Convert course/department to IDs based on role
            $course_id = ($this->role_id == 3) ? $this->course : null;
            $department_id = ($this->role_id == 2) ? $this->department : null;

            $stmt->bindParam(':identifier', $this->identifier);
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':middlename', $this->middlename);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->bindParam(':department_id', $department_id);

            $stmt->execute();
            
            // Get the last inserted ID from the PDO connection
            return $this->db->connect()->lastInsertId();
            
        } catch (PDOException $e) {
            error_log("Error in User::store: " . $e->getMessage());
            return false;
        }
    }

    public function getUserDetails($user_id) {
        try {
            $sql = "SELECT u.*, a.role_id, a.status, d.department_name 
                    FROM user u 
                    LEFT JOIN account a ON u.id = a.user_id 
                    LEFT JOIN department d ON u.department_id = d.id 
                    WHERE u.id = :user_id";
                    
            $stmt = $this->db->connect()->prepare($sql);
            
            // Add debug output
            error_log("Attempting to get user details for ID: " . $user_id);
            
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Add debug output
            if (!$result) {
                error_log("No user found with ID: " . $user_id);
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error in getUserDetails: " . $e->getMessage());
            return false;
        }
    }

    public function getCourseOptions() {
        try {
            $sql = "SELECT course_name as name FROM course ORDER BY course_name";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting course options: " . $e->getMessage());
            return [];
        }
    }

    public function getDepartmentOptions() {
        try {
            $sql = "SELECT department_name as name FROM department ORDER BY department_name";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting department options: " . $e->getMessage());
            return [];
        }
    }

    public function identifierExists($identifier) {
        try {
            $sql = "SELECT COUNT(*) FROM user WHERE identifier = :identifier";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':identifier', $identifier);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking identifier: " . $e->getMessage());
            return false;
        }
    }

    public function setRoleId($role_id) {
        $this->role_id = $role_id;
    }

    public function emailExists($email) {
        try {
            $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function validateIdentifier($identifier, $roleId) {
        $result = [
            'valid' => false,
            'message' => ''
        ];

        // Remove any whitespace
        $identifier = trim($identifier);

        // Basic ID number validation
        if (empty($identifier)) {
            $result['message'] = 'ID Number is required';
            return $result;
        }

        // Check if it contains only numbers and hyphens
        if (!preg_match('/^[\d-]+$/', $identifier)) {
            $result['message'] = 'ID Number must contain only numbers and hyphens';
            return $result;
        }

        // Check if identifier already exists
        try {
            if ($this->identifierExists($identifier)) {
                $result['message'] = 'This ID Number is already registered';
                return $result;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $result['message'] = 'Error checking identifier';
            return $result;
        }

        // If all validations pass
        $result['valid'] = true;
        return $result;
    }

    public function validateEmail($email) {
        $result = [
            'valid' => false,
            'message' => ''
        ];

        // Remove any whitespace
        $email = trim($email);

        // Basic email format validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['message'] = 'Please enter a valid email address';
            return $result;
        }

        // Check for WMSU domain
        if (!str_ends_with(strtolower($email), '@wmsu.edu.ph')) {
            $result['message'] = 'Please use a valid WMSU email address (@wmsu.edu.ph)';
            return $result;
        }

        // Check if email already exists
        try {
            if ($this->emailExists($email)) {
                $result['message'] = 'This email is already registered';
                return $result;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $result['message'] = 'Error checking email';
            return $result;
        }

        // If all validations pass
        $result['valid'] = true;
        return $result;
    }

    public function updateUser($data) {
        try {
            $sql = "UPDATE user SET 
                    identifier = :identifier,
                    firstname = :firstname,
                    middlename = :middlename,
                    lastname = :lastname,
                    email = :email,
                    course_id = :course_id,
                    department_id = :department_id
                    WHERE id = :id";

            $stmt = $this->db->connect()->prepare($sql);
            
            return $stmt->execute([
                ':id' => $data['id'],
                ':identifier' => $data['identifier'],
                ':firstname' => $data['firstname'],
                ':middlename' => $data['middlename'],
                ':lastname' => $data['lastname'],
                ':email' => $data['email'],
                ':course_id' => $data['course_id'],
                ':department_id' => $data['department_id']
            ]);
        } catch (PDOException $e) {
            error_log("Error in User::updateUser: " . $e->getMessage());
            throw new Exception("Failed to update user details");
        }
    }

}
