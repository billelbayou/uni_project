<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->conn;
    }

    public function register($username, $email, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
        return $stmt->execute();
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id, $dbUsername, $dbPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $dbPassword)) {
            return ['id' => $id, 'username' => $dbUsername, 'role' => $role];
        }

        return false;
    }
}
?>
