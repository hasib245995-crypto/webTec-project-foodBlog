<?php $title = 'Admin Dashboard'; include __DIR__ . '/../layouts/header.php'; ?>
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
  <div class="stat-card">
    <h3><?= $stats['reviews'] ?></h3>
    <p>Total Reviews</p>
    <a href="<?= BASE_URL ?>/admin/reviews" class="btn-outline btn-sm">Manage</a>
  </div>
  <div class="stat-card">
    <h3><?= $stats['fe_posts'] ?></h3>
    <p>Food Experience Posts</p>
    <a href="<?= BASE_URL ?>/food-experience" class="btn-outline btn-sm">View</a>
  </div>
</div>

<div class="admin-links">
  <h3>Quick Actions</h3>
  <a href="<?= BASE_URL ?>/restaurants/create" class="btn-primary">+ Add Restaurant</a>
  <a href="<?= BASE_URL ?>/admin/members" class="btn-secondary">Manage Members</a>
  <a href="<?= BASE_URL ?>/admin/reviews" class="btn-secondary">Manage Reviews</a>
  <a href="<?= BASE_URL ?>/food-experience" class="btn-secondary">Food Experience Posts</a>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
