<?php $title = 'Manage Reviews'; include __DIR__ . '/../layouts/header.php'; ?>
<h2>Manage All Reviews</h2>
<table class="table">
  <thead>
    <tr><th>User</th><th>Food Item</th><th>Comment</th><th>Date</th><th>Action</th></tr>
  </thead>
  <tbody id="reviews-table">
    <?php foreach ($reviews as $r): ?>
      <tr id="review-row-<?= $r['id'] ?>">
        <td><?= h($r['user_name']) ?></td>
        <td><?= h($r['item_name']) ?></td>
        <td><?= h(substr($r['comment'], 0, 80)) ?></td>
        <td><?= h(date('M d, Y', strtotime($r['created_at']))) ?></td>
        <td><button class="btn-danger btn-sm" onclick="deleteReview(<?= $r['id'] ?>)">Delete</button></td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($reviews)): ?><tr><td colspan="5">No reviews.</td></tr><?php endif; ?>
  </tbody>
</table>

<script>
const BASE_URL = '<?= BASE_URL ?>';
async function deleteReview(id) {
  if (!confirm('Delete this review?')) return;
  const res  = await fetch(BASE_URL + '/api/admin/delete-review/' + id, {method: 'DELETE'});
  const data = await res.json();
  if (data.success) document.getElementById('review-row-' + id)?.remove();
  else alert(data.error || 'Error');
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
