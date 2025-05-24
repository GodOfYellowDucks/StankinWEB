<?php
// Подключаемся к базе данных
require_once 'db_connect.php';

// Массив с товарами из вашего существующего кода
$products = array(
    // Категория "Кольца"
    array(
        "manufacturer_id" => 1,
        "name" => "Кольцо из золота с бриллиантами",
        "alias" => "ring-diamond",
        "short_description" => "Элегантное кольцо с бриллиантами, выполненное из белого золота 585 пробы.",
        "description" => "Элегантное кольцо с бриллиантами, выполненное из белого золота 585 пробы.",
        "price" => 17990,
        "image" => "images/ring_diamond.png",
        "available" => 1,
        "meta_keywords" => "кольцо, бриллиант, золото, ювелирные изделия",
        "meta_description" => "Элегантное кольцо с бриллиантами, выполненное из белого золота 585 пробы.",
        "meta_title" => "Кольцо из золота с бриллиантами",
        "category" => "rings",
        "link" => "product.html"
    ),
    array(
        "manufacturer_id" => 1,
        "name" => "Кольцо из комбинированного золота с бриллиантами",
        "alias" => "ring-combined-gold",
        "short_description" => "Комбинированное золото 585 пробы",
        "description" => "Комбинированное золото 585 пробы",
        "price" => 9990,
        "image" => "images/ring_1.png",
        "available" => 1,
        "meta_keywords" => "кольцо, бриллиант, комбинированное золото",
        "meta_description" => "Комбинированное золото 585 пробы",
        "meta_title" => "Кольцо из комбинированного золота с бриллиантами",
        "category" => "rings",
        "link" => "#"
    ),
    array(
        "manufacturer_id" => 1,
        "name" => "Кольцо из золота",
        "alias" => "ring-gold",
        "short_description" => "Красное золото 585 пробы",
        "description" => "Красное золото 585 пробы",
        "price" => 18750,
        "image" => "images/ring_2.png",
        "available" => 1,
        "meta_keywords" => "кольцо, золото, красное золото",
        "meta_description" => "Красное золото 585 пробы",
        "meta_title" => "Кольцо из золота",
        "category" => "rings",
        "link" => "#"
    ),
    
    // Категория "Серьги"
    array(
        "manufacturer_id" => 2,
        "name" => "Серьги из золота с бриллиантами",
        "alias" => "earrings-diamond",
        "short_description" => "Роскошные серьги с бриллиантами. Классический дизайн.",
        "description" => "Роскошные серьги с бриллиантами. Классический дизайн.",
        "price" => 11990,
        "image" => "images/ear_diamond.png",
        "available" => 1,
        "meta_keywords" => "серьги, бриллиант, золото",
        "meta_description" => "Роскошные серьги с бриллиантами. Классический дизайн.",
        "meta_title" => "Серьги из золота с бриллиантами",
        "category" => "earrings",
        "link" => "product1.html"
    ),
    array(
        "manufacturer_id" => 2,
        "name" => "Серьги из комбинированного золота с бриллиантами",
        "alias" => "earrings-combined-gold",
        "short_description" => "Комбинированное золото 585 пробы",
        "description" => "Комбинированное золото 585 пробы",
        "price" => 12990,
        "image" => "images/ear_1.png",
        "available" => 1,
        "meta_keywords" => "серьги, бриллиант, комбинированное золото",
        "meta_description" => "Комбинированное золото 585 пробы",
        "meta_title" => "Серьги из комбинированного золота с бриллиантами",
        "category" => "earrings",
        "link" => "#"
    ),
    array(
        "manufacturer_id" => 2,
        "name" => "Серьги из золота с бриллиантами",
        "alias" => "earrings-gold-diamond",
        "short_description" => "Красное золото 585 пробы",
        "description" => "Красное золото 585 пробы",
        "price" => 16990,
        "image" => "images/ear_2.png",
        "available" => 1,
        "meta_keywords" => "серьги, бриллиант, красное золото",
        "meta_description" => "Красное золото 585 пробы",
        "meta_title" => "Серьги из золота с бриллиантами",
        "category" => "earrings",
        "link" => "#"
    ),
    
    // Категория "Прочие украшения"
    array(
        "manufacturer_id" => 3,
        "name" => "Подвеска из золота с бриллиантом",
        "alias" => "pendant-diamond",
        "short_description" => "Изящная подвеска с бриллиантом. Элегантное украшение.",
        "description" => "Изящная подвеска с бриллиантом. Элегантное украшение.",
        "price" => 2790,
        "image" => "images/podv_diamond.png",
        "available" => 1,
        "meta_keywords" => "подвеска, бриллиант, золото",
        "meta_description" => "Изящная подвеска с бриллиантом. Элегантное украшение.",
        "meta_title" => "Подвеска из золота с бриллиантом",
        "category" => "other",
        "link" => "product2.html"
    ),
    array(
        "manufacturer_id" => 3,
        "name" => "Браслет из золота",
        "alias" => "bracelet-gold",
        "short_description" => "Стильный браслет из золота. Прекрасное дополнение к образу.",
        "description" => "Стильный браслет из золота. Прекрасное дополнение к образу.",
        "price" => 5800,
        "image" => "images/braslet_gold.png",
        "available" => 1,
        "meta_keywords" => "браслет, золото",
        "meta_description" => "Стильный браслет из золота. Прекрасное дополнение к образу.",
        "meta_title" => "Браслет из золота",
        "category" => "other",
        "link" => "product4.html"
    ),
    array(
        "manufacturer_id" => 3,
        "name" => "Брошь из золочёного серебра с опалом, фианитами и эмалью",
        "alias" => "brooch-silver-gold",
        "short_description" => "Золочёное серебро 925 пробы",
        "description" => "Золочёное серебро 925 пробы",
        "price" => 7700,
        "image" => "images/other_1.png",
        "available" => 1,
        "meta_keywords" => "брошь, серебро, золочёное, опал, фианит, эмаль",
        "meta_description" => "Золочёное серебро 925 пробы",
        "meta_title" => "Брошь из золочёного серебра с опалом, фианитами и эмалью",
        "category" => "other",
        "link" => "#"
    )
);

// Дополним товары до 20, сохраняя стиль именования и структуру существующих товаров
$additional_products = array(
    // Дополнительные кольца
    array(
        "manufacturer_id" => 1,
        "name" => "Кольцо из золота с рубином",
        "alias" => "ring-ruby",
        "short_description" => "Элегантное кольцо с рубином, выполненное из золота 585 пробы.",
        "description" => "Элегантное кольцо с рубином, выполненное из золота 585 пробы.",
        "price" => 20990,
        "image" => "images/ring_diamond.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "кольцо, рубин, золото",
        "meta_description" => "Элегантное кольцо с рубином, выполненное из золота 585 пробы.",
        "meta_title" => "Кольцо из золота с рубином",
        "category" => "rings",
        "link" => "#"
    ),
    array(
        "manufacturer_id" => 1,
        "name" => "Кольцо из золота с сапфиром",
        "alias" => "ring-sapphire",
        "short_description" => "Элегантное кольцо с сапфиром, выполненное из золота 585 пробы.",
        "description" => "Элегантное кольцо с сапфиром, выполненное из золота 585 пробы.",
        "price" => 21500,
        "image" => "images/ring_1.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "кольцо, сапфир, золото",
        "meta_description" => "Элегантное кольцо с сапфиром, выполненное из золота 585 пробы.",
        "meta_title" => "Кольцо из золота с сапфиром",
        "category" => "rings",
        "link" => "#"
    ),
    array(
        "manufacturer_id" => 1,
        "name" => "Кольцо из золота с изумрудом",
        "alias" => "ring-emerald",
        "short_description" => "Элегантное кольцо с изумрудом, выполненное из золота 585 пробы.",
        "description" => "Элегантное кольцо с изумрудом, выполненное из золота 585 пробы.",
        "price" => 22990,
        "image" => "images/ring_2.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "кольцо, изумруд, золото",
        "meta_description" => "Элегантное кольцо с изумрудом, выполненное из золота 585 пробы.",
        "meta_title" => "Кольцо из золота с изумрудом",
        "category" => "rings",
        "link" => "#"
    ),
    
    // Дополнительные серьги
    array(
        "manufacturer_id" => 2,
        "name" => "Серьги из золота с рубинами",
        "alias" => "earrings-ruby",
        "short_description" => "Роскошные серьги с рубинами. Классический дизайн.",
        "description" => "Роскошные серьги с рубинами. Классический дизайн.",
        "price" => 13990,
        "image" => "images/ear_diamond.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "серьги, рубин, золото",
        "meta_description" => "Роскошные серьги с рубинами. Классический дизайн.",
        "meta_title" => "Серьги из золота с рубинами",
        "category" => "earrings",
        "link" => "#"
    ),
    array(
        "manufacturer_id" => 2,
        "name" => "Серьги из золота с сапфирами",
        "alias" => "earrings-sapphire",
        "short_description" => "Комбинированное золото 585 пробы с сапфирами",
        "description" => "Комбинированное золото 585 пробы с сапфирами",
        "price" => 14990,
        "image" => "images/ear_1.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "серьги, сапфир, комбинированное золото",
        "meta_description" => "Комбинированное золото 585 пробы с сапфирами",
        "meta_title" => "Серьги из золота с сапфирами",
        "category" => "earrings",
        "link" => "#"
    ),
    array(
        "manufacturer_id" => 2,
        "name" => "Серьги из золота с изумрудами",
        "alias" => "earrings-emerald",
        "short_description" => "Красное золото 585 пробы с изумрудами",
        "description" => "Красное золото 585 пробы с изумрудами",
        "price" => 15990,
        "image" => "images/ear_2.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "серьги, изумруд, красное золото",
        "meta_description" => "Красное золото 585 пробы с изумрудами",
        "meta_title" => "Серьги из золота с изумрудами",
        "category" => "earrings",
        "link" => "#"
    ),
    
    // Дополнительные прочие украшения
    array(
        "manufacturer_id" => 3,
        "name" => "Подвеска из золота с рубином",
        "alias" => "pendant-ruby",
        "short_description" => "Изящная подвеска с рубином. Элегантное украшение.",
        "description" => "Изящная подвеска с рубином. Элегантное украшение.",
        "price" => 3790,
        "image" => "images/podv_diamond.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "подвеска, рубин, золото",
        "meta_description" => "Изящная подвеска с рубином. Элегантное украшение.",
        "meta_title" => "Подвеска из золота с рубином",
        "category" => "other",
        "link" => "#"
    ),
    array(
        "manufacturer_id" => 3,
        "name" => "Браслет из золота с сапфиром",
        "alias" => "bracelet-gold-sapphire",
        "short_description" => "Стильный браслет из золота с сапфиром. Прекрасное дополнение к образу.",
        "description" => "Стильный браслет из золота с сапфиром. Прекрасное дополнение к образу.",
        "price" => 6800,
        "image" => "images/braslet_gold.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "браслет, золото, сапфир",
        "meta_description" => "Стильный браслет из золота с сапфиром. Прекрасное дополнение к образу.",
        "meta_title" => "Браслет из золота с сапфиром",
        "category" => "other",
        "link" => "#"
    ),
    array(
        "manufacturer_id" => 3,
        "name" => "Брошь из золочёного серебра с изумрудом",
        "alias" => "brooch-silver-gold-emerald",
        "short_description" => "Золочёное серебро 925 пробы с изумрудом",
        "description" => "Золочёное серебро 925 пробы с изумрудом",
        "price" => 8700,
        "image" => "images/other_1.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "брошь, серебро, золочёное, изумруд",
        "meta_description" => "Золочёное серебро 925 пробы с изумрудом",
        "meta_title" => "Брошь из золочёного серебра с изумрудом",
        "category" => "other",
        "link" => "#"
    ),
    array(
        "manufacturer_id" => 3,
        "name" => "Колье из золота с бриллиантами",
        "alias" => "necklace-gold-diamond",
        "short_description" => "Роскошное колье из золота с бриллиантами",
        "description" => "Роскошное колье из золота с бриллиантами",
        "price" => 29990,
        "image" => "images/podv_diamond.png", // Используем существующее изображение
        "available" => 1,
        "meta_keywords" => "колье, золото, бриллианты",
        "meta_description" => "Роскошное колье из золота с бриллиантами",
        "meta_title" => "Колье из золота с бриллиантами",
        "category" => "other",
        "link" => "#"
    )
);

// Объединяем основные и дополнительные товары
$all_products = array_merge($products, $additional_products);

// Функция для вставки товаров в базу данных
function insertProducts($conn, $products) {
    // Счетчик успешно добавленных товаров
    $success_count = 0;
    
    // Перебираем массив с товарами
    foreach ($products as $product) {
        // Подготавливаем SQL-запрос для вставки данных
        $sql = "INSERT INTO product (
                    manufacturer_id, 
                    name, 
                    alias, 
                    short_description, 
                    description, 
                    price, 
                    image, 
                    available, 
                    meta_keywords, 
                    meta_description, 
                    meta_title,
                    category
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";
        
        // Подготавливаем запрос
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            // Привязываем параметры к запросу
            mysqli_stmt_bind_param(
                $stmt,
                "issssdsisiss",
                $product['manufacturer_id'],
                $product['name'],
                $product['alias'],
                $product['short_description'],
                $product['description'],
                $product['price'],
                $product['image'],
                $product['available'],
                $product['meta_keywords'],
                $product['meta_description'],
                $product['meta_title'],
                $product['category']
            );
            
            // Выполняем запрос
            if (mysqli_stmt_execute($stmt)) {
                $success_count++;
                
                // Получаем ID последнего добавленного товара
                $product_id = mysqli_insert_id($conn);
                
                // Добавляем изображение в таблицу product_images
                $image_sql = "INSERT INTO product_images (product_id, image, title) VALUES (?, ?, ?)";
                $image_stmt = mysqli_prepare($conn, $image_sql);
                
                if ($image_stmt) {
                    mysqli_stmt_bind_param($image_stmt, "iss", $product_id, $product['image'], $product['name']);
                    mysqli_stmt_execute($image_stmt);
                    mysqli_stmt_close($image_stmt);
                }
            }
            
            // Закрываем подготовленный запрос
            mysqli_stmt_close($stmt);
        }
    }
    
    return $success_count;
}

// Проверяем, есть ли уже товары в базе данных
$check_sql = "SELECT COUNT(*) as count FROM product";
$result = mysqli_query($conn, $check_sql);
$row = mysqli_fetch_assoc($result);

if ($row['count'] > 0) {
    echo "<h2>В базе данных уже есть товары.</h2>";
    echo "<p>Всего товаров: " . $row['count'] . "</p>";
    echo "<p>Если вы хотите добавить товары повторно, сначала очистите таблицу.</p>";
    echo "<form method='post'>";
    echo "<input type='submit' name='clear_products' value='Очистить таблицу товаров и добавить товары заново'>";
    echo "</form>";
    
    // Если пользователь нажал кнопку очистки таблицы
    if (isset($_POST['clear_products'])) {
        // Очищаем таблицы
        mysqli_query($conn, "DELETE FROM product_images");
        mysqli_query($conn, "DELETE FROM product_properties");
        mysqli_query($conn, "DELETE FROM product");
        
        // Сбрасываем автоинкремент
        mysqli_query($conn, "ALTER TABLE product AUTO_INCREMENT = 1");
        mysqli_query($conn, "ALTER TABLE product_images AUTO_INCREMENT = 1");
        mysqli_query($conn, "ALTER TABLE product_properties AUTO_INCREMENT = 1");
        
        // Добавляем товары
        $success_count = insertProducts($conn, $all_products);
        
        echo "<h2>Таблицы успешно очищены и добавлены новые товары.</h2>";
        echo "<p>Добавлено товаров: " . $success_count . " из " . count($all_products) . "</p>";
    }
} else {
    // Добавляем товары
    $success_count = insertProducts($conn, $all_products);
    
    echo "<h2>Товары успешно добавлены в базу данных.</h2>";
    echo "<p>Добавлено товаров: " . $success_count . " из " . count($all_products) . "</p>";
}

// Закрываем соединение с базой данных
mysqli_close($conn);
?>