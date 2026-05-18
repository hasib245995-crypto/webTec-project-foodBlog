<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/app.php';

class AuthController {
    private UserModel $users;
    public function __construct() { $this->users = new UserModel(); }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect('/auth/register');
            }
            $name  = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';
            $pass2 = $_POST['password_confirm'] ?? '';
            $role  = in_array($_POST['role'] ?? '', ['admin','member']) ? $_POST['role'] : 'member';
            $errors = [];
            if (strlen($name) < 2) $errors[] = 'Name must be at least 2 characters.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
            if (strlen($pass) < 8) $errors[] = 'Password must be at least 8 characters.';
            if ($pass !== $pass2) $errors[] = 'Passwords do not match.';
            if ($this->users->findByEmail($email)) $errors[] = 'Email already registered.';
            if ($errors) {
                include __DIR__ . '/../views/auth/register.php';
                return;
            }
            $this->users->create($name, $email, $pass, $role);
            setFlash('success', 'Registration successful! Please log in.');
            redirect('/auth/login');
        }
        $errors = [];
        include __DIR__ . '/../views/auth/register.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect('/auth/login');
            }
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';
            $user  = $this->users->findByEmail($email);
            $errors = [];
            if (!$user || !password_verify($pass, $user['password_hash'])) {
                $errors[] = 'Invalid email or password.';
                include __DIR__ . '/../views/auth/login.php';
                return;
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];

            // Remember Me
            if (!empty($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(32));
                $this->users->setRememberToken($user['id'], hash('sha256', $token));
                setcookie('remember_token', $token, time() + 30 * 86400, '/', '', false, true);
            }

            setFlash('success', 'Welcome back, ' . $user['name'] . '!');
            redirect('/home');
        }
        $errors = [];
        include __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void {
        if (isLoggedIn()) {
            $this->users->clearRememberToken($_SESSION['user_id']);
        }
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    public function profile(): void {
        requireLogin();
        $user = $this->users->findById($_SESSION['user_id']);
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect('/auth/profile');
            }
            $name  = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            if (strlen($name) < 2) $errors[] = 'Name too short.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
            $existing = $this->users->findByEmail($email);
            if ($existing && $existing['id'] !== $user['id']) $errors[] = 'Email already in use.';

            $pic = null;
            if (!empty($_FILES['profile_picture']['name'])) {
                $pic = uploadFile($_FILES['profile_picture'], PROFILE_UPLOAD_DIR, 'profile_');
                if (!$pic) $errors[] = 'Invalid image (JPEG/PNG, max 2MB).';
            }

            // Password change
            if (!empty($_POST['current_password'])) {
                if (!password_verify($_POST['current_password'], $user['password_hash'])) {
                    $errors[] = 'Current password is incorrect.';
                } elseif (strlen($_POST['new_password'] ?? '') < 8) {
                    $errors[] = 'New password must be at least 8 characters.';
                } elseif ($_POST['new_password'] !== $_POST['new_password_confirm']) {
                    $errors[] = 'New passwords do not match.';
                }
            }

            if (!$errors) {
                $this->users->updateProfile($user['id'], $name, $email, $pic);
                if (!empty($_POST['current_password']) && empty($errors)) {
                    $this->users->updatePassword($user['id'], $_POST['new_password']);
                }
                $_SESSION['name'] = $name;
                setFlash('success', 'Profile updated successfully!');
                redirect('/auth/profile');
            }
            $user = array_merge($user, ['name' => $name, 'email' => $email]);
        }
        include __DIR__ . '/../views/auth/profile.php';
    }
}
