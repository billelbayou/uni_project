<?php
session_start();
require_once 'Database.php';
require_once 'Request.php';

if ($_SESSION['role'] !== 'admin') {
  header('Location: login.html');
  exit();
}

$db = new Database();
$requestHandler = new Request($db);

// Fetch all requests
$requests = $requestHandler->getRequests();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    /* Add your admin dashboard styles here */
  </style>
</head>

<body>

  <h1>Admin Dashboard</h1>
  <form action="logout.php" method="POST" style="margin-bottom: 20px;">
    <button type="submit">Logout</button>
  </form>
  <table border="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>Student Name</th>
        <th>Description</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $requests->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['student_name']) ?></td>
          <td><?= htmlspecialchars($row['file_description']) ?></td>
          <td><?= $row['status'] ?></td>
          <td>
            <form action="update_request.php" method="POST">
              <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
              <select name="status">
                <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $row['status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Rejected" <?= $row['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
              </select>
              <button type="submit">Update</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>

</html>