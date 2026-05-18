<?php $title = h($restaurant['name']); include __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
  <h2><?= h($restaurant['name']) ?></h2>
  <?php if (isAdmin()): ?>
    <div>
      <a href="<?= BASE_URL ?>/restaurants/<?= $restaurant['id'] ?>/edit" class="btn-secondary">Edit</a>
      <a href="<?= BASE_URL ?>/menu-items/create?restaurant_id=<?= $restaurant['id'] ?>" class="btn-primary">+ Add Menu Item</a>
    </div>
  <?php endif; ?>
</div>

<div class="restaurant-info card">
  <p>📍 <strong><?= h($restaurant['location']) ?></strong> &middot; <?= h($restaurant['area']) ?></p>
  <p><?= nl2br(h($restaurant['short_background'] ?? '')) ?></p>
  <?php if ($restaurant['goals']): ?>
    <p><strong>Goals:</strong> <?= nl2br(h($restaurant['goals'])) ?></p>
  <?php endif; ?>
</div>

<h3>Menu Items</h3>
<div class="card-grid">
  <?php foreach ($items as $item): ?>
    <div class="card">
      <?php if ($item['image_path']): ?>
        <img src="<?= BASE_URL ?>/uploads/menu/<?= h($item['image_path']) ?>" alt="<?= h($item['name']) ?>" class="card-img">
      <?php endif; ?>
      <h4><a href="<?= BASE_URL ?>/menu-items/<?= $item['id'] ?>/show"><?= h($item['name']) ?></a></h4>
      <p class="price">৳<?= number_format($item['price'], 2) ?></p>
      <p><?= h(substr($item['description'] ?? '', 0, 100)) ?></p>
      <div class="card-actions">
        <a href="<?= BASE_URL ?>/menu-items/<?= $item['id'] ?>/show" class="btn-outline">Details</a>
        <?php if (isAdmin()): ?>
          <a href="<?= BASE_URL ?>/menu-items/<?= $item['id'] ?>/edit" class="btn-secondary">Edit</a>
          <form method="POST" action="<?= BASE_URL ?>/menu-items/<?= $item['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Delete this item?')">
            <?= csrfField() ?>
            <button type="submit" class="btn-danger">Delete</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (empty($items)): ?><p>No menu items yet.</p><?php endif; ?>
</div>

<br><a href="<?= BASE_URL ?>/restaurants" class="btn-outline">← Back to Restaurants</a>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
