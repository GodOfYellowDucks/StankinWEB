<?php
// Подключаемся к базе данных
require_once 'db_connect.php';

// Проверяем наличие существующих свойств
$check_sql = "SELECT COUNT(*) as count FROM product_properties";
$result = mysqli_query($conn, $check_sql);
$row = mysqli_fetch_assoc($result);

if ($row['count'] > 0) {
    echo "<h2>В базе данных уже есть свойства товаров.</h2>";
    echo "<p>Всего свойств: " . $row['count'] . "</p>";
    echo "<p>Если вы хотите добавить свойства повторно, сначала очистите таблицу.</p>";
    echo "<form method='post'>";
    echo "<input type='submit' name='clear_properties' value='Очистить таблицу свойств и добавить свойства заново'>";
    echo "</form>";
    
    // Если пользователь нажал кнопку очистки таблицы
    if (isset($_POST['clear_properties'])) {
        // Очищаем таблицу
        mysqli_query($conn, "DELETE FROM product_properties");
        
        // Сбрасываем автоинкремент
        mysqli_query($conn, "ALTER TABLE product_properties AUTO_INCREMENT = 1");
        
        // Добавляем свойства
        add_properties($conn);
        
        echo "<h2>Таблица свойств успешно очищена и добавлены новые свойства.</h2>";
    }
} else {
    // Добавляем свойства
    add_properties($conn);
    
    echo "<h2>Свойства товаров успешно добавлены в базу данных.</h2>";
}

// Функция для добавления свойств товаров
function add_properties($conn) {
    // Получаем все товары из базы данных
    $products_sql = "SELECT id, name, category, price FROM product ORDER BY id";
    $products_result = mysqli_query($conn, $products_sql);
    
    // Массив с добавленными свойствами для подсчета
    $added_properties = 0;
    
    if (mysqli_num_rows($products_result) > 0) {
        while ($product = mysqli_fetch_assoc($products_result)) {
            $product_id = $product['id'];
            $category = $product['category'];
            $base_price = $product['price'];
            
            // Определяем свойства товара в зависимости от категории
            $properties = array();
            
            // Общие свойства для всех категорий
            $properties[] = array(
                "property_name" => "Материал",
                "property_value" => "Золото 585 пробы",
                "property_price" => 0
            );
            
            if (strpos(strtolower($product['name']), 'комбинирован') !== false) {
                $properties[] = array(
                    "property_name" => "Цвет металла",
                    "property_value" => "Комбинированное (белое и желтое)",
                    "property_price" => 0
                );
            } elseif (strpos(strtolower($product['name']), 'красное') !== false) {
                $properties[] = array(
                    "property_name" => "Цвет металла",
                    "property_value" => "Красное",
                    "property_price" => 0
                );
            } else {
                $properties[] = array(
                    "property_name" => "Цвет металла",
                    "property_value" => "Желтое",
                    "property_price" => 0
                );
            }
            
            // Добавляем свойства вставок, если они есть в названии
            if (strpos(strtolower($product['name']), 'бриллиант') !== false) {
                $properties[] = array(
                    "property_name" => "Вставка",
                    "property_value" => "Бриллиант",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Огранка",
                    "property_value" => "Круглая, 57 граней",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Чистота",
                    "property_value" => "VS1",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Цвет камня",
                    "property_value" => "G (бесцветный)",
                    "property_price" => 0
                );
            } elseif (strpos(strtolower($product['name']), 'рубин') !== false) {
                $properties[] = array(
                    "property_name" => "Вставка",
                    "property_value" => "Рубин",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Огранка",
                    "property_value" => "Овальная",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Цвет камня",
                    "property_value" => "Красный",
                    "property_price" => 0
                );
            } elseif (strpos(strtolower($product['name']), 'сапфир') !== false) {
                $properties[] = array(
                    "property_name" => "Вставка",
                    "property_value" => "Сапфир",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Огранка",
                    "property_value" => "Принцесса",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Цвет камня",
                    "property_value" => "Синий",
                    "property_price" => 0
                );
            } elseif (strpos(strtolower($product['name']), 'изумруд') !== false) {
                $properties[] = array(
                    "property_name" => "Вставка",
                    "property_value" => "Изумруд",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Огранка",
                    "property_value" => "Изумрудная",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Цвет камня",
                    "property_value" => "Зеленый",
                    "property_price" => 0
                );
            } elseif (strpos(strtolower($product['name']), 'опал') !== false) {
                $properties[] = array(
                    "property_name" => "Вставка",
                    "property_value" => "Опал",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Цвет камня",
                    "property_value" => "Переливающийся",
                    "property_price" => 0
                );
            } elseif (strpos(strtolower($product['name']), 'фианит') !== false) {
                $properties[] = array(
                    "property_name" => "Вставка",
                    "property_value" => "Фианит",
                    "property_price" => 0
                );
                $properties[] = array(
                    "property_name" => "Цвет камня",
                    "property_value" => "Прозрачный",
                    "property_price" => 0
                );
            }
            
            // Свойства, зависящие от категории
            if ($category == 'rings') {
                // Свойства для колец
                $properties[] = array(
                    "property_name" => "Вес",
                    "property_value" => rand(15, 30) / 10 . " г",
                    "property_price" => 0
                );
                
                // Добавляем размеры с разными ценами
                $ring_sizes = array(
                    array("16", -500),  // Меньший размер может быть немного дешевле
                    array("16.5", -300),
                    array("17", 0),     // Базовая цена
                    array("17.5", 0),   // Базовая цена
                    array("18", 200),   // Больший размер может быть немного дороже
                    array("18.5", 500),
                    array("19", 700)
                );
                
                foreach ($ring_sizes as $size) {
                    $properties[] = array(
                        "property_name" => "Размер",
                        "property_value" => $size[0],
                        "property_price" => $size[1]
                    );
                }
                
            } elseif ($category == 'earrings') {
                // Свойства для серег
                $properties[] = array(
                    "property_name" => "Вес пары",
                    "property_value" => rand(20, 50) / 10 . " г",
                    "property_price" => 0
                );
                
                $properties[] = array(
                    "property_name" => "Тип застежки",
                    "property_value" => "Английский замок",
                    "property_price" => 0
                );
                
                // Для некоторых серег добавим альтернативные застежки с разной ценой
                $earring_clasps = array(
                    array("Пусеты", -200),
                    array("Французский замок", 300),
                    array("Итальянский замок", 500)
                );
                
                foreach ($earring_clasps as $clasp) {
                    $properties[] = array(
                        "property_name" => "Тип застежки",
                        "property_value" => $clasp[0],
                        "property_price" => $clasp[1]
                    );
                }
                
            } elseif ($category == 'other') {
                // Свойства для прочих украшений
                if (strpos(strtolower($product['name']), 'подвеск') !== false) {
                    $properties[] = array(
                        "property_name" => "Вес",
                        "property_value" => rand(10, 25) / 10 . " г",
                        "property_price" => 0
                    );
                    
                    $properties[] = array(
                        "property_name" => "Длина цепочки",
                        "property_value" => "45 см",
                        "property_price" => 0
                    );
                    
                    // Добавляем разные длины цепочек с разными ценами
                    $chain_lengths = array(
                        array("40 см", -300),
                        array("50 см", 500),
                        array("55 см", 800),
                        array("60 см", 1200)
                    );
                    
                    foreach ($chain_lengths as $length) {
                        $properties[] = array(
                            "property_name" => "Длина цепочки",
                            "property_value" => $length[0],
                            "property_price" => $length[1]
                        );
                    }
                    
                } elseif (strpos(strtolower($product['name']), 'браслет') !== false) {
                    $properties[] = array(
                        "property_name" => "Вес",
                        "property_value" => rand(40, 80) / 10 . " г",
                        "property_price" => 0
                    );
                    
                    $properties[] = array(
                        "property_name" => "Длина",
                        "property_value" => "19 см",
                        "property_price" => 0
                    );
                    
                    // Добавляем разные длины браслетов с разными ценами
                    $bracelet_lengths = array(
                        array("17 см", -400),
                        array("18 см", -200),
                        array("20 см", 300),
                        array("21 см", 600)
                    );
                    
                    foreach ($bracelet_lengths as $length) {
                        $properties[] = array(
                            "property_name" => "Длина",
                            "property_value" => $length[0],
                            "property_price" => $length[1]
                        );
                    }
                    
                } elseif (strpos(strtolower($product['name']), 'брошь') !== false) {
                    $properties[] = array(
                        "property_name" => "Вес",
                        "property_value" => rand(30, 50) / 10 . " г",
                        "property_price" => 0
                    );
                    
                    $properties[] = array(
                        "property_name" => "Размер",
                        "property_value" => rand(20, 40) . "x" . rand(15, 30) . " мм",
                        "property_price" => 0
                    );
                } elseif (strpos(strtolower($product['name']), 'колье') !== false) {
                    $properties[] = array(
                        "property_name" => "Вес",
                        "property_value" => rand(80, 150) / 10 . " г",
                        "property_price" => 0
                    );
                    
                    $properties[] = array(
                        "property_name" => "Длина",
                        "property_value" => "45 см",
                        "property_price" => 0
                    );
                    
                    // Добавляем разные длины колье с разными ценами
                    $necklace_lengths = array(
                        array("42 см", -500),
                        array("50 см", 800),
                        array("55 см", 1500)
                    );
                    
                    foreach ($necklace_lengths as $length) {
                        $properties[] = array(
                            "property_name" => "Длина",
                            "property_value" => $length[0],
                            "property_price" => $length[1]
                        );
                    }
                }
            }
            
            // Добавляем каждое свойство в базу данных
            foreach ($properties as $property) {
                $sql = "INSERT INTO product_properties (product_id, property_name, property_value, property_price) 
                        VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                
                if ($stmt) {
                    mysqli_stmt_bind_param(
                        $stmt,
                        "issd",
                        $product_id,
                        $property['property_name'],
                        $property['property_value'],
                        $property['property_price']
                    );
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $added_properties++;
                    }
                    
                    mysqli_stmt_close($stmt);
                }
            }
            
            echo "<p>Добавлены свойства для товара: " . htmlspecialchars($product['name']) . "</p>";
        }
    }
    
    echo "<p>Всего добавлено свойств: " . $added_properties . "</p>";
}

// Закрываем соединение с базой данных
mysqli_close($conn);
?>