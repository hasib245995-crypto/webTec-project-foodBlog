<?php
require_once __DIR__ . '/../config/database.php';

class MenuItemModel {
    private PDO $db;
    public function __construct() { $this->db = getDB(); }

    public function getByRestaurant(int $restaurantId): array {
        $s = $this->db->prepare("SELECT * FROM menu_items WHERE restaurant_id=? ORDER BY created_at DESC");
        $s->execute([$restaurantId]);
        return $s->fetchAll();
    }

    public function findById(int $id): ?array {
        $s = $this->db->prepare("SELECT mi.*, r.name AS restaurant_name, r.id AS restaurant_id FROM menu_items mi JOIN restaurants r ON r.id=mi.restaurant_id WHERE mi.id=? LIMIT 1");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function create(array $data): int {
        $s = $this->db->prepare("INSERT INTO menu_items (restaurant_id,name,description,price,image_path) VALUES (?,?,?,?,?)");
        $s->execute([$data['restaurant_id'], $data['name'], $data['description'], $data['price'], $data['image_path'] ?? null]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void {
        if (!empty($data['image_path'])) {
            $s = $this->db->prepare("UPDATE menu_items SET name=?,description=?,price=?,image_path=? WHERE id=?");
            $s->execute([$data['name'], $data['description'], $data['price'], $data['image_path'], $id]);
        } else {
            $s = $this->db->prepare("UPDATE menu_items SET name=?,description=?,price=? WHERE id=?");
            $s->execute([$data['name'], $data['description'], $data['price'], $id]);
        }
    }

    public function delete(int $id): void {
        $s = $this->db->prepare("DELETE FROM menu_items WHERE id=?");
        $s->execute([$id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();
    }

    public function search(string $q): array {
        $like = "%$q%";
        $s = $this->db->prepare("SELECT mi.*, r.name AS restaurant_name FROM menu_items mi JOIN restaurants r ON r.id=mi.restaurant_id WHERE mi.name LIKE ? OR mi.description LIKE ? ORDER BY mi.name");
        $s->execute([$like, $like]);
        return $s->fetchAll();
    }

    public function getAll(): array {
        return $this->db->query("SELECT mi.*, r.name AS restaurant_name FROM menu_items mi JOIN restaurants r ON r.id=mi.restaurant_id ORDER BY mi.created_at DESC")->fetchAll();
    }
}
