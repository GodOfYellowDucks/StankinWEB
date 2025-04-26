<?php
// Проверяем авторизацию пользователя
$is_logged_in = isset($_SESSION['user_id']);
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<!-- Шапка сайта -->
<div class="header">
    <img src="images/logo.png" alt="Логотип Диамант" class="logo">
    <div class="site-title">ДИАМАНТ</div>
    <div class="login-form">
        <?php if ($is_logged_in): ?>
            <div>
                <p>Привет, <?php echo htmlspecialchars($username); ?>!</p>
                <a href="logout.php">Выйти</a>
            </div>
        <?php else: ?>
            <form method="post" action="login.php">
                <div>логин: <input type="text" name="username"></div>
                <div>пароль: <input type="password" name="password"></div>
                <div class="login-remember">
                    <input type="checkbox" id="header_remember_me" name="remember_me">
                    <label for="header_remember_me">Запомнить меня</label>
                </div>
                <div class="login-buttons">
                    <a href="registration.php">регистрация</a>
                    <button type="submit">войти</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<!-- Главное меню -->
<nav class="main-menu">
    <a href="index.php">Главная</a>
    <a href="catalog.php">Каталог</a>
    <a href="contacts.php">Контакты</a>
</nav>