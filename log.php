<?php
session_start();
header('Content-Type: application/json');

// Подключение к базе данных
$servername = "localhost"; // Ваш сервер базы данных
$username = "u68669"; // Ваш пользователь базы данных
$password = "5943600"; // Ваш пароль базы данных
$dbname = "u68669"; // Имя вашей базы данных

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Регистрация нового пользователя
    $username = uniqid(); // Генерация уникального логина
    $password = password_hash(uniqid(), PASSWORD_DEFAULT); // Хеширование пароля
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $biography = $data['biography'] ?? '';

    if ($name && $email && $phone && $biography) {
        // Сохранение пользователя в базу данных
        $stmt = $conn->prepare("INSERT INTO users (username, password, name, email, phone, biography, consent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $consent = isset($data['consent']) ? 1 : 0; // Преобразование checkbox в 1 или 0
        $stmt->bind_param("ssssssi", $username, $password, $name, $email, $phone, $biography, $consent);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username; // Авторизация пользователя
            echo json_encode(['message' => 'Пользователь зарегистрирован.']);
        } else {
            echo json_encode(['error' => 'Ошибка регистрации: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['error' => 'Все поля обязательны для заполнения.']);
    }
} else {
    echo json_encode(['error' => 'Неверный метод запроса.']);
}

$conn->close(); // Закрытие соединения
?>
