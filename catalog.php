<?php
// Подключаемся к базе данных
require_once 'db_connect.php';
require_once 'products_functions.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог - Диамант</title>
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
            <!-- Добавляем форму поиска вверху страницы -->
            <div class="search-form">
                <h2>Поиск товара</h2>
                <form name="f1" method="post" action="search.php">
                    <input type="search" name="search_q" placeholder="Введите название товара">
                    <select name="category">
                        <option value="all">Все категории</option>
                        <option value="rings">Кольца</option>
                        <option value="earrings">Серьги</option>
                        <option value="other">Прочие украшения</option>
                    </select>
                    <input type="submit" value="Поиск">
                </form>
            </div>
            
            <h1>Каталог</h1>
            <hr>

            <!-- Категории товаров -->
            <div class="categories">
                <div class="category-item">
                    <a href="category-rings.php">
                        <img src="images/ring_diamond.png" alt="Кольца">
                        <h3>Кольца</h3>
                    </a>
                </div>

                <div class="category-item">
                    <a href="category-earrings.php">
                        <img src="images/ear_diamond.png" alt="Серьги">
                        <h3>Серьги</h3>
                    </a>
                </div>

                <div class="category-item">
                    <a href="category-other.php">
                        <img src="images/podv_diamond.png" alt="Прочие украшения">
                        <h3>Прочие украшения</h3>
                    </a>
                </div>
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