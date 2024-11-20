<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'users_db');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch all requests
$result = $conn->query("SELECT * FROM file_requests ORDER BY created_at DESC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    // Update the status of the request
    $stmt = $conn->prepare("UPDATE file_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $request_id);

    if ($stmt->execute()) {
        $success_message = "Request has been updated.";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: admin_dashboard.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to the Admin Dashboard, <?php echo $_SESSION['username']; ?></h1>

        <h2>Pending File Requests</h2>
        <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['file_description']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="Approved" class="button">Approve</button>
                                    <button type="submit" name="action" value="Rejected" class="button">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No requests available.</p>
        <?php endif; ?>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
