<?php
// config/app.php

define('BASE_URL', 'http://localhost/project');
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('UPLOAD_URL', BASE_URL . '/public/uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB

// Start session if not already started
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Check if user is logged in
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']);
}

// Check role
function isAdmin() {
    startSession();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isMember() {
    startSession();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'member';
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('index.php?page=login');
    }
}

// Redirect helper
function redirect($url) {
    header("Location: " . BASE_URL . '/' . $url);
    exit;
}

// Flash messages
function setFlash($key, $message) {
    startSession();
    $_SESSION['flash'][$key] = $message;
}

function getFlash($key) {
    startSession();
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

// CSRF token
function generateCsrfToken() {
    startSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    startSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// XSS safe output
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}