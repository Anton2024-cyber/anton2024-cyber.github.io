<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: index.php');
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
    <h1>Вы успешно зарегистрированы!</h1>
    <p>Ваш логин: <?php echo $_SESSION['username']; ?></p>
    <p>Ваш пароль: <?php echo $_SESSION['password']; ?></p>
    <a href="login.php">Войти</a>
</body>
</html>
