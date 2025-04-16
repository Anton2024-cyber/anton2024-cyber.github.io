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

        <label for="options">Выбирете язык:</label>
        <select id="options" name="options[]" multiple class="<?php echo isset($_COOKIE['options_error']) ? 'invalid' : ''; ?>">
            <option value="option1" <?php echo (isset($_COOKIE['options']) && in_array('option1', $_COOKIE['options'])) ? 'selected' : ''; ?>>C</option>
            <option value="option2" <?php echo (isset($_COOKIE['options']) && in_array('option2', $_COOKIE['options'])) ? 'selected' : ''; ?>>C++</option>
            <option value="option3" <?php echo (isset($_COOKIE['options']) && in_array('option3', $_COOKIE['options'])) ? 'selected' : ''; ?>>JavaScript</option>
            <option value="option4" <?php echo (isset($_COOKIE['options']) && in_array('option4', $_COOKIE['options'])) ? 'selected' : ''; ?>>PHP</option>
            <option value="option5" <?php echo (isset($_COOKIE['options']) && in_array('option5', $_COOKIE['options'])) ? 'selected' : ''; ?>>Python</option>
            <option value="option6" <?php echo (isset($_COOKIE['options']) && in_array('option6', $_COOKIE['options'])) ? 'selected' : ''; ?>>Java</option>
            <option value="option7" <?php echo (isset($_COOKIE['options']) && in_array('option7', $_COOKIE['options'])) ? 'selected' : ''; ?>>Haskel</option>
            <option value="option8" <?php echo (isset($_COOKIE['options']) && in_array('option8', $_COOKIE['options'])) ? 'selected' : ''; ?>>Clojure</option>
            <option value="option9" <?php echo (isset($_COOKIE['options']) && in_array('option9', $_COOKIE['options'])) ? 'selected' : ''; ?>>Prolog</option>
            <option value="option10" <?php echo (isset($_COOKIE['options']) && in_array('option10', $_COOKIE['options'])) ? 'selected' : ''; ?>>Scala</option>
        </select>
        <span class="error"><?php echo $_COOKIE['options_error'] ?? ''; ?></span>
        <br>
        <label>С контрактом ознакомлен(а):</label>
        <input type="checkbox" name="checkbox_field[]" value="checkbox1" <?php echo (isset($_COOKIE['checkboxes']) && in_array('checkbox1', $_COOKIE['checkboxes'])) ? 'checked' : ''; ?>>
        <span class="error"><?php echo $_COOKIE['checkbox_error'] ?? ''; ?></span>
        <br>

        <button type="submit" value="Отправить"> Сохранить</button>
    </form>
</body>
</html>
