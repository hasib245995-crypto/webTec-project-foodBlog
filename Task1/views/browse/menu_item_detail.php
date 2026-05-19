<?php
// views/browse/menu_item_detail.php
require_once __DIR__ . '/../../config/app.php';
$pageTitle = $menuItem['name'];
require __DIR__ . '/../partials/header.php';
startSession();
$role = $_SESSION['role'] ?? 'visitor';
?>

<div class="page-container">
    <nav class="breadcrumb">
        <a href="<?= BASE_URL ?>/index.php?page=restaurants">Restaurants</a>
        <span>›</span>
        <a href="<?= BASE_URL ?>/index.php?page=restaurant&id=<?= $restaurant['id'] ?>"><?= e($restaurant['name']) ?></a>
        <span>›</span>
        <span><?= e($menuItem['name']) ?></span>
    </nav>

    <div class="item-detail-layout">
        <!-- Image -->
        <div class="item-image-wrap">
            <?php if (!empty($menuItem['image_path'])): ?>
                <img src="<?= BASE_URL ?>/public/uploads/menu/<?= e($menuItem['image_path']) ?>"
                     alt="<?= e($menuItem['name']) ?>" class="item-image">
            <?php else: ?>
                <div class="item-image-placeholder">🍽️</div>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div class="item-info">
            <h1><?= e($menuItem['name']) ?></h1>
            <p class="item-price">৳ <?= number_format((float)$menuItem['price'], 2) ?></p>
            <p class="item-restaurant">
                From: <a href="<?= BASE_URL ?>/index.php?page=restaurant&id=<?= $restaurant['id'] ?>"><?= e($restaurant['name']) ?></a>
            </p>
            <?php if (!empty($menuItem['description'])): ?>
                <div class="item-description">
                    <h2>Description</h2>
                    <p><?= nl2br(e($menuItem['description'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <h2 class="section-title">Reviews <span class="review-count">(<?= count($reviews) ?>)</span></h2>

        <?php if ($role === 'member'): ?>
            <!-- Review form placeholder for Task 3 -->
            <div class="review-form-notice">
                <p>Review submission is handled in Task 3 (AJAX endpoint).</p>
            </div>
        <?php elseif ($role === 'visitor'): ?>
            <p class="login-prompt">
                <a href="<?= BASE_URL ?>/index.php?page=login">Log in</a> or
                <a href="<?= BASE_URL ?>/index.php?page=register">register</a> to post a review.
            </p>
        <?php endif; ?>

        <?php if (empty($reviews)): ?>
            <p class="no-reviews">No reviews yet. Be the first!</p>
        <?php else: ?>
            <div class="reviews-list">
                <?php foreach ($reviews as $rev): ?>
                    <div class="review-card">
                        <div class="review-meta">
                            <span class="reviewer-name"><?= e($rev['reviewer_name']) ?></span>
                            <span class="review-date"><?= date('d M Y', strtotime($rev['created_at'])) ?></span>
                        </div>
                        <p class="review-comment"><?= nl2br(e($rev['comment'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
