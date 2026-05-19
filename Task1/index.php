<?php
// index.php - Front Controller

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Try remember-me auto login
require_once __DIR__ . '/controllers/AuthController.php';
tryRememberMe();

$page   = $_GET['page']   ?? 'home';
$method = $_SERVER['REQUEST_METHOD'];

switch ($page) {

    case 'register':
        if ($method === 'POST') handleRegister();
        else                    showRegister();
        break;

    case 'login':
        if ($method === 'POST') handleLogin();
        else                    showLogin();
        break;

    case 'logout':
        handleLogout();
        break;

    case 'profile':
        require_once __DIR__ . '/controllers/ProfileController.php';
        if ($method === 'POST') handleProfile();
        else                    showProfile();
        break;

    case 'restaurants':
        require_once __DIR__ . '/controllers/BrowseController.php';
        showRestaurants();
        break;

    case 'restaurant':
        require_once __DIR__ . '/controllers/BrowseController.php';
        showRestaurant();
        break;

    case 'menu_item':
        require_once __DIR__ . '/controllers/BrowseController.php';
        showMenuItem();
        break;

    case 'home':
    default:
        require __DIR__ . '/views/home.php';
        break;
}