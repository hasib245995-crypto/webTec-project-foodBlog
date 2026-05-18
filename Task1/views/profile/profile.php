<?php
// views/profile/profile.php
require_once __DIR__ . '/../../config/app.php';
$pageTitle = 'My Profile';
require __DIR__ . '/../partials/header.php';
$flashSuccess = getFlash('success');
$flashError   = getFlash('error');
$errors = $errors ?? [];
?>

<div class="page-container">
    <div class="page-header">
        <h1>My Profile</h1>
        <p>Manage your account information</p>
    </div>

    <?php if ($flashSuccess): ?><div class="alert alert-success"><?= e($flashSuccess) ?></div><?php endif; ?>
    <?php if ($flashError):   ?><div class="alert alert-error"><?= e($flashError) ?></div><?php endif; ?>

    <div class="profile-layout">
        <!-- Avatar sidebar -->
        <div class="profile-sidebar">
            <div class="avatar-wrap">
                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="<?= UPLOAD_URL . e($user['profile_picture']) ?>" alt="Profile picture" class="avatar-img">
                <?php else: ?>
                    <div class="avatar-placeholder"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                <?php endif; ?>
            </div>
            <p class="profile-name"><?= e($user['name']) ?></p>
            <span class="role-badge role-<?= e($user['role']) ?>"><?= ucfirst(e($user['role'])) ?></span>
            <p class="profile-since">Member since <?= date('M Y', strtotime($user['created_at'])) ?></p>
        </div>

        <!-- Form -->
        <div class="profile-form-wrap">
            <form id="profileForm" method="POST" action="<?= BASE_URL ?>/index.php?page=profile"
                  enctype="multipart/form-data" novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

                <h2 class="form-section-title">Basic Information</h2>

                <div class="form-group <?= isset($errors['name']) ? 'has-error' : '' ?>">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?= e($user['name']) ?>" required>
                    <?php if (isset($errors['name'])): ?><span class="field-error"><?= e($errors['name']) ?></span><?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= e($user['email']) ?>" required>
                    <?php if (isset($errors['email'])): ?><span class="field-error"><?= e($errors['email']) ?></span><?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['profile_picture']) ? 'has-error' : '' ?>">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/jpeg,image/png,image/gif,image/webp">
                    <small class="field-hint">JPEG, PNG, GIF or WEBP · Max 2 MB</small>
                    <?php if (isset($errors['profile_picture'])): ?><span class="field-error"><?= e($errors['profile_picture']) ?></span><?php endif; ?>
                </div>

                <h2 class="form-section-title">Change Password <small>(leave blank to keep current)</small></h2>

                <div class="form-group <?= isset($errors['current_password']) ? 'has-error' : '' ?>">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" autocomplete="current-password">
                    <?php if (isset($errors['current_password'])): ?><span class="field-error"><?= e($errors['current_password']) ?></span><?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['new_password']) ? 'has-error' : '' ?>">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" autocomplete="new-password" placeholder="Minimum 8 characters">
                    <?php if (isset($errors['new_password'])): ?><span class="field-error"><?= e($errors['new_password']) ?></span><?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['confirm_password']) ? 'has-error' : '' ?>">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password">
                    <?php if (isset($errors['confirm_password'])): ?><span class="field-error"><?= e($errors['confirm_password']) ?></span><?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/validate-profile.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
