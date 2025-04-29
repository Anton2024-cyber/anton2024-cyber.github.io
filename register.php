<?php
use \Firebase\JWT\JWT;

$host = 'localhost';
$db = 'u68669';
$user = 'u68669';
$pass = '5943600';

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $biography = $_POST['biography'];
    $programming_languages = implode(',', $_POST['programming_languages']);
    $consent = isset($_POST['consent']) ? 1 : 0;

    $username = uniqid('user_'); // Генерация уникального логина
    $password = bin2hex(random_bytes(4)); // Генерация случайного пароля
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Сохранение пользователя в базе данных
    $stmt = $conn->prepare("INSERT INTO user (username, password, name, phone, email, gender, birthdate, biography, programming_languages) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $hashedPassword, $name, $phone, $email, $gender, $birthdate, $biography, $programming_languages]);
$secretKey = 'jnhicsisubfb@ijngoi#fk'; // Секретный ключ для подписи JWT
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600; // Время жизни токена (1 час)

    $payload = [
        'iat' => $issuedAt,
        'exp' => $expirationTime,
        'username' => $username
    ];

    $jwt = JWT::encode($payload, $secretKey);

    // Вывод информации пользователю
    echo "Регистрация прошла успешно!<br>";
    echo "Логин: $username<br>";
    echo "Пароль: $password<br>";
    echo "Ваш JWT: $jwt<br>";
}
?>
