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
  <!-- Include Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="text-primary">Admin Dashboard</h1>
      <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Logout</button>
      </form>
    </div>

    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Requests</h4>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead class="table-primary">
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
                <td>
                  <span class="badge 
                    <?= $row['status'] === 'Pending' ? 'bg-warning' : 
                        ($row['status'] === 'Approved' ? 'bg-success' : 'bg-danger') ?>">
                    <?= $row['status'] ?>
                  </span>
                </td>
                <td>
                  <form action="update_request.php" method="POST" class="d-flex gap-2">
                    <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                    <select name="status" class="form-select form-select-sm">
                      <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                      <option value="Approved" <?= $row['status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
                      <option value="Rejected" <?= $row['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Include Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
