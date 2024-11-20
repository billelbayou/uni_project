<?php
session_start();
require_once 'Database.php';
require_once 'Request.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'];
    $status = $_POST['status'];

    $db = new Database();
    $requestHandler = new Request($db);

    if ($requestHandler->updateRequestStatus($requestId, $status)) {
        header('Location: admin_dashboard.php');
    } else {
        echo "Error updating request status.";
    }
}
?>
