<?php
session_start();
// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
// Генерация токена CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Подключение к базе данных
$host = 'localhost'; // или ваш хост
$db = 'u68669';
$user = 'u68669'; // ваш пользователь
$pass = '5943600'; // ваш пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
// Обрабатываем форму редактирования заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed.");
    }
    if (isset($_POST['edit'])) {
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];
        $bio = $_POST['bio'];
        $languages = json_encode($_POST['languages'] ?? []);
        $agreement = isset($_POST['agreement']) ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE applications SET name = ?, phone = ?, email = ?, birthday = ?, gender = ?, bio = ?, languages = ?, agreement = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $email, $birthday, $gender, $bio, $languages, $agreement, $user_id]);
        $_SESSION['messages'][] = "Заявка обновлена.";
    }
    // Обрабатываем форму удаления заявки
    if (isset($_POST['delete'])) {
        $user_id = $_POST['user_id'];
        // Удаляем заявку из базы данных
        $stmt = $pdo->prepare("DELETE FROM applications WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['messages'][] = "Заявка удалена.";
    }
// Включение HTML-шаблона
include 'admin_view.html';
?>
