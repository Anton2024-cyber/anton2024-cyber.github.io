<?php
// Подключение к базе данных
$host = 'localhost';
$db = 'my_database';
$user = 'root'; // замените на ваше имя пользователя
$pass = ''; // замените на ваш пароль

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Подключение библиотеки для работы с JWT
function jwt_encode($payload, $secret) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $header = base64_encode($header);
    $payload = base64_encode(json_encode($payload));
    $signature = hash_hmac('sha256', "$header.$payload", $secret, true);
    $signature = base64_encode($signature);
    return "$header.$payload.$signature";
}

// Обработка данных формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Получение пользователя из базы данных
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Генерация JWT
        $secret = 'your_secret_key'; // Замените на ваш секретный ключ
        $payload = [
            'id' => $user['id'],
            'username' => $user['username'],
            'exp' => time() + 3600 // Время жизни токена (1 час)
        ];
        $jwt = jwt_encode($payload, $secret);

        echo "Аутентификация прошла успешно!<br>";
        echo "Ваш JWT: $jwt<br>";
    } else {
        echo "Неверный логин или пароль.";
    }
}
?>
