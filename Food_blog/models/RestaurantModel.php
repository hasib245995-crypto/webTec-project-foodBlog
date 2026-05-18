<?php
require_once __DIR__ . '/../config/database.php';

class RestaurantModel {
    private PDO $db;
    public function __construct() { $this->db = getDB(); }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM restaurants ORDER BY created_at DESC")->fetchAll();
    }

    public function findById(int $id): ?array {
        $s = $this->db->prepare("SELECT * FROM restaurants WHERE id=? LIMIT 1");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function create(array $data): int {
        $s = $this->db->prepare("INSERT INTO restaurants (name,location,area,short_background,goals) VALUES (?,?,?,?,?)");
        $s->execute([$data['name'], $data['location'], $data['area'], $data['short_background'], $data['goals']]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $s = $this->db->prepare("UPDATE restaurants SET name=?,location=?,area=?,short_background=?,goals=? WHERE id=?");
        $s->execute([$data['name'], $data['location'], $data['area'], $data['short_background'], $data['goals'], $id]);
    }

    public function delete(int $id): void {
        $s = $this->db->prepare("DELETE FROM restaurants WHERE id=?");
        $s->execute([$id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM restaurants")->fetchColumn();
    }

    public function search(string $q, string $location, string $area): array {
        $like = "%$q%";
        $s = $this->db->prepare(
            "SELECT * FROM restaurants WHERE (name LIKE ? OR short_background LIKE ?)
             AND (location LIKE ? OR ?='') AND (area LIKE ? OR ?='') ORDER BY name"
        );
        $s->execute([$like, $like, "%$location%", $location, "%$area%", $area]);
        return $s->fetchAll();
    }
}
