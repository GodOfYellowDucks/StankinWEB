<?php
// Запускаем сессию
session_start();

// Подключаемся к базе данных
require_once 'db_connect.php';
require_once 'products_functions.php';

// Проверяем авторизацию пользователя
$is_logged_in = isset($_SESSION['user_id']);

// Получаем товары из категории "earrings"
$products = getProductsByCategory($conn, 'earrings');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Серьги - Диамант</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/privacy-modal.js"></script>
    <script src="js/cart.js"></script>
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
            <h1>Серьги</h1>
            <hr>
            
            <!-- Навигация по категориям -->
            <div class="category-navigation">
                <a href="catalog.php">Назад к категориям</a>
            </div>

            <!-- Форма поиска -->
            <div class="search-form">
                <h2>Поиск товара</h2>
                <form name="f1" method="post" action="search.php">
                    <input type="search" name="search_q" placeholder="Введите название товара">
                    <select name="category">
                        <option value="all">Все категории</option>
                        <option value="rings">Кольца</option>
                        <option value="earrings" selected>Серьги</option>
                        <option value="other">Прочие украшения</option>
                    </select>
                    <input type="submit" value="Поиск">
                </form>
            </div>

            <!-- Товары категории "Серьги" -->
            <div class="catalog-items">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="catalog-item">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                            <p><?php echo htmlspecialchars($product['short_description']); ?></p>
                            <p class="price">Цена: <?php echo number_format($product['price'], 0, ',', ' '); ?> руб.</p>
                            
                            <?php if ($is_logged_in): ?>
                                <button class="add-to-cart-button" style="margin-top: 10px; background-color: #FF8000; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; width: 100%;">
                                    Добавить в корзину
                                </button>
                            <?php else: ?>
                                <p style="margin-top: 10px; font-size: 12px; color: #666; text-align: center;">
                                    <a href="login.php">Войдите</a>, чтобы добавить в корзину
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Товары не найдены</p>
                <?php endif; ?>
            </div>
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
        <?php if ($is_logged_in): ?>
            | <a href="#" class="privacy-link">Политика конфиденциальности</a>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
// Закрываем соединение с базой данных
mysqli_close($conn);
?>