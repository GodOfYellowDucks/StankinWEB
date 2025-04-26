<?php
// Запускаем сессию
session_start();

// Подключаемся к базе данных
require_once 'db_connect.php';

// Инициализируем переменные
$error = '';
$success = '';

// Если пользователь уже авторизован, перенаправляем на главную страницу
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Проверяем авторизацию через куки
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    
    // Создаем таблицу remember_tokens, если она не существует
    $create_table = "CREATE TABLE IF NOT EXISTS remember_tokens (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        token VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY token (token),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    mysqli_query($conn, $create_table);
    
    // Ищем токен в базе данных
    $sql = "SELECT * FROM remember_tokens WHERE token = ? AND expires_at > NOW()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 1) {
        $token_data = mysqli_fetch_assoc($result);
        $user_id = $token_data['user_id'];
        
        // Получаем данные пользователя
        $user_sql = "SELECT * FROM users WHERE id = ?";
        $user_stmt = mysqli_prepare($conn, $user_sql);
        mysqli_stmt_bind_param($user_stmt, "i", $user_id);
        mysqli_stmt_execute($user_stmt);
        $user_result = mysqli_stmt_get_result($user_stmt);
        
        if (mysqli_num_rows($user_result) === 1) {
            $user = mysqli_fetch_assoc($user_result);
            
            // Авторизуем пользователя
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Сохраняем сессию в базе данных
            $session_id = session_id();
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            
            $session_sql = "INSERT INTO sessions (user_id, session_id, ip_address, user_agent) VALUES (?, ?, ?, ?)";
            $session_stmt = mysqli_prepare($conn, $session_sql);
            mysqli_stmt_bind_param($session_stmt, "isss", $user['id'], $session_id, $ip_address, $user_agent);
            mysqli_stmt_execute($session_stmt);
            mysqli_stmt_close($session_stmt);
            
            // Перенаправляем на главную страницу
            header("Location: index.php");
            exit;
        }
        
        mysqli_stmt_close($user_stmt);
    }
    
    mysqli_stmt_close($stmt);
}

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $remember_me = isset($_POST['remember_me']) ? true : false;
    
    // Валидация данных
    if (empty($username)) {
        $error = 'Пожалуйста, введите логин';
    } elseif (empty($password)) {
        $error = 'Пожалуйста, введите пароль';
    } else {
        // Ищем пользователя в базе данных
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Проверяем пароль
            if (password_verify($password, $user['password'])) {
                // Пароль верный, авторизуем пользователя
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Если выбрана опция "Запомнить меня"
                if ($remember_me) {
                    // Создаем таблицу remember_tokens, если она не существует
                    $create_table = "CREATE TABLE IF NOT EXISTS remember_tokens (
                        id INT(11) NOT NULL AUTO_INCREMENT,
                        user_id INT(11) NOT NULL,
                        token VARCHAR(255) NOT NULL,
                        expires_at DATETIME NOT NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (id),
                        UNIQUE KEY token (token),
                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
                    
                    mysqli_query($conn, $create_table);
                    
                    // Генерируем уникальный токен
                    $token = bin2hex(random_bytes(32));
                    
                    // Устанавливаем срок действия токена (30 дней)
                    $expires_at = date('Y-m-d H:i:s', strtotime('+30 days'));
                    
                    // Сохраняем токен в базе данных
                    $token_sql = "INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)";
                    $token_stmt = mysqli_prepare($conn, $token_sql);
                    mysqli_stmt_bind_param($token_stmt, "iss", $user['id'], $token, $expires_at);
                    mysqli_stmt_execute($token_stmt);
                    mysqli_stmt_close($token_stmt);
                    
                    // Устанавливаем куки на 30 дней
                    setcookie('remember_token', $token, time() + (86400 * 30), "/"); // 86400 = 1 день
                }
                
                // Сохраняем сессию в базе данных
                $session_id = session_id();
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                
                $session_sql = "INSERT INTO sessions (user_id, session_id, ip_address, user_agent) VALUES (?, ?, ?, ?)";
                $session_stmt = mysqli_prepare($conn, $session_sql);
                mysqli_stmt_bind_param($session_stmt, "isss", $user['id'], $session_id, $ip_address, $user_agent);
                mysqli_stmt_execute($session_stmt);
                mysqli_stmt_close($session_stmt);
                
                // Перенаправляем на главную страницу
                header("Location: index.php");
                exit;
            } else {
                $error = 'Неверный логин или пароль';
            }
        } else {
            $error = 'Неверный логин или пароль';
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Диамант</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>


    <!-- Основной контент -->
    <div class="content">
        <!-- Боковое меню -->
        <nav class="side-menu">
            <ul>
                <li><a href="index.php">О нас</a></li>
                <li><a href="#">История фирмы</a></li>
                <li><a href="#">Сотрудники</a></li>
            </ul>
        </nav>

        <!-- Основной контент -->
        <div class="main-content">
            <h1>Вход в систему</h1>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success" style="color: green; font-weight: bold;"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="post" action="login.php" class="message-form">
                <div>
                    <label for="username">Логин:</label>
                    <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div>
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <input type="checkbox" id="remember_me" name="remember_me">
                    <label for="remember_me">Запомнить меня</label>
                </div>
                <div class="message-buttons">
                    <button type="submit">Войти</button>
                </div>
                <div style="margin-top: 10px;">
                    <a href="registration.php">Зарегистрироваться</a>
                </div>
            </form>
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