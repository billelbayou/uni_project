<?php
require_once 'Database.php';

class Request {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->conn;
    }

    public function createRequest($studentId, $studentName, $description) {
        $stmt = $this->db->prepare("INSERT INTO file_requests (student_id, student_name, file_description, status) VALUES (?, ?, ?, 'Pending')");
        $stmt->bind_param("iss", $studentId, $studentName, $description);
        return $stmt->execute();
    }

    public function getRequests($userId = null) {
        if ($userId) {
            $stmt = $this->db->prepare("SELECT * FROM file_requests WHERE student_id = ? ORDER BY created_at DESC");
            $stmt->bind_param("i", $userId);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM file_requests ORDER BY created_at DESC");
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateRequestStatus($requestId, $status) {
        $stmt = $this->db->prepare("UPDATE file_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $requestId);
        return $stmt->execute();
    }
}
?>
