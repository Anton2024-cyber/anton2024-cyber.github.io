<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html'); // Перенаправление на страницу регистрации, если пользователь не авторизован
    exit();
}

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

// Удаление пользователя
if (isset($_POST['delete_user'])) {
    $username_to_delete = $_POST['username'];
    $stmt = $conn->prepare("DELETE FROM users WHERE username=?");
    $stmt->bind_param("s", $username_to_delete);
    $stmt->execute();
}

// Получение всех пользователей
$result = $conn->query("SELECT username, name, email, phone, biography FROM users");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
</head>
<body>
    <h1>Панель администратора</h1>
    <table border="1">
        <tr>
            <th>Логин</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>Биография</th>
            <th>Действия</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo htmlspecialchars($row['biography']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
                    <button type="submit" name="delete_user">Удалить</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>

<?php
$conn->close(); // Закрытие соединения
?>
