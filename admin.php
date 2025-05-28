<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: login.php'); // Redirect to admin login page
    exit();
}

// Database connection
$servername = "localhost"; // Your database server
$db_username = "u68669"; // Your database username
$db_password = "5943600"; // Your database password
$dbname = "u68669"; // Your database name

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user deletion
if (isset($_POST['delete'])) {
    $id_to_delete = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id_to_delete);
    $stmt->execute();
}

// Fetch all users
$result = $conn->query("SELECT id, name, email, phone, biography FROM users");

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Biography</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo htmlspecialchars($row['biography']); ?></td>
            <td>
                <form method="POST" action="edit.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <button type="submit" name="edit">Edit</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>

<?php
$conn->close(); // Close connection
?>
