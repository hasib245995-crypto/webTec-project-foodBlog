<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/app.php';

// Remember Me auto-login
if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $db = getDB();
    $hashedToken = hash('sha256', $token);

    $stmt = mysqli_prepare($db, "SELECT * FROM users WHERE remember_token = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $hashedToken);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
    }
}

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base   = '/food_blog';
$path   = preg_replace('#^' . preg_quote($base, '#') . '/?#', '', $uri);
$path   = preg_replace('#^index\.php/?#', '', $path);
$path   = trim($path, '/');
$parts  = explode('/', $path);
$method = $_SERVER['REQUEST_METHOD'];

$controller = $parts[0] ?: 'home';
$action     = $parts[1] ?? null;
$id         = $parts[2] ?? null;

// Accept clean URLs like /restaurants/5/show
if (in_array($controller, ['restaurants', 'menu-items'], true)
    && isset($parts[1], $parts[2])
    && ctype_digit($parts[1])) {
    $id = $parts[1];
    $action = $parts[2];
}

//API routes for AJAX
if ($controller === 'api') {
    require_once __DIR__ . '/controllers/ApiController.php';
    $api = new ApiController();

    if ($action === 'menu-items' && ($parts[2] ?? '') === 'delete') {
        $api->deleteMenuItem((int)($_POST['id'] ?? 0));
    }

    if ($action === 'restaurants' && ($parts[2] ?? '') === 'delete') {
        $api->deleteRestaurant((int)($_POST['id'] ?? 0));
    }

    jsonResponse(['success' => false, 'message' => 'API route not found'], 404);
}

// ── Main routing
switch ($controller) {
    case '':
    case 'home':
        require_once __DIR__ . '/controllers/HomeController.php';
        $c = new HomeController();
        $c->index();
        break;

    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $c = new AuthController();
        match($action) {
            'register' => $c->register(),
            'login'    => $c->login(),
            'logout'   => $c->logout(),
            'profile'  => $c->profile(),
            default    => $c->login(),
        };
        break;

    case 'restaurants':
        require_once __DIR__ . '/controllers/RestaurantController.php';
        $c = new RestaurantController();
        match($action) {
            'index'   => $c->index(),
            'show'    => $c->show((int)$id),
            'create'  => $c->create(),
            'store'   => $c->store(),
            'edit'    => $c->edit((int)$id),
            'update'  => $c->update((int)$id),
            'delete'  => $c->delete((int)$id),
            default   => $c->index(),
        };
        break;

    case 'menu-items':
        require_once __DIR__ . '/controllers/MenuItemController.php';
        $c = new MenuItemController();
        match($action) {
            'show'   => $c->show((int)$id),
            'create' => $c->create(),
            'store'  => $c->store(),
            'edit'   => $c->edit((int)$id),
            'update' => $c->update((int)$id),
            'delete' => $c->delete((int)$id),
            default  => redirect('/restaurants'),
        };
        break;

    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        $c = new AdminController();
        match($action) {
            'dashboard' => $c->dashboard(),
            default     => $c->dashboard(),
        };
        break;

    default:
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
}