<?php $title = 'Manage Members'; include __DIR__ . '/../layouts/header.php'; ?>
<h2>Manage Members</h2>
<div id="member-error" class="alert alert-error hidden"></div>
<table class="table">
  <thead>
    <tr><th>Name</th><th>Email</th><th>Joined</th><th>Action</th></tr>
  </thead>
  <tbody id="members-table">
    <?php foreach ($members as $m): ?>
      <tr id="member-row-<?= $m['id'] ?>">
        <td><?= h($m['name']) ?></td>
        <td><?= h($m['email']) ?></td>
        <td><?= h(date('M d, Y', strtotime($m['created_at']))) ?></td>
        <td>
          <button class="btn-danger btn-sm" onclick="deleteMember(<?= $m['id'] ?>, '<?= h(addslashes($m['name'])) ?>')">Remove</button>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($members)): ?><tr><td colspan="4">No members found.</td></tr><?php endif; ?>
  </tbody>
</table>

<script>
const BASE_URL = '<?= BASE_URL ?>';
async function deleteMember(id, name) {
  if (!confirm('Remove member "' + name + '"? This will delete all their reviews and posts.')) return;
  const errBox = document.getElementById('member-error');
  try {
    const res  = await fetch(BASE_URL + '/api/admin/delete-member/' + id, {method: 'DELETE'});
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Error');
    document.getElementById('member-row-' + id)?.remove();
  } catch (e) {
    errBox.textContent = e.message; errBox.classList.remove('hidden');
  }
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
