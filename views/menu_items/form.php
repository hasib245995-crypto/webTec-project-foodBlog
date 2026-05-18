<?php
$isEdit = isset($item) && !empty($item);
$title  = $isEdit ? 'Edit Menu Item' : 'Add Menu Item';
include __DIR__ . '/../layouts/header.php';
?>
<div class="form-container">
  <h2><?= $isEdit ? 'Edit' : 'Add' ?> Menu Item for <?= h($restaurant['name']) ?></h2>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?></div>
  <?php endif; ?>
  <form method="POST"
        action="<?= BASE_URL ?>/menu-items/<?= $isEdit ? $item['id'] . '/update' : 'store' ?>"
        enctype="multipart/form-data" id="menuItemForm" novalidate>
    <?= csrfField() ?>
    <input type="hidden" name="restaurant_id" value="<?= $restaurant['id'] ?>">
    <div class="form-group">
      <label>Item Name *</label>
      <input type="text" name="name" class="input" required value="<?= h($item['name'] ?? $_POST['name'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea name="description" class="input textarea" rows="4"><?= h($item['description'] ?? $_POST['description'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label>Price (৳) *</label>
      <input type="number" name="price" class="input" required min="0.01" step="0.01"
             value="<?= h($item['price'] ?? $_POST['price'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Image (JPEG/PNG, max 2MB)</label>
      <?php if ($isEdit && !empty($item['image_path'])): ?>
        <img src="<?= BASE_URL ?>/uploads/menu/<?= h($item['image_path']) ?>" width="120" alt="Current image"><br>
      <?php endif; ?>
      <input type="file" name="image" class="input" accept="image/jpeg,image/png">
    </div>
    <button type="submit" class="btn-primary w-full"><?= $isEdit ? 'Update' : 'Add' ?> Item</button>
  </form>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
