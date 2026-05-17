<?php
// models/RestaurantModel.php

require_once __DIR__ . '/../config/database.php';

// Get all restaurants
function getAllRestaurants() {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM restaurants ORDER BY created_at DESC");
    $restaurants = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $restaurants[] = $row;
    }
    return $restaurants;
}

// Get restaurant by ID
function getRestaurantById($id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM restaurants WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ?: null;
}

// Get menu items for a restaurant
function getMenuItems($restaurantId) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM menu_items WHERE restaurant_id = ? ORDER BY created_at DESC");
    mysqli_stmt_bind_param($stmt, "i", $restaurantId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $items;
}

// Get menu item by ID
function getMenuItemById($id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM menu_items WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ?: null;
}

// Get reviews for a menu item
function getReviewsForItem($menuItemId) {
    global $conn;
    $stmt = mysqli_prepare($conn, 
        "SELECT r.*, u.name AS reviewer_name 
         FROM reviews r 
         JOIN users u ON u.id = r.user_id 
         WHERE r.menu_item_id = ? 
         ORDER BY r.created_at DESC"
    );
    mysqli_stmt_bind_param($stmt, "i", $menuItemId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $reviews = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $reviews;
}