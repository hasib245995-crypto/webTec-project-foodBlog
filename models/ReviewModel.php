<?php
require_once __DIR__ . '/../config/database.php';

class ReviewModel {
    private PDO $db;
    public function __construct() { $this->db = getDB(); }

    public function getByMenuItem(int $menuItemId): array {
        $s = $this->db->prepare(
            "SELECT r.*, u.name AS user_name FROM reviews r
             JOIN users u ON u.id=r.user_id
             WHERE r.menu_item_id=? ORDER BY r.created_at DESC"
        );
        $s->execute([$menuItemId]);
        return $s->fetchAll();
    }

    public function findById(int $id): ?array {
        $s = $this->db->prepare("SELECT * FROM reviews WHERE id=? LIMIT 1");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function create(int $menuItemId, int $userId, string $comment): int {
        $s = $this->db->prepare("INSERT INTO reviews (menu_item_id,user_id,comment) VALUES (?,?,?)");
        $s->execute([$menuItemId, $userId, $comment]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): void {
        $s = $this->db->prepare("DELETE FROM reviews WHERE id=?");
        $s->execute([$id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    }

    public function getAll(): array {
        return $this->db->query(
            "SELECT r.*, u.name AS user_name, mi.name AS item_name FROM reviews r
             JOIN users u ON u.id=r.user_id JOIN menu_items mi ON mi.id=r.menu_item_id
             ORDER BY r.created_at DESC"
        )->fetchAll();
    }
}
