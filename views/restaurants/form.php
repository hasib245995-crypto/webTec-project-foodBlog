<?php
$isEdit = isset($restaurant) && !empty($restaurant);

$title = $isEdit
    ? 'Edit Restaurant'
    : 'Add Restaurant';

include __DIR__ . '/../layouts/header.php';
?>

<div class="form-container">

  <h2>
    <?= $isEdit ? 'Edit' : 'Add' ?> Restaurant
  </h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <?php foreach ($errors as $e): ?>
        <p><?= h($e) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form
    method="POST"
    action="<?= BASE_URL ?>/restaurants/<?= $isEdit ? $restaurant['id'] . '/update' : 'store' ?>"
    id="restaurantForm"
    novalidate>

    <?= csrfField() ?>

    <div class="form-group">
      <label>Restaurant Name *</label>
      <input type="text" name="name" class="input" required maxlength="100"
             value="<?= h($restaurant['name'] ?? $_POST['name'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label>Location (City) *</label>
      <input type="text" name="location" class="input" required maxlength="100"
             value="<?= h($restaurant['location'] ?? $_POST['location'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label>Area (Neighborhood) *</label>
      <input type="text" name="area" class="input" required maxlength="100"
             value="<?= h($restaurant['area'] ?? $_POST['area'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label>Short Background *</label>
      <textarea name="short_background" class="input textarea" rows="4" required maxlength="500"><?= h($restaurant['short_background'] ?? $_POST['short_background'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label>Goals *</label>
      <textarea name="goals" class="input textarea" rows="4" required maxlength="500"><?= h($restaurant['goals'] ?? $_POST['goals'] ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn-primary w-full">
      <?= $isEdit ? 'Update' : 'Add' ?> Restaurant
    </button>

  </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>