<?php

require_once 'database.class.php';

class Role
{
    public $id = '';
    public $name = '';

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // Fetch and render all roles
    function renderAllRoles()
    {
        try {
            $connection = $this->db->connect();
            if (!$connection) {
                error_log("Error in Role::renderAllRoles: Database connection failed");
                return [];
            }
            
            $sql = "SELECT * FROM role;";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in Role::renderAllRoles: " . $e->getMessage());
            return [];
        }
    }

    public function getAllRoles() {
        try {
            $sql = "SELECT * FROM role ORDER BY name";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in Role::getAllRoles: " . $e->getMessage());
            return [];
        }
    }
}
