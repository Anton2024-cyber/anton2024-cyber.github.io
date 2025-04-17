<?php
session_start();

// Функция для очистки данных
function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

// Регулярные выражения для валидации
$namePattern = "/^[a-zA-Zа-яА-ЯёЁ\s]+$/u"; // Имя: только буквы и пробелы
$emailPattern = "/^[\w\.-]+@[\w\.-]+\.\w+$/"; // Email: стандартный формат
$phonePattern = "/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/"; // Номер телефона: +7 (999) 999-99-99
$datePattern = "/^(19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/"; // Дата рождения: YYYY-MM-DD
$bioPattern = "/^[a-zA-Zа-яА-ЯёЁ0-9\s.,!?;:'\"-]*$/u"; // Биография: буквы, цифры и некоторые знаки

$errors = [];
$name = cleanInput($_POST['name'] ?? '');
$email = cleanInput($_POST['email'] ?? '');
$phone = cleanInput($_POST['phone'] ?? '');
$date_of_birth = cleanInput($_POST['date_of_birth'] ?? '');
$gender = $_POST['gender'] ?? '';
$bio = cleanInput($_POST['bio'] ?? '');
$options = $_POST['options'] ?? [];
$agreement = isset($_POST['agreement']) ? true : false;

// Валидация имени
if (!preg_match($namePattern, $name)) {
    $errors['name'] = "Имя должно содержать буквы и пробелы.";
}

// Валидация email
if (!preg_match($emailPattern, $email)) {
    $errors['email'] = "Email должен содержать латинские буквы, @ и цифры.";
}

// Валидация номера телефона
if (!preg_match($phonePattern, $phone)) {
    $errors['phone'] = "Номер телефона в формате +7 (999) 999-99-99.";
}

// Валидация даты рождения
if (!preg_match($datePattern, $date_of_birth)) {
    $errors['date_of_birth'] = "Введите дату рождения.";
}

// Валидация пола
if (empty($gender)) {
    $errors['gender'] = "Выберите пол.";
}

// Валидация биографии
if (!preg_match($bioPattern, $bio)) {
    $errors['bio'] = "Биография может содержать только буквы, цифры и знаки препинания.";
}

// Валидация множественного выбора
if (empty($options)) {
    $errors['options'] = "Выберите хотя бы один язык программирования.";
}
if (!$agreement) {
    $errors['agreement'] = "Вы должны поставить галочку.";
}

// Если есть ошибки, сохраняем их в Cookies и перенаправляем обратно
if (!empty($errors)) {
    foreach ($errors as $field => $error) {
        setcookie("{$field}_error", $error, 0, "/");
    }
    setcookie("name", $name, 0, "/");
    setcookie("email", $email, 0, "/");
    setcookie("phone", $phone, 0, "/");
    setcookie("date_of_birth", $date_of_birth, 0, "/");
    setcookie("gender", $gender, 0, "/");
    setcookie("bio", $bio, 0, "/");
    setcookie('options', serialize($options), 0, "/");
    setcookie('agreement', $agreement ? '1' : '0', 0, "/");

    // Перенаправляем обратно на форму
    header("Location: index.php");
    exit();
}

// Если ошибок нет, сохраняем данные в Cookies на год
setcookie("name", $name, time() + (365 * 24 * 60 * 60), "/");
setcookie("email", $email, time() + (365 * 24 * 60 * 60), "/");
setcookie("phone", $phone, time() + (365 * 24 * 60 * 60), "/");
setcookie("date_of_birth", $date_of_birth, time() + (365 * 24 * 60 * 60), "/");
setcookie("gender", $gender, time() + (365 * 24 * 60 * 60), "/");
setcookie("bio", $bio, time() + (365 * 24 * 60 * 60), "/");
setcookie('options', serialize($options), time() + (365 * 24 * 60 * 60), "/");
setcookie('agreement', $agreement ? '1' : '0', time() + (365 * 24 * 60 * 60), "/");

// Успешная обработка
header("Location: success.php");
exit();
?>
