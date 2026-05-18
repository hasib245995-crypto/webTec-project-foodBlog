<?php
// ── Start session (matches your format)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/food_blog');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MENU_UPLOAD_DIR', UPLOAD_DIR . 'menu/');
define('PROFILE_UPLOAD_DIR', UPLOAD_DIR . 'profiles/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB

function redirect(string $path): void {
    header('Location: ' . BASE_URL . $path);
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isMember(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'member';
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect('/auth/login');
    }
}

function requireAdmin(): void {
    if (!isAdmin()) {
        redirect('/auth/login');
    }
}

function requireMember(): void {
    if (!isMember()) {
        redirect('/auth/login');
    }
}

// ── HTML escaping
function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// ── CSRF protection
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . h(generateCsrfToken()) . '">';
}

// ── Flash messages
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function jsonResponse(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// ── File upload helper    
function uploadFile(array $file, string $dest_dir, string $prefix = ''): string|false {
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    if (!is_dir($dest_dir)) mkdir($dest_dir, 0755, true);

    $allowed_types = ['image/jpeg', 'image/png'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!in_array($mime, $allowed_types)) return false;
    if ($file['size'] > MAX_FILE_SIZE) return false;

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . bin2hex(random_bytes(8)) . '.' . $ext;

    if (!move_uploaded_file($file['tmp_name'], $dest_dir . $filename)) return false;

    return $filename;
}