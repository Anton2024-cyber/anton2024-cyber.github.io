<?php
session_start();
header('Content-Type: application/json');

// Получаем данные из POST-запроса
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// Проверка учетных данных
if ($username === 'admin' && $password === 'password') { // Замените на ваши реальные данные
    $_SESSION['admin_username'] = $username;
    echo json_encode(['message' => 'Успешный вход.']);
} else {
    echo json_encode(['error' => 'Неверный логин или пароль.']);
}
?>
