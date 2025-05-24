<?php
// Включаем файл конфигурации
require_once 'config.php';

// Устанавливаем соединение с базой данных
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Проверяем соединение
if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Устанавливаем кодировку
mysqli_set_charset($conn, "utf8mb4");
?>