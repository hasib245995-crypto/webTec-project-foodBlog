<?php
$isEdit = isset($post) && !empty($post) && isset($post['id']);
$title  = $isEdit ? 'Edit Post' : 'New Food Experience Post';
include __DIR__ . '/../layouts/header.php';
?>
<div class="form-container form-container-lg">
  <h2><?= $isEdit ? 'Edit' : 'New' ?> Food Experience Post</h2>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?></div>
  <?php endif; ?>
  <form method="POST"
        action="<?= BASE_URL ?>/food-experience/<?= $isEdit ? $post['id'] . '/update' : 'store' ?>"
        id="feForm" novalidate>
    <?= csrfField() ?>
    <div class="form-group">
      <label>Title *</label>
      <input type="text" name="title" class="input" required value="<?= h($post['title'] ?? $_POST['title'] ?? '') ?>">
      <span class="field-error" id="err-title"></span>
    </div>
    <div class="form-group">
      <label>Post Type *</label>
      <select name="post_type" class="input">
        <?php foreach (['restaurant','food','both'] as $t): ?>
          <option value="<?= $t ?>" <?= (($post['post_type'] ?? $_POST['post_type'] ?? 'both') === $t) ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Link to Restaurant (optional)</label>
      <select name="restaurant_id" class="input">
        <option value="">-- None --</option>
        <?php foreach ($restaurants as $r): ?>
          <option value="<?= $r['id'] ?>" <?= (($post['restaurant_id'] ?? $_POST['restaurant_id'] ?? '') == $r['id']) ? 'selected' : '' ?>><?= h($r['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Link to Menu Item (optional)</label>
      <select name="menu_item_id" class="input">
        <option value="">-- None --</option>
        <?php foreach ($menuItems as $mi): ?>
          <option value="<?= $mi['id'] ?>" <?= (($post['menu_item_id'] ?? $_POST['menu_item_id'] ?? '') == $mi['id']) ? 'selected' : '' ?>><?= h($mi['restaurant_name'] . ' → ' . $mi['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Content *</label>
      <textarea name="content" class="input textarea" rows="10" required><?= h($post['content'] ?? $_POST['content'] ?? '') ?></textarea>
      <span class="field-error" id="err-content"></span>
    </div>
    <button type="submit" class="btn-primary w-full"><?= $isEdit ? 'Update' : 'Publish' ?> Post</button>
  </form>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
