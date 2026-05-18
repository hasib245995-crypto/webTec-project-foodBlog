<?php $title = 'Login'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="form-container">
  <h2>Sign In</h2>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
    </div>
  <?php endif; ?>
  <form method="POST" action="<?= BASE_URL ?>/auth/login" id="loginForm" novalidate>
    <?= csrfField() ?>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="input" required value="<?= h($_POST['email'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" class="input" required minlength="8">
    </div>
    <div class="form-group checkbox">
      <label><input type="checkbox" name="remember_me"> Remember Me (30 days)</label>
    </div>
    <button type="submit" class="btn-primary w-full">Login</button>
  </form>
  <p class="form-footer">Don't have an account? <a href="<?= BASE_URL ?>/auth/register">Register</a></p>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
