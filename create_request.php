<?php
session_start();
require_once 'Database.php';
require_once 'Request.php';

if ($_SESSION['role'] !== 'student') {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['student_id'];
    $studentName = $_POST['student_name'];
    $description = $_POST['description'];

    $db = new Database();
    $requestHandler = new Request($db);

    if ($requestHandler->createRequest($studentId, $studentName, $description)) {
        header('Location: student_dashboard.php');
    } else {
        echo "Error creating request.";
    }
}
?>
