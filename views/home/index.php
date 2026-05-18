<?php $title = 'Home'; include __DIR__ . '/../layouts/header.php'; ?>

<section class="hero">
  <h1>Discover Amazing Food</h1>
  <p>Browse restaurants, explore menus, and share your culinary experiences.</p>
  <?php if (!isLoggedIn()): ?>
    <div class="hero-cta">
      <a href="<?= BASE_URL ?>/auth/register" class="btn-primary">Join Now</a>
      <a href="<?= BASE_URL ?>/auth/login" class="btn-secondary">Sign In</a>
    </div>
  <?php endif; ?>
</section>

<!-- Search Bar (Task 3) -->
<section class="search-section">
  <h2>Search Restaurants & Food</h2>
  <div class="search-bar">
    <input type="text" id="search-q" placeholder="Restaurant or food name..." class="input">
    <input type="text" id="search-location" placeholder="City / Location" class="input">
    <input type="text" id="search-area" placeholder="Area / Neighborhood" class="input">
    <button id="search-btn" class="btn-primary">Search</button>
  </div>
  <div id="search-results" class="search-results hidden"></div>
</section>

<section class="restaurants-section">
  <h2>Featured Restaurants</h2>
  <div class="card-grid">
    <?php foreach ($restaurants as $r): ?>
      <div class="card">
        <h3><a href="<?= BASE_URL ?>/restaurants/<?= $r['id'] ?>/show"><?= h($r['name']) ?></a></h3>
        <p class="meta">📍 <?= h($r['location']) ?> &middot; <?= h($r['area']) ?></p>
        <p><?= h(substr($r['short_background'] ?? '', 0, 120)) ?>...</p>
        <a href="<?= BASE_URL ?>/restaurants/<?= $r['id'] ?>/show" class="btn-outline">View Menu</a>
      </div>
    <?php endforeach; ?>
    <?php if (empty($restaurants)): ?>
      <p>No restaurants yet. <?php if (isAdmin()): ?><a href="<?= BASE_URL ?>/restaurants/create">Add one!</a><?php endif; ?></p>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
