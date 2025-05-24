<?php
// Запускаем сессию
session_start();

// Подключаемся к базе данных
require_once 'db_connect.php';

// Проверяем авторизацию пользователя
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Пользователь не авторизован']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Создаем таблицу корзины, если она не существует
$create_cart_table = "CREATE TABLE IF NOT EXISTS cart (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    product_data TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_cart (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

mysqli_query($conn, $create_cart_table);

// Получаем JSON данные
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['action'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Неверный запрос']);
    exit;
}

$action = $input['action'];

switch ($action) {
    case 'save':
        // Сохранение корзины
        if (!isset($input['products'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Отсутствуют данные товаров']);
            exit;
        }
        
        $products_json = json_encode($input['products']);
        
        // Используем INSERT ... ON DUPLICATE KEY UPDATE для обновления или вставки
        $sql = "INSERT INTO cart (user_id, product_data) VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE product_data = VALUES(product_data), updated_at = CURRENT_TIMESTAMP";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "is", $user_id, $products_json);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Ошибка сохранения корзины']);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка подготовки запроса']);
        }
        break;
        
    case 'load':
        // Загрузка корзины
        $sql = "SELECT product_data FROM cart WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                $products = json_decode($row['product_data'], true);
                echo json_encode(['success' => true, 'products' => $products ?: []]);
            } else {
                echo json_encode(['success' => true, 'products' => []]);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка подготовки запроса']);
        }
        break;
        
    case 'clear':
        // Очистка корзины
        $sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Ошибка очистки корзины']);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка подготовки запроса']);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
        break;
}

// Закрываем соединение с базой данных
mysqli_close($conn);
?>