<?php
// views/home.php
require_once __DIR__ . '/../config/app.php';
startSession();
$pageTitle = 'Home';
require __DIR__ . '/partials/header.php';
$success = getFlash('success');
$error   = getFlash('error');
$role    = $_SESSION['role'] ?? 'visitor';
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= e($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <span class="hero-badge">Discover · Explore · Review</span>
        <h1 class="hero-title">Your City's<br><em>Food Story</em></h1>
        <p class="hero-subtitle">Explore restaurants, discover menu items, and share your culinary adventures with a community of food lovers.</p>
        <div class="hero-actions">
            <a href="<?= BASE_URL ?>/index.php?page=restaurants" class="btn btn-primary btn-lg">Browse Restaurants</a>
            <?php if ($role === 'visitor'): ?>
                <a href="<?= BASE_URL ?>/index.php?page=register" class="btn btn-outline btn-lg">Join the Community</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/index.php?page=home#food-experience" class="btn btn-outline btn-lg">Food Experience</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero-visual">
        <div class="hero-card hero-card-1">🍣 Sushi Restaurant</div>
        <div class="hero-card hero-card-2">🍕 Pizza Burg</div>
        <div class="hero-card hero-card-3">🍜 Ramen Bar</div>
    </div>
</section>

<!-- Features Section (Visitor) -->
<?php if ($role === 'visitor'): ?>
<section class="features">
    <div class="container">
        <h2 class="section-title">Why Join FoodBlog?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-icon">🔍</span>
                <h3>Search & Discover</h3>
                <p>Find restaurants by location, area, or cuisine type with powerful search filters.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">✍️</span>
                <h3>Write Reviews</h3>
                <p>Share your dining experience with detailed reviews on any food item.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">📖</span>
                <h3>Food Experience Blog</h3>
                <p>Post descriptive stories about your favourite restaurants and dishes.</p>
            </div>
        </div>
        <div class="cta-banner">
            <p>Ready to start your food journey?</p>
            <a href="<?= BASE_URL ?>/index.php?page=register" class="btn btn-primary">Create Free Account</a>
            <a href="<?= BASE_URL ?>/index.php?page=login" class="btn btn-outline">Sign In</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Food Experience Placeholder (anchor for other tasks) -->
<section class="food-exp-section" id="food-experience">
    <div class="container">
        <h2 class="section-title">Food Experience</h2>
        <p class="section-subtitle">
            <?php if ($role === 'visitor'): ?>
                <a href="<?= BASE_URL ?>/index.php?page=register">Register</a> or
                <a href="<?= BASE_URL ?>/index.php?page=login">log in</a> to post your food stories.
            <?php else: ?>
                Food Experience posts are managed in Task 4.
            <?php endif; ?>
        </p>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
