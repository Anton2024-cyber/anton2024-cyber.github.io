<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html'); // Перенаправление на страницу регистрации, если пользователь не авторизован
    exit();
}

$username = $_SESSION['username'];

// Подключение к базе данных
$servername = "localhost"; // или ваш сервер базы данных
$db_username = "u68669"; // ваш пользователь базы данных
$db_password = "5943600"; // ваш пароль базы данных
$dbname = "u68669"; // имя вашей базы данных

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных пользователя
$stmt = $conn->prepare("SELECT name, email FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
</head>
<body>
    <h1>Добро пожаловать, <?php echo htmlspecialchars($name); ?></h1>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
