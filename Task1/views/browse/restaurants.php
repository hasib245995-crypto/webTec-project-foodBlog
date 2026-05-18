<?php
// views/browse/restaurants.php
require_once __DIR__ . '/../../config/app.php';
$pageTitle = 'Restaurants';
require __DIR__ . '/../partials/header.php';
$flashError = getFlash('error');
?>

<div class="page-container">
    <div class="page-header">
        <h1>Restaurants</h1>
        <p>Discover great dining spots in your city</p>
    </div>

    <?php if ($flashError): ?>
        <div class="alert alert-error"><?= e($flashError) ?></div>
    <?php endif; ?>

    <?php if (empty($restaurants)): ?>
        <div class="empty-state">
            <span class="empty-icon">🍽️</span>
            <h2>No restaurants yet</h2>
            <p>Check back soon — new restaurants are being added.</p>
        </div>
    <?php else: ?>
        <div class="restaurants-grid">
            <?php foreach ($restaurants as $r): ?>
                <a href="<?= BASE_URL ?>/index.php?page=restaurant&id=<?= $r['id'] ?>" class="restaurant-card">
                    <div class="restaurant-card-body">
                        <h2 class="restaurant-name"><?= e($r['name']) ?></h2>
                        <div class="restaurant-meta">
                            <span class="meta-tag">📍 <?= e($r['location']) ?></span>
                            <?php if (!empty($r['area'])): ?>
                                <span class="meta-tag">🏙️ <?= e($r['area']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($r['short_background'])): ?>
                            <p class="restaurant-bg"><?= e(mb_substr($r['short_background'], 0, 120)) ?>…</p>
                        <?php endif; ?>
                    </div>
                    <div class="restaurant-card-footer">
                        <span class="view-link">View Menu →</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
