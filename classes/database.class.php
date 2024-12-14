<?php

// The Database class is designed to handle the connection to a MySQL database.
class Database
{
    // These are the properties that store the database connection details.
    private $host = 'localhost';      // The hostname of the database server.
    private $username = 'root';       // The username used to connect to the database.
    private $password = '';           // The password used to connect to the database (empty string means no password).
    private $database = 'ccs_ranking_db'; // The name of the database to connect to.

    protected $connection = null; // This property will hold the PDO connection object once connected.

    // The connect() method is used to establish a connection to the database.
    function connect()
    {
        try {
            if ($this->connection === null) {
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->database;
                $this->connection = new PDO($dsn, $this->username, $this->password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->connection;
        } catch(PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            return null;
        }
    }

    public function prepare($sql) {
        $connection = $this->connect();
        if ($connection === null) {
            throw new Exception("Database connection failed");
        }
        return $connection->prepare($sql);
    }

    public function execute($sql, $params = []) {
        try {
            $stmt = $this->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function fetch($sql, $params = []) {
        try {
            $stmt = $this->execute($sql, $params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function fetchAll($sql, $params = []) {
        try {
            $stmt = $this->execute($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}

// Uncomment the lines below to test the connection by creating an instance of the Database class and calling the connect() method.
// $obj = new Database();
// $obj->connect();