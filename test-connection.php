<?php
// Включаем файл подключения к базе данных
require_once 'db_connect.php';

// Если соединение установлено успешно
if ($conn) {
    echo "<h2>Подключение к базе данных успешно установлено!</h2>";
    
    // Проверяем, существуют ли созданные таблицы
    $tables = ["product", "product_properties", "product_images", "users", "sessions"];
    echo "<h3>Проверка таблиц в базе данных:</h3>";
    echo "<ul>";
    
    foreach ($tables as $table) {
        $query = "SHOW TABLES LIKE '$table'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<li>Таблица <strong>$table</strong> существует</li>";
        } else {
            echo "<li style='color: red;'>Таблица <strong>$table</strong> не найдена</li>";
        }
    }
    
    echo "</ul>";
    
    // Дополнительная информация о соединении
    echo "<h3>Информация о соединении:</h3>";
    echo "<p>Сервер MySQL: " . mysqli_get_host_info($conn) . "</p>";
    echo "<p>Версия MySQL: " . mysqli_get_server_info($conn) . "</p>";
    echo "<p>Имя базы данных: " . DB_NAME . "</p>";
    
} else {
    echo "<h2 style='color: red;'>Ошибка подключения к базе данных!</h2>";
    echo "<p>Ошибка: " . mysqli_connect_error() . "</p>";
}

// Закрываем соединение
mysqli_close($conn);
?>