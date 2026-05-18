<?php
$title = 'Admin Dashboard';
include __DIR__ . '/../layouts/header.php';
?>

<h2>Admin Dashboard</h2>

<div class="stats-grid">

  <div class="stat-card">
    <h3><?= $stats['restaurants'] ?></h3>
    <p>Restaurants</p>
    <a href="<?= BASE_URL ?>/restaurants" class="btn-outline btn-sm">Manage</a>
  </div>

  <div class="stat-card">
    <h3><?= $stats['menu_items'] ?></h3>
    <p>Menu Items</p>
    <a href="<?= BASE_URL ?>/restaurants" class="btn-outline btn-sm">View All</a>
  </div>

</div>

<div class="admin-links">
  <h3>Quick Actions</h3>
  <a href="<?= BASE_URL ?>/restaurants/create" class="btn-primary">+ Add Restaurant</a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>