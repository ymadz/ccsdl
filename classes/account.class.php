<?php

require_once 'database.class.php';

class Account
{
    private $db;

    public $id = '';

    public $user_id = null; // Reference to the user (student, staff, admin)
    public $username = '';

    public $password = '';

    public $role_id = null;

    public $status = '';


    function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Store a new account in the database
     * 
     * @param int $user_id The ID of the user (student, staff, admin)
     * @return bool True on success, False on failure
     */
    public function store($user_id)
    {
        try {
            // Check if username already exists
            if ($this->usernameExist($this->username)) {
                throw new Exception("Username already exists.");
            }

            // Debug log
            error_log("Storing account for user_id: " . $user_id);
            error_log("Username: " . $this->username);
            
            // Insert account into the database
            $sql = "INSERT INTO account (user_id, username, password, role_id, status) 
                    VALUES (:user_id, :username, :password, :role_id, :status)";
            $query = $this->db->connect()->prepare($sql);

            // Bind parameters
            $params = [
                ':user_id' => $user_id,
                ':username' => $this->username,
                ':password' => $this->password, // Should already be hashed
                ':role_id' => $this->role_id,
                ':status' => 'Active'
            ];

            // Debug log
            error_log("Executing account creation with params: " . print_r($params, true));

            // Execute query
            $result = $query->execute($params);
            
            // Debug log
            error_log("Account creation result: " . ($result ? 'success' : 'failed'));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Database Error in account store: " . $e->getMessage());
            throw new Exception("Failed to create account: " . $e->getMessage());
        }
    }

    /**
     * Login function to authenticate a user
     *
     * @param string $email_or_username The email or username
     * @param string $password The raw password entered by the user
     * @return bool True if authentication succeeds, False otherwise
     */
    public function login($username_or_email, $password)
{
    try {
        $account = $this->fetch($username_or_email);
        
        if (!$account) {
            return false; // Account not found
        }
        
        // Verify hashed password
        if (!password_verify($password, $account['password'])) {
            return false; // Invalid password
        }
        
        return $account; // Successful login
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        throw $e;
    }
}


    /**
     * Fetch user data by username or email
     *
     * @param string $email_or_username The email or username
     * @return array|false User data on success, False otherwise
     */
    public function fetch($username_or_email)
{
    try {
        $sql = "SELECT a.*, u.firstname, u.lastname, r.name AS role_name 
                FROM account a
                JOIN user u ON a.user_id = u.id
                JOIN role r ON a.role_id = r.id
                WHERE a.username = :input OR u.email = :input";
                
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':input', $username_or_email, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in fetch: " . $e->getMessage());
        throw new Exception("Failed to fetch account details");
    }
}



    /**
     * Check if a username already exists
     */
    function usernameExist($username)
    {
        try {
            $sql = "SELECT COUNT(*) FROM account WHERE username = :username";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':username', $username);
            $query->execute();
            
            return $query->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking username existence: " . $e->getMessage());
            throw new Exception("Error checking username");
        }
    }

    /**
     * Check if an email already exists
     */
    function emailExist($email)
    {
        $sql = "SELECT COUNT(*) FROM user WHERE email = :email";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetchColumn() > 0;
    }

    /**
     * Fetch account details for a specific account
     *
     * @param string|int $identifier The username or ID of the account
     * @return array|false Account details on success, False otherwise
     */
    public function getAccountDetails($id)
    {
        try {
            // Query to fetch account details by ID or username
            $sql = "SELECT 
                        a.id AS account_id,
                        a.username,
                        a.role_id,
                        a.status,
                        a.created_at,
                        u.id AS user_id,
                        u.identifier,
                        u.firstname,
                        u.middlename,
                        u.lastname,
                        u.email,
                        u.course,
                        u.department
                    FROM account a
                    LEFT JOIN user u ON a.user_id = u.id
                    WHERE a.id = :id";

            // Prepare the query
            $query = $this->db->connect()->prepare($sql);

            // Bind parameters
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query
            $query->execute();

            // Fetch the account details
            return $query->fetch(PDO::FETCH_ASSOC); // Return a single row as an associative array
        } catch (PDOException $e) {
            // Log the error
            error_log("Failed to fetch account details: " . $e->getMessage());
            return false; // Return false on error
        }
    }

    public function getAccounts() {
        try {
            $sql = "SELECT a.*, u.first_name, u.last_name, r.name as role_name 
                    FROM account a 
                    JOIN user u ON a.user_id = u.id 
                    JOIN role r ON a.role_id = r.id";
                    
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to get accounts: " . $e->getMessage());
            throw new Exception("Failed to get accounts");
        }
    }

    public function updateAccount($data) {
        try {
            $sql = "UPDATE account SET 
                    username = :username,
                    role_id = :role_id
                    WHERE id = :id";
    
            $stmt = $this->db->connect()->prepare($sql);
            
            $params = [
                ':id' => $data['id'],
                ':username' => $data['username'],
                ':role_id' => $data['role_id']
            ];
    
            // Changed $query to $stmt
            if (!$stmt->execute($params)) {
                throw new Exception("Failed to update account");
            }
            return true;
        } catch (PDOException $e) {
            error_log("Error in Account::updateAccount: " . $e->getMessage());
            throw new Exception("Failed to update account details");
        }
    }


    public function deleteAccount($id) {
        try {
            // SQL query to delete the account and its related user
            $sql = "DELETE a, u 
                FROM account a
                JOIN user u ON a.user_id = u.id
                WHERE a.id = :id";

            $query = $this->db->connect()->prepare($sql);

            // Bind the account ID
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Failed to delete account: " . $e->getMessage());
            return false;
        }
    }

    public function add($data) {
        try {
            $conn = $this->db->connect();
            $conn->beginTransaction();
            
            // Insert user first
            $userSql = "INSERT INTO user (first_name, last_name, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($userSql);
            $stmt->execute([$data['first_name'], $data['last_name'], $data['email']]);
            $userId = $conn->lastInsertId();
            
            // Then insert account
            $accountSql = "INSERT INTO account (user_id, role_id, username, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($accountSql);
            $stmt->execute([
                $userId, 
                $data['role_id'], 
                $data['username'], 
                password_hash($data['password'], PASSWORD_BCRYPT)
            ]);
            
            $conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($conn) {
                $conn->rollBack();
            }
            error_log("Failed to add account: " . $e->getMessage());
            throw new Exception("Failed to add account");
        }
    }

    public function emailExists($email) {
        $sql = "SELECT id FROM accounts WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    public function saveResetToken($email, $token) {
        $sql = "UPDATE accounts SET reset_token = ?, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) 
                WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token, $email]);
    }

    public function resetPassword($username, $new_password) {
        try {
            // First check if username exists
            $check_sql = "SELECT id FROM account WHERE username = :username";
            $check_stmt = $this->db->connect()->prepare($check_sql);
            $check_stmt->bindParam(':username', $username);
            $check_stmt->execute();
            
            if ($check_stmt->fetch()) {
                // Username exists, proceed with password update
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                $update_sql = "UPDATE account SET password = :password WHERE username = :username";
                $update_stmt = $this->db->connect()->prepare($update_sql);
                $update_stmt->bindParam(':password', $hashed_password);
                $update_stmt->bindParam(':username', $username);
                return $update_stmt->execute();
            }
            
            return false; // Username not found
            
        } catch (Exception $e) {
            error_log("Reset password error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAccount($id) {
        try {
            $sql = "SELECT a.*, u.* 
                    FROM account a 
                    INNER JOIN user u ON a.user_id = u.id 
                    WHERE a.id = :id";
            
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                error_log("Failed to execute getAccount query for ID: " . $id);
                throw new Exception("Failed to get account details");
            }
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                error_log("No account found for ID: " . $id);
                throw new Exception("Account not found");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Database error in getAccount: " . $e->getMessage());
            throw new Exception("Failed to get account details");
        }
    }

}
