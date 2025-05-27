<?php
session_start();
header('Content-Type: application/json');

// Симуляция базы данных
$users = [];

// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Проверка авторизации
    if (isset($_SESSION['username'])) {
        // Обновление данных пользователя
        if (isset($data['name']) && isset($data['email'])) {
            $username = $_SESSION['username'];
            $users[$username]['name'] = $data['name'];
            $users[$username]['email'] = $data['email'];
            echo json_encode(['message' => 'Данные пользователя обновлены.']);
        } else {
            echo json_encode(['error' => 'Неверные данные.']);
        }
    } else {
        // Регистрация нового пользователя
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';

        if ($username && $password && $name && $email) {
            // Сохранение пользователя в "базу данных"
            $users[$username] = ['password' => $password, 'name' => $name, 'email' => $email];
            $_SESSION['username'] = $username; // Авторизация пользователя
            echo json_encode(['message' => 'Пользователь зарегистрирован.', 'profile' => "profile.php?user=$username"]);
        } else {
            echo json_encode(['error' => 'Все поля обязательны для заполнения.']);
        }
    }
} else {
    echo json_encode(['error' => 'Неверный метод запроса.']);
}
?>
