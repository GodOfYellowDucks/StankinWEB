<?php
// Создаем ассоциативный массив с товарами
$products = array(
    // Категория "Кольца"
    "Кольцо из золота с бриллиантами" => array(
        "id" => 1,
        "category" => "rings",
        "description" => "Элегантное кольцо с бриллиантами, выполненное из белого золота 585 пробы.",
        "price" => "17990",
        "image" => "images/ring_diamond.png",
        "link" => "product.html"
    ),
    "Кольцо из комбинированного золота с бриллиантами" => array(
        "id" => 2,
        "category" => "rings",
        "description" => "Комбинированное золото 585 пробы",
        "price" => "9990",
        "image" => "images/ring_1.png",
        "link" => "#"
    ),
    "Кольцо из золота" => array(
        "id" => 3,
        "category" => "rings",
        "description" => "Красное золото 585 пробы",
        "price" => "18750",
        "image" => "images/ring_2.png",
        "link" => "#"
    ),
    
    // Категория "Серьги"
    "Серьги из золота с бриллиантами" => array(
        "id" => 4,
        "category" => "earrings",
        "description" => "Роскошные серьги с бриллиантами. Классический дизайн.",
        "price" => "11990",
        "image" => "images/ear_diamond.png",
        "link" => "product1.html"
    ),
    "Серьги из комбинированного золота с бриллиантами" => array(
        "id" => 5,
        "category" => "earrings",
        "description" => "Комбинированное золото 585 пробы",
        "price" => "12990",
        "image" => "images/ear_1.png",
        "link" => "#"
    ),
    "Серьги из золота с бриллиантами" => array(
        "id" => 6,
        "category" => "earrings",
        "description" => "Красное золото 585 пробы",
        "price" => "16990",
        "image" => "images/ear_2.png",
        "link" => "#"
    ),
    
    // Категория "Прочие украшения"
    "Подвеска из золота с бриллиантом" => array(
        "id" => 7,
        "category" => "other",
        "description" => "Изящная подвеска с бриллиантом. Элегантное украшение.",
        "price" => "2790",
        "image" => "images/podv_diamond.png",
        "link" => "product2.html"
    ),
    "Браслет из золота" => array(
        "id" => 8,
        "category" => "other",
        "description" => "Стильный браслет из золота. Прекрасное дополнение к образу.",
        "price" => "5800",
        "image" => "images/braslet_gold.png",
        "link" => "product4.html"
    ),
    "Брошь из золочёного серебра с опалом, фианитами и эмалью" => array(
        "id" => 9,
        "category" => "other",
        "description" => "Золочёное серебро 925 пробы",
        "price" => "7700",
        "image" => "images/other_1.png",
        "link" => "#"
    )
);

// Инициализируем результаты по умолчанию всеми товарами
$results = $products;

// Проверяем, был ли отправлен поисковый запрос
if (isset($_POST['search_q'])) {
    // Получаем и очищаем поисковый запрос
    $search_q = trim($_POST['search_q']);
    
    // Если запрос не пустой, выполняем поиск
    if (!empty($search_q)) {
        // Сбрасываем результаты и выполняем поиск
        $results = array();
        
        foreach ($products as $name => $details) {
            // Приводим к нижнему регистру для регистронезависимого поиска
            if (strpos(mb_strtolower($name, 'UTF-8'), mb_strtolower($search_q, 'UTF-8')) !== false ||
                strpos(mb_strtolower($details['description'], 'UTF-8'), mb_strtolower($search_q, 'UTF-8')) !== false) {
                $results[$name] = $details;
            }
        }
    }
}

// Получаем поисковую категорию, если она задана
$category_filter = '';
if (isset($_POST['category']) && !empty($_POST['category'])) {
    $category_filter = $_POST['category'];
    
    // Если категория задана и это не поиск по всем категориям
    if ($category_filter != 'all') {
        // Фильтруем результаты по категории
        foreach ($results as $name => $details) {
            if ($details['category'] != $category_filter) {
                unset($results[$name]);
            }
        }
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
    <!-- Шапка сайта -->
    <div class="header">
        <img src="images/logo.png" alt="Логотип Диамант" class="logo">
        <div class="site-title">ДИАМАНТ</div>
        <div class="login-form">
            <div>логин: <input type="text"></div>
            <div>пароль: <input type="password"></div>
            <div class="login-buttons">
                <a href="registration.html">регистрация</a>
                <button>войти</button>
            </div>
        </div>
    </div>

    <!-- Главное меню -->
    <div class="main-menu">
        <a href="index.html">Главная</a>
        <a href="catalog.html">Каталог</a>
        <a href="contacts.html">Контакты</a>
    </div>

    <!-- Основной контент -->
    <div class="content">
        <!-- Боковое меню -->
        <div class="side-menu">
            <ul>
                <li><a href="index.html">О нас</a></li>
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
                    <input type="search" name="search_q" placeholder="Введите название товара" value="<?php echo isset($search_q) ? htmlspecialchars($search_q) : ''; ?>">
                    <select name="category">
                        <option value="all" <?php if(isset($category_filter) && $category_filter == 'all') echo 'selected'; ?>>Все категории</option>
                        <option value="rings" <?php if(isset($category_filter) && $category_filter == 'rings') echo 'selected'; ?>>Кольца</option>
                        <option value="earrings" <?php if(isset($category_filter) && $category_filter == 'earrings') echo 'selected'; ?>>Серьги</option>
                        <option value="other" <?php if(isset($category_filter) && $category_filter == 'other') echo 'selected'; ?>>Прочие украшения</option>
                    </select>
                    <input type="submit" value="Поиск">
                </form>
            </div>
            
            <h1>Результаты поиска</h1>
            
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php elseif (isset($search_q)): ?>
                <?php if (empty($results)): ?>
                    <p>По вашему запросу ничего не найдено. Попробуйте другой поисковый запрос.</p>
                <?php else: ?>
                    <p>Результаты поиска для запроса "<?php echo htmlspecialchars($search_q); ?>":</p>
                    
                    <div class="catalog-items">
                        <?php foreach ($results as $name => $details): ?>
                            <div class="catalog-item">
                                <img src="<?php echo $details['image']; ?>" alt="<?php echo htmlspecialchars($name); ?>">
                                <a href="<?php echo $details['link']; ?>"><?php echo htmlspecialchars($name); ?></a>
                                <p><?php echo htmlspecialchars($details['description']); ?></p>
                                <p class="price">Цена: <?php echo number_format($details['price'], 0, ',', ' '); ?> руб.</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p>Введите поисковый запрос для поиска товаров.</p>
            <?php endif; ?>
            
            <!-- Кнопка возврата к каталогу -->
            <div class="category-navigation" style="margin-top: 20px;">
                <a href="catalog.html">Вернуться к каталогу</a>
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