<?php
session_start();
header('Content-Type: application/json');

// Database connection
$servername = "localhost"; // Your database server
$username = "u68669"; // Your database username
$password = "5943600"; // Your database password
$dbname = "u68669"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);
&nbsp;
&nbsp;

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if user is authorized
    if (isset($_SESSION['username'])) {
        // Update user data
        if (isset($data['name']) && isset($data['email']) && isset($data['phone']) && isset($data['biography'])) {
            $username = $_SESSION['username'];
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, biography=? WHERE username=?");
            $stmt->bind_param("ssssi", $data['name'], $data['email'], $data['phone'], $data['biography'], $username);
            $stmt->execute();
            echo json_encode(['message' => 'User  data updated.']);
        } else {
            echo json_encode(['error' => 'Invalid data.']);
        }
    } else {
        // Register new user
        $username = uniqid(); // Generate a unique username
        $password = password_hash(uniqid(), PASSWORD_DEFAULT); // Hash the password
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $phone = $data['phone'] ?? '';
        $biography = $data['biography'] ?? '';

        if ($name && $email && $phone && $biography) {
            // Save user to the database
            $stmt = $conn->prepare("INSERT INTO users (username, password, name, email, phone, biography, consent) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $consent = isset($data['consent']) ? 1 : 0; // Convert checkbox to 1 or 0
            $stmt->bind_param("ssssssi", $username, $password, $name, $email, $phone, $biography, $consent);
            if ($stmt->execute()) {
                $_SESSION['username'] = $username; // Authorize user
                echo json_encode(['message' => 'User  registered.', 'username' => $username, 'password' => uniqid(), 'profile' => "log.html?user=$username"]);
            } else {
                echo json_encode(['error' => 'Registration error: ' . $stmt->error]);
            }
        } else {
            echo json_encode(['error' => 'All fields are required.']);
        }
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}

$conn->close(); // Close connection
?>
