<?php
// controllers/ProfileController.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/UserModel.php';

// Show profile page
function showProfile() {
    requireLogin();
    $user = findUserById((int)$_SESSION['user_id']);
    $csrf = generateCsrfToken();
    require __DIR__ . '/../views/profile/profile.php';
}

// Handle profile update POST
function handleProfile() {
    requireLogin();

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect('index.php?page=profile');
    }

    $userId = (int)$_SESSION['user_id'];
    $name   = trim($_POST['name']  ?? '');
    $email  = trim($_POST['email'] ?? '');
    $errors = [];

    // Server-side validation
    if (strlen($name) < 2)
        $errors['name'] = 'Name must be at least 2 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Enter a valid email address.';
    if (empty($errors) && emailExists($email, $userId))
        $errors['email'] = 'Email is already used by another account.';

    // Handle profile picture upload
    $profilePicture = null;
    if (!empty($_FILES['profile_picture']['name'])) {
        $file    = $_FILES['profile_picture'];
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo   = new finfo(FILEINFO_MIME_TYPE);
        $mime    = $finfo->file($file['tmp_name']);

        if (!in_array($mime, $allowed))
            $errors['profile_picture'] = 'Only JPEG, PNG, GIF, WEBP allowed.';
        elseif ($file['size'] > MAX_FILE_SIZE)
            $errors['profile_picture'] = 'File must be under 2MB.';
        else {
            $ext         = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename    = 'user_' . $userId . '_' . time() . '.' . $ext;
            $destination = UPLOAD_DIR . $filename;
            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                $errors['profile_picture'] = 'Failed to upload file.';
            } else {
                $profilePicture = $filename;
            }
        }
    }

    // Handle password change
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password']     ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $changePassword  = !empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword);

    if ($changePassword) {
        $user = findUserById($userId);
        if (!password_verify($currentPassword, $user['password_hash']))
            $errors['current_password'] = 'Current password is incorrect.';
        elseif (strlen($newPassword) < 8)
            $errors['new_password'] = 'New password must be at least 8 characters.';
        elseif ($newPassword !== $confirmPassword)
            $errors['confirm_password'] = 'New passwords do not match.';
    }

    if (!empty($errors)) {
        $user = findUserById($userId);
        $csrf = generateCsrfToken();
        require __DIR__ . '/../views/profile/profile.php';
        return;
    }

    // Save changes
    updateUserProfile($userId, $name, $email, $profilePicture);

    if ($changePassword) {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        updateUserPassword($userId, $hash);
    }

    $_SESSION['name'] = $name;
    setFlash('success', 'Profile updated successfully.');
    redirect('index.php?page=profile');
}