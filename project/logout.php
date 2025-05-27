<?php
session_start();
session_destroy();
header('Location: project.html'); // Перенаправление на страницу регистрации
exit();
?>
