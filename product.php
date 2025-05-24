<?php
// Запускаем сессию
session_start();

// Подключаемся к базе данных
require_once 'db_connect.php';
require_once 'products_functions.php';

// Проверяем авторизацию пользователя
$is_logged_in = isset($_SESSION['user_id']);
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Получаем ID товара из URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Проверяем, что ID существует
if ($product_id <= 0) {
    header("Location: catalog.php");
    exit;
}

// Получаем данные товара
$product = getProductById($conn, $product_id);

// Если товар не найден, перенаправляем на каталог
if (!$product) {
    header("Location: catalog.php");
    exit;
}

// Получаем все изображения товара
$product_images = [];
if (!empty($product['images'])) {
    $product_images = $product['images'];
} else {
    // Если нет изображений в базе, используем основное изображение
    $product_images = [['image' => $product['image'], 'title' => $product['name']]];
}

// Получаем свойства товара из базы данных
$properties_sql = "SELECT * FROM product_properties WHERE product_id = ? ORDER BY property_name, property_price ASC";
$properties_stmt = mysqli_prepare($conn, $properties_sql);
mysqli_stmt_bind_param($properties_stmt, "i", $product_id);
mysqli_stmt_execute($properties_stmt);
$properties_result = mysqli_stmt_get_result($properties_stmt);

// Группируем свойства по имени для создания вариантов выбора
$grouped_properties = [];
while ($property = mysqli_fetch_assoc($properties_result)) {
    $name = $property['property_name'];
    if (!isset($grouped_properties[$name])) {
        $grouped_properties[$name] = [];
    }
    $grouped_properties[$name][] = $property;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Диамант</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/cookie-notice.js"></script>
    <script src="js/slider.js"></script>
    <script src="js/privacy-modal.js"></script>
    <script src="js/cart.js"></script>
    <script>
        // Обновление цены при выборе свойств
        function updatePrice() {
            let basePrice = <?php echo $product['price']; ?>;
            let additionalPrice = 0;
            
            // Получаем все выбранные селекты свойств
            const propertySelects = document.querySelectorAll('.property-select');
            propertySelects.forEach(select => {
                if (select.value) {
                    additionalPrice += parseFloat(select.value);
                }
            });
            
            // Обновляем цену на странице
            const totalPrice = basePrice + additionalPrice;
            document.getElementById('product-price').innerText = totalPrice.toLocaleString('ru-RU') + ' руб.';
        }
        
        // Инициализация галереи товара
        document.addEventListener('DOMContentLoaded', function() {
            initProductGallery();
        });
    </script>
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
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>

            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <!-- Галерея изображений товара -->
                <div class="product-gallery">
                    <div class="product-slider">
                        <?php foreach ($product_images as $index => $image): ?>
                            <div class="product-slide">
                                <img src="<?php echo htmlspecialchars($image['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     onclick="openFullImage('<?php echo htmlspecialchars($image['image']); ?>')">
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($product_images) > 1): ?>
                            <!-- Кнопки навигации -->
                            <button class="previous" onclick="previousProductSlide()">&#10094;</button>
                            <button class="next" onclick="nextProductSlide()">&#10095;</button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (count($product_images) > 1): ?>
                        <!-- Миниатюры -->
                        <div class="thumbnails">
                            <?php foreach ($product_images as $index => $image): ?>
                                <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" 
                                     onclick="currentProductSlide(<?php echo $index + 1; ?>)">
                                    <img src="<?php echo htmlspecialchars($image['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-details" style="flex: 1;">
                    <h2>Краткое описание товара</h2>
                    <p><?php echo htmlspecialchars($product['short_description']); ?></p>
                    <p class="product-price">Стоимость: <span id="product-price"><?php echo number_format($product['price'], 0, ',', ' '); ?></span> руб.</p>

                    <!-- Свойства товара с возможностью выбора -->
                    <?php if (!empty($grouped_properties)): ?>
                        <div class="product-properties">
                            <h2>Характеристики и варианты</h2>
                            <form id="product-options-form">
                                <?php foreach ($grouped_properties as $property_name => $properties): ?>
                                    <div class="property-group">
                                        <label for="property-<?php echo htmlspecialchars($property_name); ?>"><?php echo htmlspecialchars($property_name); ?>:</label>
                                        
                                        <?php if (count($properties) > 1): ?>
                                            <!-- Если у свойства есть варианты, показываем выпадающий список -->
                                            <select 
                                                id="property-<?php echo htmlspecialchars($property_name); ?>" 
                                                class="property-select" 
                                                onchange="updatePrice()"
                                            >
                                                <?php foreach ($properties as $property): ?>
                                                    <option 
                                                        value="<?php echo $property['property_price']; ?>"
                                                        <?php echo $property['property_price'] == 0 ? 'selected' : ''; ?>
                                                    >
                                                        <?php echo htmlspecialchars($property['property_value']); ?>
                                                        <?php if ($property['property_price'] > 0): ?>
                                                            (+<?php echo number_format($property['property_price'], 0, ',', ' '); ?> руб.)
                                                        <?php elseif ($property['property_price'] < 0): ?>
                                                            (<?php echo number_format($property['property_price'], 0, ',', ' '); ?> руб.)
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php else: ?>
                                            <!-- Если у свойства нет вариантов, просто показываем значение -->
                                            <span class="property-value"><?php echo htmlspecialchars($properties[0]['property_value']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </form>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Кнопка добавления в корзину -->
                    <?php if ($is_logged_in): ?>
                        <div class="add-to-cart">
                            <button class="add-to-cart-button">Добавить в корзину</button>
                        </div>
                    <?php else: ?>
                        <div class="add-to-cart">
                            <p>Для добавления товаров в корзину необходимо <a href="login.php">войти в систему</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="description">
                <h2>Подробное описание</h2>
                <p><?php echo htmlspecialchars($product['description']); ?></p>

                <?php if (!empty($product['meta_keywords'])): ?>
                    <h3>Особенности изделия</h3>
                    <ul>
                        <?php
                        $keywords = explode(',', $product['meta_keywords']);
                        foreach ($keywords as $keyword):
                            if (!empty(trim($keyword))):
                        ?>
                            <li><?php echo htmlspecialchars(trim($keyword)); ?></li>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Кнопка возврата к категории -->
            <div class="category-navigation" style="margin-top: 20px;">
                <?php
                $category_page = '';
                switch ($product['category']) {
                    case 'rings':
                        $category_page = 'category-rings.php';
                        $category_name = 'кольцам';
                        break;
                    case 'earrings':
                        $category_page = 'category-earrings.php';
                        $category_name = 'серьгам';
                        break;
                    case 'other':
                        $category_page = 'category-other.php';
                        $category_name = 'прочим украшениям';
                        break;
                    default:
                        $category_page = 'catalog.php';
                        $category_name = 'каталогу';
                }
                ?>
                <a href="<?php echo $category_page; ?>">Вернуться к <?php echo $category_name; ?></a>
            </div>

            <hr>
            <p>&copy; Все права защищены</p>
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