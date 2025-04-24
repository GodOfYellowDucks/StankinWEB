<?php
// Создаем ассоциативный массив с товарами
$products = array(
    "Кольцо из золота с бриллиантами" => array(
        "id" => 1,
        "description" => "Элегантное кольцо из золота с бриллиантами круглой огранки, выполненное из белого золота 585 пробы.",
        "price" => "17990",
        "image" => "images/ring_diamond.png",
        "link" => "product.html"
    ),
    "Серьги из золота с бриллиантами" => array(
        "id" => 2,
        "description" => "Роскошные серьги из золота с бриллиантами. Классический дизайн, подходящий для любого случая.",
        "price" => "11990",
        "image" => "images/ear_diamond.png",
        "link" => "product1.html"
    ),
    "Подвеска из золота с бриллиантом" => array(
        "id" => 3,
        "description" => "Изящная подвеска из золота с бриллиантом. Элегантное украшение на каждый день.",
        "price" => "2790",
        "image" => "images/podv_diamond.png",
        "link" => "product2.html"
    ),
    "Браслет из золота" => array(
        "id" => 4,
        "description" => "Стильный браслет из золота. Прекрасное дополнение к любому образу.",
        "price" => "5800",
        "image" => "images/braslet_gold.png",
        "link" => "product4.html"
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
            // Используем strpos и strtolower вместо mb_strpos, чтобы избежать проблем с кодировкой
            if (strpos(mb_strtolower($name, 'UTF-8'), mb_strtolower($search_q, 'UTF-8')) !== false) {
                $results[$name] = $details;
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
            <?php endif; ?>
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