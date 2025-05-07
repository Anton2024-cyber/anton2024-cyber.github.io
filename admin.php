<?php
// Подключение к базе данных
$host = 'localhost'; // или ваш хост
$db = 'u68669';
$user = 'u68669'; // ваш пользователь
$pass = '5943600'; // ваш пароль

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// HTTP Basic Authentication
$valid_users = ['admin' => 'password123']; // Измените на свои учетные данные

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    !array_key_exists($_SERVER['PHP_AUTH_USER'], $valid_users) ||
    $valid_users[$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW']) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required.';
    exit;
}

// Обработка запросов на редактирование и удаление
$messages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete']) && isset($_POST['user_id'])) {
        $id = intval($_POST['user_id']);
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            $messages[] = "Пользователь #$id удален успешно.";
        } else {
            $messages[] = "Ошибка при удалении пользователя.";
        }
    } elseif (isset($_POST['edit']) && isset($_POST['user_id'])) {
        $id = intval($_POST['user_id']);
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];
        $bio = trim($_POST['bio']);
        $languages = json_encode($_POST['languages'] ?? []);
        $agreement = isset($_POST['agreement']) ? 1 : 0;

        $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, email = ?, birthday = ?, gender = ?, bio = ?, languages = ?, agreement = ? WHERE id = ?");
        if ($stmt->execute([$name, $phone, $email, $birthday, $gender, $bio, $languages, $agreement, $id])) {
            $messages[] = "Пользователь #$id обновлен успешно.";
        } else {
            $messages[] = "Ошибка при обновлении пользователя.";
        }
    }
}

// Получение данных пользователей
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Подсчет статистики по языкам программирования
$langs = ['C', 'C++', 'JavaScript', 'Python', 'Java', 'Haskell', 'Clojure', 'Prolog'];
$lang_stats = array_fill_keys($langs, 0);
foreach ($users as $user) {
    $user_languages = json_decode($user['languages'], true);
    foreach ($user_languages as $lang) {
        if (in_array($lang, $langs)) {
            $lang_stats[$lang]++;
        }
    }
}

// Включение HTML-шаблона
include 'admin_view.html';
?>
