<?php
// views/auth/register.php
require_once __DIR__ . '/../../config/app.php';
$pageTitle = 'Register';
require __DIR__ . '/../partials/header.php';
$flashError   = getFlash('error');
$flashSuccess = getFlash('success');
$old    = $old    ?? [];
$errors = $errors ?? [];
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Create Account</h1>
            <p>Join FoodBlog and start exploring</p>
        </div>

        <?php if ($flashError):   ?><div class="alert alert-error"><?= e($flashError) ?></div><?php endif; ?>
        <?php if ($flashSuccess): ?><div class="alert alert-success"><?= e($flashSuccess) ?></div><?php endif; ?>
        <?php if (!empty($errors['general'])): ?><div class="alert alert-error"><?= e($errors['general']) ?></div><?php endif; ?>

        <form id="registerForm" method="POST" action="<?= BASE_URL ?>/index.php?page=register" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

            <div class="form-group <?= isset($errors['name']) ? 'has-error' : '' ?>">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?= e($old['name'] ?? '') ?>"
                       placeholder="Your name" autocomplete="name" required>
                <?php if (isset($errors['name'])): ?>
                    <span class="field-error"><?= e($errors['name']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>"
                       placeholder="you@example.com" autocomplete="email" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="field-error"><?= e($errors['email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['password']) ? 'has-error' : '' ?>">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Minimum 8 characters" autocomplete="new-password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="field-error"><?= e($errors['password']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['confirm']) ? 'has-error' : '' ?>">
                <label for="confirm">Confirm Password</label>
                <input type="password" id="confirm" name="confirm"
                       placeholder="Repeat your password" autocomplete="new-password" required>
                <?php if (isset($errors['confirm'])): ?>
                    <span class="field-error"><?= e($errors['confirm']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['role']) ? 'has-error' : '' ?>">
                <label for="role">Account Type</label>
                <select id="role" name="role" required>
                    <option value="member" <?= ($old['role'] ?? 'member') === 'member' ? 'selected' : '' ?>>Member</option>
                    <option value="admin"  <?= ($old['role'] ?? '') === 'admin'  ? 'selected' : '' ?>>Admin</option>
                </select>
                <?php if (isset($errors['role'])): ?>
                    <span class="field-error"><?= e($errors['role']) ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Create Account</button>
        </form>

        <p class="auth-switch">Already have an account? <a href="<?= BASE_URL ?>/index.php?page=login">Sign in</a></p>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/validate-register.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
