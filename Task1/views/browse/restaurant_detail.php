<?php
// views/browse/restaurant_detail.php
require_once __DIR__ . '/../../config/app.php';
$pageTitle = $restaurant['name'];
require __DIR__ . '/../partials/header.php';
?>

<div class="page-container">
    <nav class="breadcrumb">
        <a href="<?= BASE_URL ?>/index.php?page=restaurants">Restaurants</a>
        <span>›</span>
        <span><?= e($restaurant['name']) ?></span>
    </nav>

    <div class="restaurant-hero">
        <h1><?= e($restaurant['name']) ?></h1>
        <div class="restaurant-meta">
            <span class="meta-tag">📍 <?= e($restaurant['location']) ?></span>
            <?php if (!empty($restaurant['area'])): ?>
                <span class="meta-tag">🏙️ <?= e($restaurant['area']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="restaurant-about">
        <?php if (!empty($restaurant['short_background'])): ?>
            <div class="about-block">
                <h2>About</h2>
                <p><?= nl2br(e($restaurant['short_background'])) ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($restaurant['goals'])): ?>
            <div class="about-block">
                <h2>Our Goals</h2>
                <p><?= nl2br(e($restaurant['goals'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Menu Items</h2>

    <?php if (empty($menuItems)): ?>
        <div class="empty-state">
            <span class="empty-icon">📋</span>
            <p>No menu items added yet.</p>
        </div>
    <?php else: ?>
        <div class="menu-grid">
            <?php foreach ($menuItems as $item): ?>
                <a href="<?= BASE_URL ?>/index.php?page=menu_item&id=<?= $item['id'] ?>" class="menu-card">
                    <?php if (!empty($item['image_path'])): ?>
                        <div class="menu-card-img">
                            <img src="<?= BASE_URL ?>/public/uploads/menu/<?= e($item['image_path']) ?>"
                                 alt="<?= e($item['name']) ?>">
                        </div>
                    <?php else: ?>
                        <div class="menu-card-img menu-card-img--placeholder">🍽️</div>
                    <?php endif; ?>
                    <div class="menu-card-body">
                        <h3><?= e($item['name']) ?></h3>
                        <p class="menu-desc"><?= e(mb_substr($item['description'] ?? '', 0, 80)) ?>…</p>
                        <span class="menu-price">৳ <?= number_format((float)$item['price'], 2) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
