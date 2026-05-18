<?php $title = 'My Profile'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="form-container">
  <h2>My Profile</h2>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?></div>
  <?php endif; ?>
  <form method="POST" action="<?= BASE_URL ?>/auth/profile" enctype="multipart/form-data" id="profileForm" novalidate>
    <?= csrfField() ?>
    <?php if ($user['profile_picture']): ?>
      <div class="profile-pic-preview">
        <img src="<?= BASE_URL ?>/uploads/profiles/<?= h($user['profile_picture']) ?>" alt="Profile" width="100">
      </div>
    <?php endif; ?>
    <div class="form-group">
      <label>Name</label>
      <input type="text" name="name" class="input" required minlength="2" value="<?= h($user['name']) ?>">
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="input" required value="<?= h($user['email']) ?>">
    </div>
    <div class="form-group">
      <label>Profile Picture (JPEG/PNG, max 2MB)</label>
      <input type="file" name="profile_picture" class="input" accept="image/jpeg,image/png">
    </div>
    <hr>
    <h3>Change Password (optional)</h3>
    <div class="form-group">
      <label>Current Password</label>
      <input type="password" name="current_password" class="input">
    </div>
    <div class="form-group">
      <label>New Password</label>
      <input type="password" name="new_password" id="new_password" class="input" minlength="8">
    </div>
    <div class="form-group">
      <label>Confirm New Password</label>
      <input type="password" name="new_password_confirm" id="new_password_confirm" class="input">
    </div>
    <button type="submit" class="btn-primary w-full">Update Profile</button>
  </form>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
