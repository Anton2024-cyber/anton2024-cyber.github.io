<?php
session_start();
session_destroy();
header('Location: index.html'); // Перенаправление на страницу регистрации
exit();
?>