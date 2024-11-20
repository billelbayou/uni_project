<?php
session_start();

// Ensure the user is logged in as a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

$host = "localhost";  // Your database host
$username = "root";   // Your database username
$password = "";       // Your database password
$dbname = "users_db"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file_description = htmlspecialchars($_POST['file_description']);
    $student_id = $_SESSION['user_id'];
    $student_name = $_SESSION['username']; // Assuming you store the student's name in session

    // Insert request into the database
    $query = "INSERT INTO file_requests (student_id, student_name, file_description, status) 
              VALUES (?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $student_id, $student_name, $file_description);
    $stmt->execute();
    $stmt->close();
    $message = "Request submitted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

        <!-- Logout Button - Redirects to logout.php -->
        <a href="logout.php" class="logout-button">Logout</a>

        <h2>File Request Form</h2>
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="student_dashboard.php" method="POST">
            <label for="file_description">File Description:</label>
            <textarea name="file_description" id="file_description" required></textarea>
            <button type="submit">Submit Request</button>
        </form>

        <h2>Your File Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>File Description</th>
                    <th>Status</th>
                    <th>Requested On</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch the student's file requests
                $student_id = $_SESSION['user_id'];
                $query = "SELECT id, file_description, status, created_at 
                          FROM file_requests 
                          WHERE student_id = ? 
                          ORDER BY created_at DESC";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $student_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Display the requests
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['file_description']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
