<?php
// Подключаемся к базе данных
require_once 'db_connect.php';
require_once 'products_functions.php';

// Инициализируем переменные
$search_q = '';
$category_filter = 'all';
$products = [];
$error = '';

// Проверяем, был ли отправлен поисковый запрос
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и очищаем поисковый запрос
    $search_q = isset($_POST['search_q']) ? trim($_POST['search_q']) : '';
    $category_filter = isset($_POST['category']) ? $_POST['category'] : 'all';
    
    // Если запрос не пустой, выполняем поиск
    if (!empty($search_q)) {
        $products = searchProducts($conn, $search_q, $category_filter);
    } else {
        $error = 'Пожалуйста, введите поисковый запрос';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты поиска - Диамант</title>
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
            <!-- Форма поиска -->
            <div class="search-form">
                <h2>Поиск товара</h2>
                <form name="f1" method="post" action="search.php">
                    <input type="search" name="search_q" placeholder="Введите название товара" value="<?php echo htmlspecialchars($search_q); ?>">
                    <select name="category">
                        <option value="all" <?php if($category_filter == 'all') echo 'selected'; ?>>Все категории</option>
                        <option value="rings" <?php if($category_filter == 'rings') echo 'selected'; ?>>Кольца</option>
                        <option value="earrings" <?php if($category_filter == 'earrings') echo 'selected'; ?>>Серьги</option>
                        <option value="other" <?php if($category_filter == 'other') echo 'selected'; ?>>Прочие украшения</option>
                    </select>
                    <input type="submit" value="Поиск">
                </form>
            </div>
            
            <h1>Результаты поиска</h1>
            
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php elseif (!empty($search_q)): ?>
                <?php if (empty($products)): ?>
                    <p>По вашему запросу ничего не найдено. Попробуйте другой поисковый запрос.</p>
                <?php else: ?>
                    <p>Результаты поиска для запроса "<?php echo htmlspecialchars($search_q); ?>":</p>
                    
                    <div class="catalog-items">
                        <?php foreach ($products as $product): ?>
                            <div class="catalog-item">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                                <p><?php echo htmlspecialchars($product['short_description']); ?></p>
                                <p class="price">Цена: <?php echo number_format($product['price'], 0, ',', ' '); ?> руб.</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p>Введите поисковый запрос для поиска товаров.</p>
            <?php endif; ?>
            
            <!-- Кнопка возврата к каталогу -->
            <div class="category-navigation" style="margin-top: 20px;">
                <a href="catalog.php">Вернуться к каталогу</a>
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
    </div>
</body>
</html>
<?php
// Закрываем соединение с базой данных
mysqli_close($conn);
?>