<?php $title = h($post['title']); include __DIR__ . '/../layouts/header.php'; ?>
<article class="fe-detail">
  <div class="fe-detail-header">
    <h2><?= h($post['title']) ?></h2>
    <span class="badge badge-<?= h($post['post_type']) ?>"><?= ucfirst(h($post['post_type'])) ?></span>
  </div>
  <p class="meta">By <strong><?= h($post['author_name']) ?></strong> &middot; <?= h(date('M d, Y', strtotime($post['created_at']))) ?>
    <?php if ($post['updated_at'] !== $post['created_at']): ?>
      &middot; <em>edited <?= h(date('M d, Y', strtotime($post['updated_at']))) ?></em>
    <?php endif; ?>
  </p>
  <?php if ($post['restaurant_name']): ?>
    <p>🍴 Restaurant: <a href="<?= BASE_URL ?>/restaurants/<?= $post['restaurant_id'] ?>/show"><?= h($post['restaurant_name']) ?></a></p>
  <?php endif; ?>
  <?php if ($post['item_name']): ?>
    <p>🍜 Food Item: <a href="<?= BASE_URL ?>/menu-items/<?= $post['menu_item_id'] ?>/show"><?= h($post['item_name']) ?></a></p>
  <?php endif; ?>
  <div class="fe-content"><?= nl2br(h($post['content'])) ?></div>
  <?php if (isLoggedIn() && ($post['user_id'] === $_SESSION['user_id'] || isAdmin())): ?>
    <div class="post-actions">
      <a href="<?= BASE_URL ?>/food-experience/<?= $post['id'] ?>/edit" class="btn-secondary">Edit</a>
      <form method="POST" action="<?= BASE_URL ?>/food-experience/<?= $post['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Delete this post?')">
        <?= csrfField() ?>
        <button class="btn-danger">Delete Post</button>
      </form>
    </div>
  <?php endif; ?>
</article>

<!-- Comments Section (Task 4) -->
<section class="comments-section">
  <h3>Comments</h3>
  <div id="comments-list">
    <?php foreach ($comments as $c): ?>
      <div class="comment-card" id="comment-<?= $c['id'] ?>">
        <strong><?= h($c['user_name']) ?></strong>
        <span class="review-date"><?= h($c['created_at']) ?></span>
        <p><?= nl2br(h($c['comment'])) ?></p>
        <?php if (isLoggedIn() && ($c['user_id'] === $_SESSION['user_id'] || isAdmin())): ?>
          <button class="btn-danger btn-sm" onclick="deleteComment(<?= $c['id'] ?>)">Delete</button>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <?php if (empty($comments)): ?><p id="no-comments">No comments yet.</p><?php endif; ?>
  </div>

  <?php if (isLoggedIn()): ?>
    <div class="comment-form">
      <h4>Add a Comment</h4>
      <div id="comment-error" class="alert alert-error hidden"></div>
      <div class="form-group">
        <textarea id="comment-text" class="input textarea" rows="3" placeholder="Write a comment..." maxlength="1000"></textarea>
      </div>
      <button class="btn-primary" onclick="postComment(<?= $post['id'] ?>)">Post Comment</button>
    </div>
  <?php else: ?>
    <p><a href="<?= BASE_URL ?>/auth/login">Login</a> to comment.</p>
  <?php endif; ?>
</section>

<br><a href="<?= BASE_URL ?>/food-experience" class="btn-outline">← Back to Food Experience</a>

<script>
const BASE_URL = '<?= BASE_URL ?>';
function escHtml(s) { return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

async function postComment(postId) {
  const text   = document.getElementById('comment-text').value.trim();
  const errBox = document.getElementById('comment-error');
  errBox.classList.add('hidden');
  if (!text) { errBox.textContent = 'Comment cannot be empty.'; errBox.classList.remove('hidden'); return; }
  try {
    const res  = await fetch(BASE_URL + '/api/food-exp/comments/add', {
      method: 'POST', headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({post_id: postId, comment: text})
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Error');
    document.getElementById('no-comments')?.remove();
    document.getElementById('comments-list').insertAdjacentHTML('beforeend',
      `<div class="comment-card" id="comment-${data.id}">
         <strong>${escHtml(data.user_name)}</strong>
         <span class="review-date">${data.created_at}</span>
         <p>${escHtml(data.comment).replace(/\n/g,'<br>')}</p>
         <button class="btn-danger btn-sm" onclick="deleteComment(${data.id})">Delete</button>
       </div>`);
    document.getElementById('comment-text').value = '';
  } catch (e) {
    errBox.textContent = e.message; errBox.classList.remove('hidden');
  }
}

async function deleteComment(id) {
  if (!confirm('Delete this comment?')) return;
  const res  = await fetch(BASE_URL + '/api/food-exp/comments/' + id, {method: 'DELETE'});
  const data = await res.json();
  if (data.success) document.getElementById('comment-' + id)?.remove();
  else alert(data.error || 'Error');
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
