<?php
// views/partials/navbar.php
require_once __DIR__ . '/../../config/app.php';
startSession();
$role = $_SESSION['role'] ?? 'visitor';
$name = $_SESSION['name'] ?? '';
?>
<nav class="navbar">
    <div class="nav-container">
        <a href="<?= BASE_URL ?>/index.php?page=home" class="nav-brand">
            <span class="brand-icon">🍜</span>
            <span class="brand-text">FoodBlog</span>
        </a>
        <div class="nav-links">
            <a href="<?= BASE_URL ?>/index.php?page=restaurants" class="nav-link">Restaurants</a>
            <a href="<?= BASE_URL ?>/index.php?page=home#food-experience" class="nav-link">Food Experience</a>

            <?php if ($role === 'visitor'): ?>
                <a href="<?= BASE_URL ?>/index.php?page=login"    class="nav-link btn-outline">Login</a>
                <a href="<?= BASE_URL ?>/index.php?page=register" class="nav-link btn-primary">Register</a>

            <?php elseif ($role === 'member'): ?>
                <a href="<?= BASE_URL ?>/index.php?page=profile" class="nav-link">
                    👤 <?= e($name) ?>
                </a>
                <a href="<?= BASE_URL ?>/index.php?page=logout" class="nav-link btn-outline">Logout</a>

            <?php elseif ($role === 'admin'): ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin" class="nav-link">⚙ Admin</a>
                <a href="<?= BASE_URL ?>/index.php?page=profile" class="nav-link">
                    👤 <?= e($name) ?>
                </a>
                <a href="<?= BASE_URL ?>/index.php?page=logout" class="nav-link btn-outline">Logout</a>
            <?php endif; ?>
        </div>
        <button class="nav-hamburger" id="hamburger" aria-label="Toggle menu">&#9776;</button>
    </div>
</nav>
