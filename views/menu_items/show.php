<?php $title = h($item['name']); include __DIR__ . '/../layouts/header.php'; ?>
<div class="page-header">
  <h2><?= h($item['name']) ?></h2>
  <?php if (isAdmin()): ?>
    <div>
      <a href="<?= BASE_URL ?>/menu-items/<?= $item['id'] ?>/edit" class="btn-secondary">Edit</a>
    </div>
  <?php endif; ?>
</div>

<div class="item-detail card">
  <?php if ($item['image_path']): ?>
    <img src="<?= BASE_URL ?>/uploads/menu/<?= h($item['image_path']) ?>" alt="<?= h($item['name']) ?>" class="item-img">
  <?php endif; ?>
  <p class="price-large">৳<?= number_format($item['price'], 2) ?></p>
  <p class="restaurant-link">From: <a href="<?= BASE_URL ?>/restaurants/<?= $item['restaurant_id'] ?>/show"><?= h($item['restaurant_name']) ?></a></p>
  <p><?= nl2br(h($item['description'] ?? '')) ?></p>
</div>

<!-- Reviews Section (Task 3) -->
<section class="reviews-section">
  <h3>Reviews</h3>
  <div id="reviews-list">
    <?php foreach ($reviews as $rev): ?>
      <div class="review-card" id="review-<?= $rev['id'] ?>">
        <strong><?= h($rev['user_name']) ?></strong>
        <span class="review-date"><?= h($rev['created_at']) ?></span>
        <p><?= nl2br(h($rev['comment'])) ?></p>
        <?php if (isLoggedIn() && ($rev['user_id'] === $_SESSION['user_id'] || isAdmin())): ?>
          <button class="btn-danger btn-sm" onclick="deleteReview(<?= $rev['id'] ?>)">Delete</button>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <?php if (empty($reviews)): ?><p id="no-reviews">No reviews yet. Be the first!</p><?php endif; ?>
  </div>

  <?php if (isMember()): ?>
    <div class="review-form">
      <h4>Write a Review</h4>
      <div id="review-error" class="alert alert-error hidden"></div>
      <div class="form-group">
        <label>Your Name</label>
        <input type="text" class="input" value="<?= h($_SESSION['name']) ?>" disabled>
      </div>
      <div class="form-group">
        <label>Comment *</label>
        <textarea id="review-comment" class="input textarea" rows="4" maxlength="1000" placeholder="Share your thoughts..."></textarea>
        <span id="comment-count" class="char-count">0 / 1000</span>
      </div>
      <button id="submit-review" class="btn-primary" onclick="submitReview(<?= $item['id'] ?>)">Submit Review</button>
    </div>
  <?php elseif (!isLoggedIn()): ?>
    <p><a href="<?= BASE_URL ?>/auth/login">Login</a> to leave a review.</p>
  <?php endif; ?>
</section>

<br><a href="<?= BASE_URL ?>/restaurants/<?= $item['restaurant_id'] ?>/show" class="btn-outline">← Back to Restaurant</a>

<script>
const BASE_URL = '<?= BASE_URL ?>';

document.getElementById('review-comment')?.addEventListener('input', function() {
  document.getElementById('comment-count').textContent = this.value.length + ' / 1000';
});

async function submitReview(menuItemId) {
  const comment = document.getElementById('review-comment').value.trim();
  const errBox  = document.getElementById('review-error');
  errBox.classList.add('hidden');
  if (!comment) { errBox.textContent = 'Comment cannot be empty.'; errBox.classList.remove('hidden'); return; }
  if (comment.length > 1000) { errBox.textContent = 'Comment too long.'; errBox.classList.remove('hidden'); return; }

  const btn = document.getElementById('submit-review');
  btn.disabled = true;
  try {
    const res  = await fetch(BASE_URL + '/api/reviews/add', {
      method: 'POST', headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({menu_item_id: menuItemId, comment})
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Error');
    document.getElementById('no-reviews')?.remove();
    const list = document.getElementById('reviews-list');
    list.insertAdjacentHTML('afterbegin', `
      <div class="review-card" id="review-${data.id}">
        <strong>${escHtml(data.user_name)}</strong>
        <span class="review-date">${data.created_at}</span>
        <p>${escHtml(data.comment).replace(/\n/g,'<br>')}</p>
        <button class="btn-danger btn-sm" onclick="deleteReview(${data.id})">Delete</button>
      </div>`);
    document.getElementById('review-comment').value = '';
    document.getElementById('comment-count').textContent = '0 / 1000';
  } catch (e) {
    errBox.textContent = e.message; errBox.classList.remove('hidden');
  }
  btn.disabled = false;
}

async function deleteReview(id) {
  if (!confirm('Delete this review?')) return;
  const res = await fetch(BASE_URL + '/api/reviews/' + id, {method: 'DELETE'});
  const data = await res.json();
  if (data.success) document.getElementById('review-' + id)?.remove();
  else alert(data.error || 'Error deleting review.');
}

function escHtml(s) {
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
