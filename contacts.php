<?php
// Запускаем сессию
session_start();

// Подключаемся к базе данных
require_once 'db_connect.php';

// Проверяем авторизацию пользователя
$is_logged_in = isset($_SESSION['user_id']);
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Инициализируем переменные
$message_error = '';
$message_success = '';
$feedback_error = '';
$feedback_success = '';

// Обработка формы отправки сообщения
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    // Получаем данные из формы сообщения
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Валидация данных
    if (empty($name)) {
        $message_error = 'Пожалуйста, введите имя';
    } elseif (empty($email)) {
        $message_error = 'Пожалуйста, введите email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_error = 'Пожалуйста, введите корректный email';
    } elseif (empty($subject)) {
        $message_error = 'Пожалуйста, введите тему сообщения';
    } elseif (empty($message)) {
        $message_error = 'Пожалуйста, введите текст сообщения';
    } else {
        // Создаем таблицу для сообщений, если она еще не существует
        $create_table_sql = "CREATE TABLE IF NOT EXISTS messages (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        mysqli_query($conn, $create_table_sql);
        
        // Сохраняем сообщение в базу данных
        $sql = "INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
            
            if (mysqli_stmt_execute($stmt)) {
                $message_success = 'Ваше сообщение успешно отправлено!';
                // Очищаем данные формы после успешной отправки
                $name = $email = $subject = $message = '';
            } else {
                $message_error = 'Ошибка при отправке сообщения: ' . mysqli_error($conn);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $message_error = 'Ошибка при подготовке запроса: ' . mysqli_error($conn);
        }
    }
}

// Обработка формы отзыва
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_feedback'])) {
    // Получаем данные из формы отзыва
    $visitor_name = isset($_POST['visitor_name']) ? trim($_POST['visitor_name']) : '';
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $liked = isset($_POST['liked']) ? $_POST['liked'] : array();
    $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';
    
    // Валидация данных
    if (empty($visitor_name)) {
        $feedback_error = 'Пожалуйста, введите ваше имя';
    } elseif ($rating < 1 || $rating > 5) {
        $feedback_error = 'Пожалуйста, выберите оценку от 1 до 5';
    } elseif (empty($feedback)) {
        $feedback_error = 'Пожалуйста, введите ваш отзыв';
    } else {
        // Создаем таблицу для отзывов, если она еще не существует
        $create_feedback_table = "CREATE TABLE IF NOT EXISTS feedback (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            rating INT(1) NOT NULL,
            liked TEXT,
            feedback TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        mysqli_query($conn, $create_feedback_table);
        
        // Сохраняем отзыв в базу данных
        $liked_str = !empty($liked) ? implode(', ', $liked) : '';
        
        $sql = "INSERT INTO feedback (name, rating, liked, feedback) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "siss", $visitor_name, $rating, $liked_str, $feedback);
            
            if (mysqli_stmt_execute($stmt)) {
                $feedback_success = 'Ваш отзыв успешно отправлен! Благодарим за обратную связь.';
                // Очищаем данные формы после успешной отправки
                $visitor_name = $rating = $liked = $feedback = '';
            } else {
                $feedback_error = 'Ошибка при отправке отзыва: ' . mysqli_error($conn);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $feedback_error = 'Ошибка при подготовке запроса: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - Диамант</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>



    <!-- Основной контент -->
    <div class="content">
        <!-- Боковое меню -->
        <div class="side-menu">
            <ul>
                <li><a href="index.php">О нас</a></li>
                <li><a href="#">История фирмы</a></li>
                <li><a href="#">Сотрудники</a></li>
            </ul>
        </div>

        <!-- Основной контент -->
        <div class="main-content">
            <h1>Напишите нам</h1>
            <?php if (!empty($message_error)): ?>
                <div class="error"><?php echo $message_error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($message_success)): ?>
                <div class="success" style="color: green; font-weight: bold;"><?php echo $message_success; ?></div>
            <?php endif; ?>
            
            <form method="post" action="contacts.php" class="message-form">
                <div>Имя <input type="text" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"></div>
                <div>Email <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"></div>
                <div>Тема <input type="text" name="subject" class="subject" value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>"></div>
                <div class="message-text">Текст обращения
                    <textarea name="message"><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                </div>
                <div class="message-buttons">
                    <button type="submit" name="send_message">отправить</button>
                </div>
            </form>
            
            <h1>Гостевая книга</h1>
            <?php if (!empty($feedback_error)): ?>
                <div class="error"><?php echo $feedback_error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($feedback_success)): ?>
                <div class="success" style="color: green; font-weight: bold;"><?php echo $feedback_success; ?></div>
            <?php endif; ?>
            
            <form method="post" action="contacts.php" class="message-form">
                <div>Ваше имя <input type="text" name="visitor_name" value="<?php echo isset($visitor_name) ? htmlspecialchars($visitor_name) : ''; ?>"></div>
                <div>
                    <label>Оценка нашего магазина:</label>
                    <div>
                        <input type="radio" name="rating" id="rating5" value="5" <?php echo (isset($rating) && $rating == 5) ? 'checked' : ''; ?>>
                        <label for="rating5">5 звёзд</label>
                    </div>
                    <div>
                        <input type="radio" name="rating" id="rating4" value="4" <?php echo (isset($rating) && $rating == 4) ? 'checked' : ''; ?>>
                        <label for="rating4">4 звезды</label>
                    </div>
                    <div>
                        <input type="radio" name="rating" id="rating3" value="3" <?php echo (isset($rating) && $rating == 3) ? 'checked' : ''; ?>>
                        <label for="rating3">3 звезды</label>
                    </div>
                    <div>
                        <input type="radio" name="rating" id="rating2" value="2" <?php echo (isset($rating) && $rating == 2) ? 'checked' : ''; ?>>
                        <label for="rating2">2 звезды</label>
                    </div>
                    <div>
                        <input type="radio" name="rating" id="rating1" value="1" <?php echo (isset($rating) && $rating == 1) ? 'checked' : ''; ?>>
                        <label for="rating1">1 звезда</label>
                    </div>
                </div>
                <div>
                    <label>Что вам понравилось (можно выбрать несколько):</label>
                    <div>
                        <input type="checkbox" id="service" name="liked[]" value="service" <?php echo (isset($liked) && is_array($liked) && in_array('service', $liked)) ? 'checked' : ''; ?>>
                        <label for="service">Обслуживание</label>
                    </div>
                    <div>
                        <input type="checkbox" id="assortment" name="liked[]" value="assortment" <?php echo (isset($liked) && is_array($liked) && in_array('assortment', $liked)) ? 'checked' : ''; ?>>
                        <label for="assortment">Ассортимент</label>
                    </div>
                    <div>
                        <input type="checkbox" id="prices" name="liked[]" value="prices" <?php echo (isset($liked) && is_array($liked) && in_array('prices', $liked)) ? 'checked' : ''; ?>>
                        <label for="prices">Цены</label>
                    </div>
                </div>
                <div class="message-text">
                    <label for="feedback">Ваш отзыв:</label>
                    <textarea id="feedback" name="feedback"><?php echo isset($feedback) ? htmlspecialchars($feedback) : ''; ?></textarea>
                </div>
                <div class="message-buttons">
                    <button type="submit" name="send_feedback">Отправить отзыв</button>
                    <button type="reset">Очистить форму</button>
                </div>
            </form>
            
            <h1>Адрес</h1>
            <p>8 800 600 01 10</p>
            <p>ТЦ Гелиос г. Королев, пр-кт Космонавтов, 20А</p>
            <p>email@email.com</p>

        </div>

        <!-- Баннеры -->
        <div class="banners">
            <div class="banner">
                <a href="sale.html">
                    <img src="images/banner_14febr.png" alt="Скидка">
                    <p>Подарки для любимых: скидка 20% на желанные украшения</p>
                </a>
            </div>

            <div class="banner">
                <a href="gift.html">
                    <img src="images/banner_Ust_Ilimsk.png" alt="Подарок">
                    <p>Открытие магазина в Усть-Ильимске</p>
                </a>
            </div>

            <div class="banner">
                <a href="masterclass.html">
                    <img src="images/banner_sales55.png" alt="Мастер-класс">
                    <p>Сезон ярких скидок (до -55%)</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Подвал сайта -->
    <div class="footer">
        &copy; Все права защищены
    </div>
</body>
</html>
<?php
// Закрываем соединение с базой данных
mysqli_close($conn);
?>