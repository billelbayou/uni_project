<?php
session_start();
require_once 'Database.php';
require_once 'Request.php';

if ($_SESSION['role'] !== 'student') {
  header('Location: login.html');
  exit();
}

$db = new Database();
$requestHandler = new Request($db);

// Fetch the student's requests
$studentId = $_SESSION['user_id'];
$requests = $requestHandler->getRequests($studentId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <style>
    /* Add your student dashboard styles here */
  </style>
</head>

<body>
  <h1>Student Dashboard</h1>
  <form action="logout.php" method="POST" style="margin-bottom: 20px;">
    <button type="submit">Logout</button>
  </form>
  <form action="create_request.php" method="POST">
    <h3>Create New Request</h3>
    <input type="hidden" name="student_id" value="<?= $studentId ?>">
    <input type="hidden" name="student_name" value="<?= htmlspecialchars($_SESSION['username']) ?>">
    <textarea name="description" placeholder="Enter file description" required></textarea>
    <button type="submit">Submit Request</button>
  </form>

  <h3>Your Requests</h3>
  <table border="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>Description</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $requests->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['file_description']) ?></td>
          <td><?= $row['status'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>

</html>