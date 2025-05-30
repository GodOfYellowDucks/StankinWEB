<?php
// Запускаем сессию
session_start();

// Подключаемся к базе данных
require_once 'db_connect.php';

// Создаем массив баннеров для слайдера
$banner_slides = [
    [
        'image' => 'images/banner_14febr.png',
        'title' => 'Подарки для любимых',
        'description' => 'Скидка 20% на желанные украшения',
        'link' => 'sale.html'
    ],
    [
        'image' => 'images/banner_sales55.png',
        'title' => 'Сезон ярких скидок',
        'description' => 'Скидки до 55% на все украшения',
        'link' => 'masterclass.html'
    ],
    [
        'image' => 'images/banner_Ust_Ilimsk.png',
        'title' => 'Новый магазин',
        'description' => 'Открытие магазина в Усть-Ильимске',
        'link' => 'gift.html'
    ]
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Диамант - ювелирный магазин</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/cookie-notice.js"></script>
    <script src="js/slider.js"></script>
    <script src="js/privacy-modal.js"></script>
    <script src="js/cart.js"></script>
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
            <!-- Слайдер баннеров -->
            <div class="slider">
                <?php foreach ($banner_slides as $index => $slide): ?>
                    <div class="item">
                        <a href="<?php echo htmlspecialchars($slide['link']); ?>">
                            <img src="<?php echo htmlspecialchars($slide['image']); ?>" alt="<?php echo htmlspecialchars($slide['title']); ?>">
                            <div class="slide-overlay">
                                <h3><?php echo htmlspecialchars($slide['title']); ?></h3>
                                <p><?php echo htmlspecialchars($slide['description']); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                
                <!-- Кнопки навигации -->
                <button class="previous" onclick="previousSlide()">&#10094;</button>
                <button class="next" onclick="nextSlide()">&#10095;</button>
            </div>
            
            <!-- Точки индикаторы -->
            <div class="slider-dots">
                <?php for ($i = 1; $i <= count($banner_slides); $i++): ?>
                    <span class="dot" onclick="currentSlide(<?php echo $i; ?>)"></span>
                <?php endfor; ?>
            </div>

            <h1>О нас</h1>

            <div class="content-with-image">
                <img src="images/main.png" alt="Менеджер" class="content-image">
                <div class="content-text">
                    <p>Ювелирный магазин "Диамант" - это место, где каждое украшение рассказывает свою историю. Мы предлагаем широкий ассортимент ювелирных изделий высочайшего качества.</p>

                    <p>Основанный в 2005 году, "Диамант" быстро стал одним из ведущих ювелирных магазинов. Мы гордимся нашей репутацией и стремимся предоставлять только лучшие украшения нашим клиентам.</p>

                    <p>Наши изделия создаются с учетом последних модных тенденций и с использованием традиционных ювелирных техник. Каждый камень и металл тщательно отбирается нашими специалистами.</p>
                </div>
            </div>

            <h2>История фирмы</h2>
            <p>История компании "Диамант" началась с небольшой мастерской, основанной талантливым ювелиром Алексеем Петровым. Благодаря уникальному подходу к созданию ювелирных изделий и внимательному отношению к каждому клиенту, компания быстро завоевала доверие и признание на рынке.</p>

            <h2>Количество магазинов</h2>
            <table>
                <tr>
                    <td colspan="2">2005-2009</td>
                    <td>2010-2024</td>
                    <td colspan="2">2025</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>15</td>
                    <td>20</td>
                    <td>25</td>
                    <td>41</td>
                </tr>
                <tr>
                    <td colspan="5">всего 102 магазина</td>
                </tr>
            </table>

            <h2>Ведем деятельность во многих городах</h2>
            <h3>Миллионники</h3>
            <ul class="marked">
                <li>Москва</li>
                <li>Санкт-Петербург</li>
                <li>Екатеринбург</li>
            </ul>

            <h3>Областные центры</h3>
            <ol class="numbered">
                <li>Псков</li>
                <li>Тверь</li>
                <li>Рязань</li>
            </ol>

            <h3>А также в нескольких областях</h3>
            <ol class="multilevel">
                <li>Нижегородская</li>
                <li>Псковская
                    <ol type="a">
                        <li>Невель</li>
                        <li>Великие Луки</li>
                    </ol>
                </li>
                <li>Костромская</li>
            </ol>
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
        <?php if (isset($_SESSION['user_id'])): ?>
            | <a href="#" class="privacy-link">Политика конфиденциальности</a>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
// Закрываем соединение с базой данных
mysqli_close($conn);
?>

            <h1>О нас</h1>

            <div class="content-with-image">
                <img src="images/main.png" alt="Менеджер" class="content-image">
                <div class="content-text">
                    <p>Ювелирный магазин "Диамант" - это место, где каждое украшение рассказывает свою историю. Мы предлагаем широкий ассортимент ювелирных изделий высочайшего качества.</p>

                    <p>Основанный в 2005 году, "Диамант" быстро стал одним из ведущих ювелирных магазинов. Мы гордимся нашей репутацией и стремимся предоставлять только лучшие украшения нашим клиентам.</p>

                    <p>Наши изделия создаются с учетом последних модных тенденций и с использованием традиционных ювелирных техник. Каждый камень и металл тщательно отбирается нашими специалистами.</p>
                </div>
            </div>

            <h2>История фирмы</h2>
            <p>История компании "Диамант" началась с небольшой мастерской, основанной талантливым ювелиром Алексеем Петровым. Благодаря уникальному подходу к созданию ювелирных изделий и внимательному отношению к каждому клиенту, компания быстро завоевала доверие и признание на рынке.</p>

            <h2>Количество магазинов</h2>
            <table>
                <tr>
                    <td colspan="2">2005-2009</td>
                    <td>2010-2024</td>
                    <td colspan="2">2025</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>15</td>
                    <td>20</td>
                    <td>25</td>
                    <td>41</td>
                </tr>
                <tr>
                    <td colspan="5">всего 102 магазина</td>
                </tr>
            </table>

            <h2>Ведем деятельность во многих городах</h2>
            <h3>Миллионники</h3>
            <ul class="marked">
                <li>Москва</li>
                <li>Санкт-Петербург</li>
                <li>Екатеринбург</li>
            </ul>

            <h3>Областные центры</h3>
            <ol class="numbered">
                <li>Псков</li>
                <li>Тверь</li>
                <li>Рязань</li>
            </ol>

            <h3>А также в нескольких областях</h3>
            <ol class="multilevel">
                <li>Нижегородская</li>
                <li>Псковская
                    <ol type="a">
                        <li>Невель</li>
                        <li>Великие Луки</li>
                    </ol>
                </li>
                <li>Костромская</li>
            </ol>
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
        <?php if (isset($_SESSION['user_id'])): ?>
            | <a href="#" class="privacy-link">Политика конфиденциальности</a>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
// Закрываем соединение с базой данных
mysqli_close($conn);
?>