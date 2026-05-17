<?php
// controllers/BrowseController.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/RestaurantModel.php';

// Show all restaurants
function showRestaurants() {
    $restaurants = getAllRestaurants();
    require __DIR__ . '/../views/browse/restaurants.php';
}

// Show single restaurant
function showRestaurant() {
    $id         = (int)($_GET['id'] ?? 0);
    $restaurant = getRestaurantById($id);

    if (!$restaurant) {
        setFlash('error', 'Restaurant not found.');
        redirect('index.php?page=restaurants');
    }

    $menuItems = getMenuItems($id);
    require __DIR__ . '/../views/browse/restaurant_detail.php';
}

// Show menu item detail
function showMenuItem() {
    $id       = (int)($_GET['id'] ?? 0);
    $menuItem = getMenuItemById($id);

    if (!$menuItem) {
        setFlash('error', 'Menu item not found.');
        redirect('index.php?page=restaurants');
    }

    $restaurant = getRestaurantById($menuItem['restaurant_id']);
    $reviews    = getReviewsForItem($id);
    require __DIR__ . '/../views/browse/menu_item_detail.php';
}