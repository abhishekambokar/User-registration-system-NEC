<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $email, $password) {
        try {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO " . $this->table_name . " (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashed_password);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage(), 3, "../logs/error.log");
            return false;
        }
    }

    public function login($email, $password) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage(), 3, "../logs/error.log");
            return false;
        }
    }
}