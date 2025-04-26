<?php
// Запускаем сессию
session_start();

// Подключаемся к базе данных
require_once 'db_connect.php';

// Если пользователь авторизован, удаляем сессию из базы данных
if (isset($_SESSION['user_id'])) {
    $session_id = session_id();
    
    $sql = "DELETE FROM sessions WHERE session_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Удаляем токен "Запомнить меня" из базы данных и куки, если он существует
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        
        $token_sql = "DELETE FROM remember_tokens WHERE token = ?";
        $token_stmt = mysqli_prepare($conn, $token_sql);
        mysqli_stmt_bind_param($token_stmt, "s", $token);
        mysqli_stmt_execute($token_stmt);
        mysqli_stmt_close($token_stmt);
        
        // Удаляем куки, устанавливая срок действия в прошлом
        setcookie('remember_token', '', time() - 3600, "/");
    }
}

// Уничтожаем все данные сессии
session_unset();
session_destroy();

// Перенаправляем на главную страницу
header("Location: index.php");
exit;
?>