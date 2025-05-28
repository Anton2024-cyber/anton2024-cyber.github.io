<?php
session_start();
session_destroy();
header('Location: login.php'); // Перенаправление на страницу входа для администраторов
exit();
?>
