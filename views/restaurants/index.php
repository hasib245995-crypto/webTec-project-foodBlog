<?php $title = 'Restaurants'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
  <h2>All Restaurants</h2>
  <?php if (isAdmin()): ?>
    <a href="<?= BASE_URL ?>/restaurants/create" class="btn-primary">+ Add Restaurant</a>
  <?php endif; ?>
</div>
<div class="card-grid">
  <?php foreach ($restaurants as $r): ?>
    <div class="card">
      <h3><a href="<?= BASE_URL ?>/restaurants/<?= $r['id'] ?>/show"><?= h($r['name']) ?></a></h3>
      <p class="meta">📍 <?= h($r['location']) ?> &middot; <?= h($r['area']) ?></p>
      <p><?= h(substr($r['short_background'] ?? '', 0, 150)) ?></p>
      <div class="card-actions">
        <a href="<?= BASE_URL ?>/restaurants/<?= $r['id'] ?>/show" class="btn-outline">View</a>
        <?php if (isAdmin()): ?>
          <a href="<?= BASE_URL ?>/restaurants/<?= $r['id'] ?>/edit" class="btn-secondary">Edit</a>
          <form method="POST" action="<?= BASE_URL ?>/restaurants/<?= $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Delete this restaurant and all its items?')">
            <?= csrfField() ?>
            <button type="submit" class="btn-danger">Delete</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (empty($restaurants)): ?><p>No restaurants found.</p><?php endif; ?>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
