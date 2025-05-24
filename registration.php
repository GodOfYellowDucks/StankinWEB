<?php
// Подключаемся к базе данных
require_once 'db_connect.php';

// Инициализируем переменные
$error = '';
$success = '';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $about = isset($_POST['about']) ? trim($_POST['about']) : '';
    $agree = isset($_POST['agree']) ? true : false;
    
    // Валидация данных
    if (empty($firstname)) {
        $error = 'Пожалуйста, введите имя';
    } elseif (empty($lastname)) {
        $error = 'Пожалуйста, введите фамилию';
    } elseif (empty($email)) {
        $error = 'Пожалуйста, введите email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Пожалуйста, введите корректный email';
    } elseif (empty($username)) {
        $error = 'Пожалуйста, введите логин';
    } elseif (empty($password)) {
        $error = 'Пожалуйста, введите пароль';
    } elseif ($password !== $confirm_password) {
        $error = 'Пароли не совпадают';
    } elseif (!$agree) {
        $error = 'Вы должны согласиться с условиями пользовательского соглашения';
    } else {
        // Проверяем, существует ли уже пользователь с таким email или логином
        $check_sql = "SELECT * FROM users WHERE email = ? OR username = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "ss", $email, $username);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $user = mysqli_fetch_assoc($check_result);
            if ($user['email'] === $email) {
                $error = 'Пользователь с таким email уже существует';
            } else {
                $error = 'Пользователь с таким логином уже существует';
            }
        } else {
            // Хешируем пароль
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Подготавливаем SQL-запрос для вставки данных
            $sql = "INSERT INTO users (firstname, lastname, email, phone, username, password, gender, birthdate, city, about) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            
            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssssssss",
                    $firstname,
                    $lastname,
                    $email,
                    $phone,
                    $username,
                    $hashed_password,
                    $gender,
                    $birthdate,
                    $city,
                    $about
                );
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = 'Регистрация успешно завершена! Теперь вы можете войти в систему.';
                } else {
                    $error = 'Ошибка при регистрации: ' . mysqli_error($conn);
                }
                
                mysqli_stmt_close($stmt);
            } else {
                $error = 'Ошибка при подготовке запроса: ' . mysqli_error($conn);
            }
        }
        
        mysqli_stmt_close($check_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Диамант</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/privacy-modal.js"></script>
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
            <h1>Регистрация</h1>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success" style="color: green; font-weight: bold;"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="post" action="registration.php" class="message-form">
                <div>
                    <label for="firstname">Имя:</label>
                    <input type="text" id="firstname" name="firstname" required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
                </div>
                <div>
                    <label for="lastname">Фамилия:</label>
                    <input type="text" id="lastname" name="lastname" required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div>
                    <label for="phone">Телефон:</label>
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{11}" placeholder="89XXXXXXXXX" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
                <div>
                    <label for="username">Логин:</label>
                    <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div>
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <label for="confirm-password">Подтверждение пароля:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <div>
                    <label>Пол:</label>
                    <div>
                        <input type="radio" id="gender-male" name="gender" value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'male') ? 'checked' : ''; ?>>
                        <label for="gender-male">Мужской</label>
                    </div>
                    <div>
                        <input type="radio" id="gender-female" name="gender" value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'female') ? 'checked' : ''; ?>>
                        <label for="gender-female">Женский</label>
                    </div>
                </div>
                <div>
                    <label for="birthdate">Дата рождения:</label>
                    <input type="date" id="birthdate" name="birthdate" value="<?php echo isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : ''; ?>">
                </div>
                <div>
                    <label for="city">Город:</label>
                    <select id="city" name="city">
                        <option value="">Выберите город</option>
                        <option value="Moscow" <?php echo (isset($_POST['city']) && $_POST['city'] === 'Moscow') ? 'selected' : ''; ?>>Москва</option>
                        <option value="St-Petersburg" <?php echo (isset($_POST['city']) && $_POST['city'] === 'St-Petersburg') ? 'selected' : ''; ?>>Санкт-Петербург</option>
                        <option value="Ekaterinburg" <?php echo (isset($_POST['city']) && $_POST['city'] === 'Ekaterinburg') ? 'selected' : ''; ?>>Екатеринбург</option>
                        <option value="Pskov" <?php echo (isset($_POST['city']) && $_POST['city'] === 'Pskov') ? 'selected' : ''; ?>>Псков</option>
                        <option value="Tver" <?php echo (isset($_POST['city']) && $_POST['city'] === 'Tver') ? 'selected' : ''; ?>>Тверь</option>
                        <option value="Ryazan" <?php echo (isset($_POST['city']) && $_POST['city'] === 'Ryazan') ? 'selected' : ''; ?>>Рязань</option>
                        <option value="other" <?php echo (isset($_POST['city']) && $_POST['city'] === 'other') ? 'selected' : ''; ?>>Другой</option>
                    </select>
                </div>
                <div>
                    <label>Интересующие категории:</label>
                    <div>
                        <input type="checkbox" id="cat-rings" name="categories[]" value="rings" <?php echo (isset($_POST['categories']) && in_array('rings', $_POST['categories'])) ? 'checked' : ''; ?>>
                        <label for="cat-rings">Кольца</label>
                    </div>
                    <div>
                        <input type="checkbox" id="cat-earrings" name="categories[]" value="earrings" <?php echo (isset($_POST['categories']) && in_array('earrings', $_POST['categories'])) ? 'checked' : ''; ?>>
                        <label for="cat-earrings">Серьги</label>
                    </div>
                    <div>
                        <input type="checkbox" id="cat-pendants" name="categories[]" value="pendants" <?php echo (isset($_POST['categories']) && in_array('pendants', $_POST['categories'])) ? 'checked' : ''; ?>>
                        <label for="cat-pendants">Подвески</label>
                    </div>
                    <div>
                        <input type="checkbox" id="cat-bracelets" name="categories[]" value="bracelets" <?php echo (isset($_POST['categories']) && in_array('bracelets', $_POST['categories'])) ? 'checked' : ''; ?>>
                        <label for="cat-bracelets">Браслеты</label>
                    </div>
                </div>
                <div class="message-text">
                    <label for="about">О себе:</label>
                    <textarea id="about" name="about"><?php echo isset($_POST['about']) ? htmlspecialchars($_POST['about']) : ''; ?></textarea>
                </div>
                <div>
                    <input type="checkbox" id="agree" name="agree" required <?php echo (isset($_POST['agree'])) ? 'checked' : ''; ?>>
                    <label for="agree">Я согласен с условиями пользовательского соглашения и <a href="#" class="privacy-link">политикой конфиденциальности</a></label>
                </div>
                <div class="message-buttons">
                    <button type="submit">Зарегистрироваться</button>
                    <button type="reset">Очистить форму</button>
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