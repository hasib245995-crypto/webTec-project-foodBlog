<?php $title = 'Register'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="form-container">
  <h2>Create Account</h2>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
    </div>
  <?php endif; ?>
  <form method="POST" action="<?= BASE_URL ?>/auth/register" id="registerForm" novalidate>
    <?= csrfField() ?>
    <div class="form-group">
      <label>Full Name</label>
      <input type="text" name="name" class="input" required minlength="2" value="<?= h($_POST['name'] ?? '') ?>">
      <span class="field-error" id="err-name"></span>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="input" required value="<?= h($_POST['email'] ?? '') ?>">
      <span class="field-error" id="err-email"></span>
    </div>
    <div class="form-group">
      <label>Password (min 8 characters)</label>
      <input type="password" name="password" id="password" class="input" required minlength="8">
      <span class="field-error" id="err-password"></span>
    </div>
    <div class="form-group">
      <label>Confirm Password</label>
      <input type="password" name="password_confirm" id="password_confirm" class="input" required>
      <span class="field-error" id="err-password-confirm"></span>
    </div>
    <div class="form-group">
      <label>Role</label>
      <select name="role" class="input">
        <option value="member" <?= (($_POST['role'] ?? '') === 'member') ? 'selected' : '' ?>>Member</option>
        <option value="admin"  <?= (($_POST['role'] ?? '') === 'admin')  ? 'selected' : '' ?>>Admin</option>
      </select>
    </div>
    <button type="submit" class="btn-primary w-full">Register</button>
  </form>
  <p class="form-footer">Already have an account? <a href="<?= BASE_URL ?>/auth/login">Login</a></p>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
