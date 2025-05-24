<?php
// Функции для работы с товарами

// Получение всех товаров
function getAllProducts($conn) {
    $sql = "SELECT * FROM product ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $products = [];
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Получаем изображение товара
            $image_sql = "SELECT * FROM product_images WHERE product_id = ? LIMIT 1";
            $image_stmt = mysqli_prepare($conn, $image_sql);
            mysqli_stmt_bind_param($image_stmt, "i", $row['id']);
            mysqli_stmt_execute($image_stmt);
            $image_result = mysqli_stmt_get_result($image_stmt);
            
            if (mysqli_num_rows($image_result) > 0) {
                $image_row = mysqli_fetch_assoc($image_result);
                $row['image'] = $image_row['image'];
            }
            
            $products[] = $row;
        }
    }
    
    return $products;
}

// Получение товаров по категории
function getProductsByCategory($conn, $category) {
    $sql = "SELECT * FROM product WHERE category = ? ORDER BY id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $products = [];
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Получаем изображение товара
            $image_sql = "SELECT * FROM product_images WHERE product_id = ? LIMIT 1";
            $image_stmt = mysqli_prepare($conn, $image_sql);
            mysqli_stmt_bind_param($image_stmt, "i", $row['id']);
            mysqli_stmt_execute($image_stmt);
            $image_result = mysqli_stmt_get_result($image_stmt);
            
            if (mysqli_num_rows($image_result) > 0) {
                $image_row = mysqli_fetch_assoc($image_result);
                $row['image'] = $image_row['image'];
            }
            
            $products[] = $row;
        }
    }
    
    mysqli_stmt_close($stmt);
    return $products;
}

// Получение товара по ID
function getProductById($conn, $id) {
    $sql = "SELECT * FROM product WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        // Получаем изображения товара
        $images_sql = "SELECT * FROM product_images WHERE product_id = ?";
        $images_stmt = mysqli_prepare($conn, $images_sql);
        mysqli_stmt_bind_param($images_stmt, "i", $id);
        mysqli_stmt_execute($images_stmt);
        $images_result = mysqli_stmt_get_result($images_stmt);
        
        $product['images'] = [];
        
        if (mysqli_num_rows($images_result) > 0) {
            while ($image_row = mysqli_fetch_assoc($images_result)) {
                $product['images'][] = $image_row;
            }
        }
        
        // Получаем свойства товара
        $properties_sql = "SELECT * FROM product_properties WHERE product_id = ?";
        $properties_stmt = mysqli_prepare($conn, $properties_sql);
        mysqli_stmt_bind_param($properties_stmt, "i", $id);
        mysqli_stmt_execute($properties_stmt);
        $properties_result = mysqli_stmt_get_result($properties_stmt);
        
        $product['properties'] = [];
        
        if (mysqli_num_rows($properties_result) > 0) {
            while ($property_row = mysqli_fetch_assoc($properties_result)) {
                $product['properties'][] = $property_row;
            }
        }
        
        mysqli_stmt_close($stmt);
        return $product;
    }
    
    mysqli_stmt_close($stmt);
    return false;
}

// Поиск товаров
function searchProducts($conn, $search_query, $category = null) {
    if ($category && $category != 'all') {
        $sql = "SELECT * FROM product WHERE 
                (name LIKE ? OR short_description LIKE ? OR description LIKE ?) 
                AND category = ? 
                ORDER BY id DESC";
        $stmt = mysqli_prepare($conn, $sql);
        $search_param = "%$search_query%";
        mysqli_stmt_bind_param($stmt, "ssss", $search_param, $search_param, $search_param, $category);
    } else {
        $sql = "SELECT * FROM product WHERE 
                name LIKE ? OR short_description LIKE ? OR description LIKE ? 
                ORDER BY id DESC";
        $stmt = mysqli_prepare($conn, $sql);
        $search_param = "%$search_query%";
        mysqli_stmt_bind_param($stmt, "sss", $search_param, $search_param, $search_param);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $products = [];
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Получаем изображение товара
            $image_sql = "SELECT * FROM product_images WHERE product_id = ? LIMIT 1";
            $image_stmt = mysqli_prepare($conn, $image_sql);
            mysqli_stmt_bind_param($image_stmt, "i", $row['id']);
            mysqli_stmt_execute($image_stmt);
            $image_result = mysqli_stmt_get_result($image_stmt);
            
            if (mysqli_num_rows($image_result) > 0) {
                $image_row = mysqli_fetch_assoc($image_result);
                $row['image'] = $image_row['image'];
            }
            
            $products[] = $row;
        }
    }
    
    mysqli_stmt_close($stmt);
    return $products;
}
?>