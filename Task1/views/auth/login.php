<?php
// views/auth/login.php
require_once __DIR__ . '/../../config/app.php';
$pageTitle = 'Login';
require __DIR__ . '/../partials/header.php';
$flashError   = getFlash('error');
$flashSuccess = getFlash('success');
$old    = $old    ?? [];
$errors = $errors ?? [];
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p>Sign in to your FoodBlog account</p>
        </div>

        <?php if ($flashError):   ?><div class="alert alert-error"><?= e($flashError) ?></div><?php endif; ?>
        <?php if ($flashSuccess): ?><div class="alert alert-success"><?= e($flashSuccess) ?></div><?php endif; ?>
        <?php if (!empty($errors['general'])): ?><div class="alert alert-error"><?= e($errors['general']) ?></div><?php endif; ?>

        <form id="loginForm" method="POST" action="<?= BASE_URL ?>/index.php?page=login" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

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
                       placeholder="Your password" autocomplete="current-password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="field-error"><?= e($errors['password']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group form-inline">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember_me" id="remember_me">
                    <span>Remember me for 30 days</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Sign In</button>
        </form>

        <p class="auth-switch">No account yet? <a href="<?= BASE_URL ?>/index.php?page=register">Create one</a></p>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/validate-login.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
