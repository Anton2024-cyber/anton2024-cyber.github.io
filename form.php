<?php
session_start();

// Удаляем сообщения об ошибках из Cookies
if (isset($_COOKIE['name_error'])) {
    setcookie('name_error', '', time() - 3600, '/');
}
if (isset($_COOKIE['email_error'])) {
    setcookie('email_error', '', time() - 3600, '/');
}
if (isset($_COOKIE['phone_error'])) {
    setcookie('phone_error', '', time() - 3600, '/');
}
if (isset($_COOKIE['date_of_birth_error'])) {
    setcookie('date_of_birth_error', '', time() - 3600, '/');
}
if (isset($_COOKIE['gender_error'])) {
    setcookie('gender_error', '', time() - 3600, '/');
}
if (isset($_COOKIE['bio_error'])) {
    setcookie('bio_error', '', time() - 3600, '/');
}
if (isset($_COOKIE['options_error'])) {
    setcookie('options_error', '', time() - 3600, '/');
}
if (isset($_COOKIE['agreement_error'])) {
    setcookie('agreement_error', '', time() - 3600, '/');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма</title>
    <style>
        .error { color: red; }
        .invalid { border: 1px solid red; }
    </style>
</head>
<body>
    <h1>Заполните форму</h1>
    <form method="POST" action="index.php">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_COOKIE['name'] ?? ''); ?>" class="<?php echo isset($_COOKIE['name_error']) ? 'invalid' : ''; ?>">
        <span class="error"><?php echo $_COOKIE['name_error'] ?? ''; ?></span>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_COOKIE['email'] ?? ''); ?>" class="<?php echo isset($_COOKIE['email_error']) ? 'invalid' : ''; ?>">
        <span class="error"><?php echo $_COOKIE['email_error'] ?? ''; ?></span>
        <br>

        <label for="phone">Номер телефона:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_COOKIE['phone'] ?? ''); ?>" class="<?php echo isset($_COOKIE['phone_error']) ? 'invalid' : ''; ?>">
        <span class="error"><?php echo $_COOKIE['phone_error'] ?? ''; ?></span>
        <br>

        <label for="date_of_birth">Дата рождения:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($_COOKIE['date_of_birth'] ?? ''); ?>" class="<?php echo isset($_COOKIE['date_of_birth_error']) ? 'invalid' : ''; ?>">
        <span class="error"><?php echo $_COOKIE['date_of_birth_error'] ?? ''; ?></span>
        <br>

        <label>Пол:</label>
        <input type="radio" name="gender" value="male" <?php echo (isset($_COOKIE['gender']) && $_COOKIE['gender'] == 'male') ? 'checked' : ''; ?>> Мужской
        <input type="radio" name="gender" value="female" <?php echo (isset($_COOKIE['gender']) && $_COOKIE['gender'] == 'female') ? 'checked' : ''; ?>> Женский
        <span class="error"><?php echo $_COOKIE['gender_error'] ?? ''; ?></span>
        <br>

        <label for="bio">Биография:</label>
        <textarea id="bio" name="bio" class="<?php echo isset($_COOKIE['bio_error']) ? 'invalid' : ''; ?>"><?php echo htmlspecialchars($_COOKIE['bio'] ?? ''); ?></textarea>
        <span class="error"><?php echo $_COOKIE['bio_error'] ?? ''; ?></span>
        <br>

        <label for="options">Выберите любимые языки программирования:</label>
<select id="options" name="options[]" multiple class="<?php echo isset($_COOKIE['options_error']) ? 'invalid' : ''; ?>">
            <option value="C" <?php echo (isset($_COOKIE['options']) && in_array('php', $_COOKIE['options'])) ? 'selected' : ''; ?>>C</option>
            <option value="C++" <?php echo (isset($_COOKIE['options']) && in_array('php', $_COOKIE['options'])) ? 'selected' : ''; ?>>C++</option>
            <option value="javascript" <?php echo (isset($_COOKIE['options']) && in_array('javascript', $_COOKIE['options'])) ? 'selected' : ''; ?>>JavaScript</option>
            <option value="php" <?php echo (isset($_COOKIE['options']) && in_array('php', $_COOKIE['options'])) ? 'selected' : ''; ?>>PHP</option>
            <option value="python" <?php echo (isset($_COOKIE['options']) && in_array('python', $_COOKIE['options'])) ? 'selected' : ''; ?>>Python</option>
            <option value="java" <?php echo (isset($_COOKIE['options']) && in_array('java', $_COOKIE['options'])) ? 'selected' : ''; ?>>Java</option>
            <option value="haskel" <?php echo (isset($_COOKIE['options']) && in_array('java', $_COOKIE['options'])) ? 'selected' : ''; ?>>Haskel</option>
            <option value="clojure" <?php echo (isset($_COOKIE['options']) && in_array('java', $_COOKIE['options'])) ? 'selected' : ''; ?>>Clojure</option>
            <option value="prolog" <?php echo (isset($_COOKIE['options']) && in_array('java', $_COOKIE['options'])) ? 'selected' : ''; ?>>Prolog</option>
            <option value="scala" <?php echo (isset($_COOKIE['options']) && in_array('java', $_COOKIE['options'])) ? 'selected' : ''; ?>>Scala</option>
        </select>
        <span class="error"><?php echo $_COOKIE['options_error'] ?? ''; ?></span>
        <br>

        <label>
            <input type="checkbox" name="agreement" <?php echo isset($_COOKIE['agreement']) ? 'checked' : ''; ?>> С контрактом ознакомлен (-а):
        </label>
        <span class="error"><?php echo $_COOKIE['agreement_error'] ?? ''; ?></span>
        <br>

        <input type="submit" value="Сохранить">
    </form>
</body>
</html>
