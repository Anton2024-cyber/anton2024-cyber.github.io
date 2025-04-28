<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: index.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Успех</title>
</head>
<body>
    <h1>Регистрация прошла успешно</h1>
    <p>Логин: <?php echo $_SESSION['username']; ?></p>
    <p>Пароль: <?php echo $_SESSION['password']; ?></p>
    <a href="login.php">Войти</a>
</body>
</html>
