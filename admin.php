<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: login.php'); // Перенаправление на страницу входа для администраторов
    exit();
}

// Подключение к базе данных
$servername = "localhost"; // Ваш сервер базы данных
$db_username = "u68669"; // Ваш пользователь базы данных
$db_password = "5943600"; // Ваш пароль базы данных
$dbname = "u68669"; // Имя вашей базы данных

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Удаление записи
if (isset($_POST['delete'])) {
    $id_to_delete = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id_to_delete);
    $stmt->execute();
}

// Получение всех записей
$result = $conn->query("SELECT id, name, email, phone, biography FROM users");

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
</head>
<body>
    <h1>Панель администратора</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>Биография</th>
            <th>Действия</th>
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
                    <button type="submit" name="edit">Редактировать</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <button type="submit" name="delete">Удалить</button>
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
