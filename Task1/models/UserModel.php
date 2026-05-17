<?php
// models/UserModel.php

require_once __DIR__ . '/../config/database.php';

// Find user by email
function findUserByEmail($email) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user ?: null;
}

// Find user by ID
function findUserById($id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user ?: null;
}

// Find user by remember token
function findUserByToken($token) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE remember_token = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user ?: null;
}

// Check if email exists
function emailExists($email, $excludeId = null) {
    global $conn;
    if ($excludeId) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "si", $email, $excludeId);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_fetch_assoc($result) ? true : false;
    mysqli_stmt_close($stmt);
    return $exists;
}

// Create new user
function createUser($name, $email, $passwordHash, $role) {
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password_hash, role, created_at) VALUES (?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $passwordHash, $role);
    mysqli_stmt_execute($stmt);
    $id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    return $id;
}

// Update profile
function updateUserProfile($id, $name, $email, $profilePicture = null) {
    global $conn;
    if ($profilePicture) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ?, profile_picture = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $profilePicture, $id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $id);
    }
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Update password
function updateUserPassword($id, $passwordHash) {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE users SET password_hash = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $passwordHash, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Set remember token
function setRememberToken($id, $token) {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE users SET remember_token = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $token, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}