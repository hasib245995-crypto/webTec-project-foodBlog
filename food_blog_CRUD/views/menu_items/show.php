<?php
$title = h($item['name']);
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
  <h2><?= h($item['name']) ?></h2>

  <?php if (isAdmin()): ?>
    <div>
      <a href="<?= BASE_URL ?>/menu-items/<?= $item['id'] ?>/edit" class="btn-secondary">Edit</a>
      <!-- AJAX Delete button -->
      <button class="btn-danger"
              onclick="deleteMenuItem(<?= $item['id'] ?>, this)">
        Delete
      </button>
    </div>
  <?php endif; ?>
</div>

<div class="item-detail card">
  <?php if (!empty($item['image_path'])): ?>
    <img
      src="<?= BASE_URL ?>/uploads/menu/<?= h($item['image_path']) ?>"
      alt="<?= h($item['name']) ?>"
      class="item-img">
  <?php endif; ?>

  <p class="price-large">
    ৳<?= number_format((float)$item['price'], 2) ?>
  </p>

  <p class="restaurant-link">
    From:
    <a href="<?= BASE_URL ?>/restaurants/<?= $item['restaurant_id'] ?>/show">
      <?= h($item['restaurant_name']) ?>
    </a>
  </p>

  <p><?= nl2br(h($item['description'] ?? '')) ?></p>
</div>

<br>

<a href="<?= BASE_URL ?>/restaurants/<?= $item['restaurant_id'] ?>/show" class="btn-outline">
  ← Back to Restaurant
</a>

<?php include __DIR__ . '/../layouts/footer.php'; ?>