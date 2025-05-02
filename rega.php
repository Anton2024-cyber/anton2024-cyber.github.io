<?php
// Подключение к базе данных
$host = 'localhost';
$db = 'u68669';
$user = 'u68669'; // замените на ваше имя пользователя
$pass = '5943600'; // замените на ваш пароль

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Обработка данных формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $biography = $_POST['biography'];
    $programming_languages = implode(',', $_POST['programming_languages']);
    $consent = isset($_POST['consent']) ? 1 : 0;

    // Генерация логина и пароля
    $username = strtolower(substr($name, 0, 3) . rand(100, 999));
    $password = bin2hex(random_bytes(4)); // Генерация случайного пароля
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, phone, email, gender, birthdate, biography, programming_languages, password_hash, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    try {
        // Выполнение запроса
        $stmt->execute([$name, $phone, $email, $gender, $birthdate, $biography, $programming_languages, $password_hash, $username]);

        // Успешная регистрация
        echo "Регистрация прошла успешно!<br>";
        echo "Ваш логин: $username<br>";
        echo "Ваш пароль: $password<br>";
    } catch (PDOException $e) {
        echo "Ошибка: " . $e->getMessage();
    }
}
?>
