<?php
// controllers/AuthController.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/UserModel.php';

// Try auto-login via remember me cookie
function tryRememberMe() {
    startSession();
    if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
        $rawToken    = $_COOKIE['remember_token'];
        $hashedToken = hash('sha256', $rawToken);
        $user        = findUserByToken($hashedToken);
        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];
        }
    }
}

// Show register form
function showRegister() {
    $csrf = generateCsrfToken();
    require __DIR__ . '/../views/auth/register.php';
}

// Handle registration POST
function handleRegister() {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        setFlash('error', 'Invalid request. Please try again.');
        redirect('index.php?page=register');
    }

    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $password= $_POST['password']     ?? '';
    $confirm = $_POST['confirm']      ?? '';
    $role    = $_POST['role']         ?? 'member';
    $errors  = [];

    // Server-side validation
    if (strlen($name) < 2)
        $errors['name'] = 'Name must be at least 2 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Enter a valid email address.';
    if (strlen($password) < 8)
        $errors['password'] = 'Password must be at least 8 characters.';
    if ($password !== $confirm)
        $errors['confirm'] = 'Passwords do not match.';
    if (!in_array($role, ['admin', 'member']))
        $errors['role'] = 'Invalid role selected.';
    if (empty($errors) && emailExists($email))
        $errors['email'] = 'Email is already registered.';

    if (!empty($errors)) {
        $old  = compact('name', 'email', 'role');
        $csrf = generateCsrfToken();
        require __DIR__ . '/../views/auth/register.php';
        return;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    createUser($name, $email, $hash, $role);

    setFlash('success', 'Registration successful! Please log in.');
    redirect('index.php?page=login');
}

// Show login form
function showLogin() {
    $csrf = generateCsrfToken();
    require __DIR__ . '/../views/auth/login.php';
}

// Handle login POST
function handleLogin() {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        setFlash('error', 'Invalid request. Please try again.');
        redirect('index.php?page=login');
    }

    $email      = trim($_POST['email']    ?? '');
    $password   = $_POST['password']      ?? '';
    $rememberMe = isset($_POST['remember_me']);
    $errors     = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Enter a valid email address.';
    if (empty($password))
        $errors['password'] = 'Password is required.';

    if (empty($errors)) {
        $user = findUserByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors['general'] = 'Invalid email or password.';
        }
    }

    if (!empty($errors)) {
        $old  = ['email' => $email];
        $csrf = generateCsrfToken();
        require __DIR__ . '/../views/auth/login.php';
        return;
    }

    // Create session
    startSession();
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name']    = $user['name'];
    $_SESSION['role']    = $user['role'];

    // Remember Me - store hashed token in DB + cookie (from PDF notes)
    if ($rememberMe) {
        $rawToken    = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $rawToken);
        setRememberToken($user['id'], $hashedToken);
        setcookie("remember_token", $rawToken, time() + (86400 * 30), "/");
    }

    setFlash('success', 'Welcome back, ' . e($user['name']) . '!');
    redirect('index.php?page=home');
}

// Handle logout
function handleLogout() {
    startSession();

    // Clear remember token from DB
    if (isset($_SESSION['user_id'])) {
        setRememberToken((int)$_SESSION['user_id'], null);
    }

    // Destroy session
    session_unset();
    session_destroy();

    // Delete cookie (set expiry to past)
    setcookie("remember_token", "", time() - 3600, "/");

    redirect('index.php?page=login');
}