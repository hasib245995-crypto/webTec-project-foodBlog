<?php $title = 'Food Experience'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
  <h2>Food Experience Blog</h2>
  <?php if (isLoggedIn()): ?>
    <a href="<?= BASE_URL ?>/food-experience/create" class="btn-primary">+ New Post</a>
  <?php endif; ?>
</div>

<?php if (empty($posts)): ?>
  <p>No posts yet. <?= isLoggedIn() ? '<a href="' . BASE_URL . '/food-experience/create">Be the first!</a>' : '' ?></p>
<?php endif; ?>

<div class="fe-posts">
  <?php foreach ($posts as $post): ?>
    <div class="fe-card">
      <div class="fe-card-header">
        <h3><a href="<?= BASE_URL ?>/food-experience/<?= $post['id'] ?>/show"><?= h($post['title']) ?></a></h3>
        <span class="badge badge-<?= h($post['post_type']) ?>"><?= ucfirst(h($post['post_type'])) ?></span>
      </div>
      <p class="meta">By <strong><?= h($post['author_name']) ?></strong> &middot; <?= h(date('M d, Y', strtotime($post['created_at']))) ?></p>
      <?php if ($post['restaurant_name']): ?>
        <p>🍴 <?= h($post['restaurant_name']) ?></p>
      <?php endif; ?>
      <p><?= h(substr($post['content'], 0, 200)) ?>...</p>
      <div class="card-actions">
        <a href="<?= BASE_URL ?>/food-experience/<?= $post['id'] ?>/show" class="btn-outline">Read More</a>
        <?php if (isLoggedIn() && ($post['user_id'] === $_SESSION['user_id'] || isAdmin())): ?>
          <a href="<?= BASE_URL ?>/food-experience/<?= $post['id'] ?>/edit" class="btn-secondary">Edit</a>
          <form method="POST" action="<?= BASE_URL ?>/food-experience/<?= $post['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Delete post?')">
            <?= csrfField() ?>
            <button type="submit" class="btn-danger">Delete</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
