<?php
require_once __DIR__ . '/../config/database.php';

class MenuItemModel {
    private mysqli $db;

    public function __construct() {
        $this->db = getDB();
    }

    //Get all menu items of a restaurant
    public function getByRestaurant(int $restaurantId): array {
        $stmt = mysqli_prepare(
            $this->db,
            "SELECT * FROM menu_items WHERE restaurant_id = ? ORDER BY created_at DESC"
        );
        mysqli_stmt_bind_param($stmt, "i", $restaurantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $items;
    }

    //Find menu item by ID (with restaurant info)
    public function findById(int $id): ?array {
        $stmt = mysqli_prepare(
            $this->db,
            "SELECT mi.*, r.name AS restaurant_name, r.id AS restaurant_id
             FROM menu_items mi
             JOIN restaurants r ON r.id = mi.restaurant_id
             WHERE mi.id = ? LIMIT 1"
        );
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $item = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $item ?: null;
    }

    //Create new menu item
    public function create(array $data): int {
        $imagePath = $data['image_path'] ?? null;

        $stmt = mysqli_prepare(
            $this->db,
            "INSERT INTO menu_items (restaurant_id, name, description, price, image_path)
             VALUES (?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            "issds",
            $data['restaurant_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $imagePath
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return mysqli_insert_id($this->db);
    }

    //Update existing menu item
    public function update(int $id, array $data): void {
        if (!empty($data['image_path'])) {
            $stmt = mysqli_prepare(
                $this->db,
                "UPDATE menu_items
                 SET name = ?, description = ?, price = ?, image_path = ?
                 WHERE id = ?"
            );
            mysqli_stmt_bind_param(
                $stmt,
                "ssdsi",
                $data['name'],
                $data['description'],
                $data['price'],
                $data['image_path'],
                $id
            );
        } else {
            $stmt = mysqli_prepare(
                $this->db,
                "UPDATE menu_items
                 SET name = ?, description = ?, price = ?
                 WHERE id = ?"
            );
            mysqli_stmt_bind_param(
                $stmt,
                "ssdi",
                $data['name'],
                $data['description'],
                $data['price'],
                $id
            );
        }
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    //Delete menu item
    public function delete(int $id): void {
        $stmt = mysqli_prepare($this->db, "DELETE FROM menu_items WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    //Count total menu items (for dashboard)
    public function count(): int {
        $result = mysqli_query($this->db, "SELECT COUNT(*) AS total FROM menu_items");
        $row = mysqli_fetch_assoc($result);
        return (int)$row['total'];
    }

    //Get all menu items (with restaurant info)
    public function getAll(): array {
        $result = mysqli_query(
            $this->db,
            "SELECT mi.*, r.name AS restaurant_name
             FROM menu_items mi
             JOIN restaurants r ON r.id = mi.restaurant_id
             ORDER BY mi.created_at DESC"
        );
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
