<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="base-url" content="<?= BASE_URL ?>">
<title><?= h($title ?? 'Food Blog') ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
<nav class="navbar">
  <a class="navbar-brand" href="<?= BASE_URL ?>/home">🍽️ FoodBlog</a>
  <div class="nav-links">
    <a href="<?= BASE_URL ?>/restaurants">Restaurants</a>
    <a href="<?= BASE_URL ?>/food-experience">Food Experience</a>
    <?php if (isLoggedIn()): ?>
      <?php if (isAdmin()): ?>
        <a href="<?= BASE_URL ?>/admin/dashboard">Admin</a>
      <?php endif; ?>
      <a href="<?= BASE_URL ?>/auth/profile"><?php if (!empty($_SESSION['name'])): ?>
    Profile (<?= h($_SESSION['name']) ?>)
<?php else: ?>
    <a href="<?= BASE_URL ?>/login">Login</a>
<?php endif; ?></a>
      <a href="<?= BASE_URL ?>/auth/logout" onclick="return confirm('Log out?')">Logout</a>
    <?php else: ?>
      <a href="<?= BASE_URL ?>/auth/login">Login</a>
      <a href="<?= BASE_URL ?>/auth/register" class="btn-primary">Register</a>
    <?php endif; ?>
  </div>
</nav>
<main class="container">
<?php $flash = getFlash(); if ($flash): ?>
  <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
<?php endif; ?>
