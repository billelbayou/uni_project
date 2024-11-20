<?php
session_start();
header('Content-Type: application/json');

require_once 'Database.php';
require_once 'User.php';

$db = new Database();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginResult = $user->login($username, $password);

    if ($loginResult) {
        $_SESSION['user_id'] = $loginResult['id'];
        $_SESSION['username'] = $loginResult['username'];
        $_SESSION['role'] = $loginResult['role'];
        echo json_encode(['status' => 'success', 'role' => $loginResult['role']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
    }
}
?>
