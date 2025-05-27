<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html'); // Перенаправление на страницу регистрации, если пользователь не авторизован
    exit();
}

$username = $_SESSION['username'];
// Симуляция получения данных пользователя
$users = []; // Здесь должна быть логика получения данных из базы данных
$userData = $users[$username];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
</head>
<body>
    <h1>Добро пожаловать, <?php echo htmlspecialchars($userData['name']); ?></h1>
    <p>Email: <?php echo htmlspecialchars($userData['email']); ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>