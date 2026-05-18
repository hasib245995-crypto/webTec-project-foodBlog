<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private PDO $db;
    public function __construct() { $this->db = getDB(); }

    public function findByEmail(string $email): ?array {
        $s = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $s->execute([$email]);
        return $s->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $s = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function create(string $name, string $email, string $password, string $role): int {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $s = $this->db->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,?)");
        $s->execute([$name, $email, $hash, $role]);
        return (int)$this->db->lastInsertId();
    }

    public function updateProfile(int $id, string $name, string $email, ?string $pic): void {
        if ($pic) {
            $s = $this->db->prepare("UPDATE users SET name=?,email=?,profile_picture=? WHERE id=?");
            $s->execute([$name, $email, $pic, $id]);
        } else {
            $s = $this->db->prepare("UPDATE users SET name=?,email=? WHERE id=?");
            $s->execute([$name, $email, $id]);
        }
    }

    public function updatePassword(int $id, string $password): void {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $s = $this->db->prepare("UPDATE users SET password_hash=? WHERE id=?");
        $s->execute([$hash, $id]);
    }

    public function setRememberToken(int $id, string $token): void {
        $hash = hash('sha256', $token);
        $s = $this->db->prepare("UPDATE users SET remember_token=? WHERE id=?");
        $s->execute([$hash, $id]);
    }

    public function clearRememberToken(int $id): void {
        $s = $this->db->prepare("UPDATE users SET remember_token=NULL WHERE id=?");
        $s->execute([$id]);
    }

    public function getAllMembers(): array {
        $s = $this->db->prepare("SELECT id,name,email,profile_picture,created_at FROM users WHERE role='member' ORDER BY created_at DESC");
        $s->execute();
        return $s->fetchAll();
    }

    public function delete(int $id): void {
        $s = $this->db->prepare("DELETE FROM users WHERE id=?");
        $s->execute([$id]);
    }
}
