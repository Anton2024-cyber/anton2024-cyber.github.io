<?php
session_start();
header('Content-Type: application/json');
&nbsp;
&nbsp;

// Подключение к базе данных
$servername = "localhost"; // или ваш сервер базы данных
$username = "u68669"; // ваш пользователь базы данных
$password = "5943600"; // ваш пароль базы данных
$dbname = "u68669"; // имя вашей базы данных
&nbsp;
&nbsp;

$conn = new mysqli($servername, $username, $password, $dbname);
&nbsp;
&nbsp;

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
&nbsp;
&nbsp;

// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
&nbsp;
&nbsp;

    // Проверка авторизации
    if (isset($_SESSION['username'])) {
        // Обновление данных пользователя
        if (isset($data['name']) && isset($data['email'])) {
            $username = $_SESSION['username'];
            $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE username=?");
            $stmt->bind_param("ssi", $data['name'], $data['email'], $username);
            $stmt->execute();
            echo json_encode(['message' => 'Данные пользователя обновлены.']);
        } else {
            echo json_encode(['error' => 'Неверные данные.']);
        }
    } else {
        // Регистрация нового пользователя
        $username = $data['username'] ?? '';
        $password = password_hash($data['password'] ?? '', PASSWORD_DEFAULT); // Хеширование пароля
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
&nbsp;
&nbsp;

        if ($username && $password && $name && $email) {
            // Сохранение пользователя в базу данных
            $stmt = $conn->prepare("INSERT INTO users (username, password, name, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $password, $name, $email);
            if ($stmt->execute()) {
                $_SESSION['username'] = $username; // Авторизация пользователя
                echo json_encode(['message' => 'Пользователь зарегистрирован.', 'profile' => "profile.php?user=$username"]);
            } else {
                echo json_encode(['error' => 'Ошибка регистрации: ' . $stmt->error]);
            }
        } else {
            echo json_encode(['error' => 'Все поля обязательны для заполнения.']);
        }
    }
} else {
    echo json_encode(['error' => 'Неверный метод запроса.']);
}
&nbsp;
&nbsp;

$conn->close(); // Закрытие соединения
?>
