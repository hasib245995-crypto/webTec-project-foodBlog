<?php
require_once __DIR__ . '/../config/database.php';

class RestaurantModel {
    private mysqli $db;

    public function __construct() {
        $this->db = getDB();
    }

    // ── Get all restaurants
    public function getAll(): array {
        $result = mysqli_query($this->db, "SELECT * FROM restaurants ORDER BY created_at DESC");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // ── Find restaurant by ID
    public function findById(int $id): ?array {
        $stmt = mysqli_prepare($this->db, "SELECT * FROM restaurants WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $restaurant = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        return $restaurant ?: null;
    }

    // ── Create new restaurant
    public function create(array $data): int {
        $stmt = mysqli_prepare(
            $this->db,
            "INSERT INTO restaurants (name, location, area, short_background, goals)
             VALUES (?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $data['name'],
            $data['location'],
            $data['area'],
            $data['short_background'],
            $data['goals']
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return mysqli_insert_id($this->db);
    }

    // ── Update existing restaurant
    public function update(int $id, array $data): void {
        $stmt = mysqli_prepare(
            $this->db,
            "UPDATE restaurants
             SET name = ?, location = ?, area = ?, short_background = ?, goals = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $data['name'],
            $data['location'],
            $data['area'],
            $data['short_background'],
            $data['goals'],
            $id
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // ── Delete restaurant (menu items will be deleted in ApiController)
    public function delete(int $id): void {
        $stmt = mysqli_prepare($this->db, "DELETE FROM restaurants WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // ── Count total restaurants (for admin dashboard)
    public function count(): int {
        $result = mysqli_query($this->db, "SELECT COUNT(*) AS total FROM restaurants");
        $row = mysqli_fetch_assoc($result);
        return (int)$row['total'];
    }

    // ── Search restaurants (Task 2: optional for member search, can keep)
    public function search(string $q, string $location = '', string $area = ''): array {
        $like = "%$q%";
        $locationLike = "%$location%";
        $areaLike = "%$area%";

        $stmt = mysqli_prepare(
            $this->db,
            "SELECT * FROM restaurants
             WHERE (name LIKE ? OR short_background LIKE ?)
             AND (location LIKE ? OR ? = '')
             AND (area LIKE ? OR ? = '')
             ORDER BY name"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $like,
            $like,
            $locationLike,
            $location,
            $areaLike,
            $area
        );

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $restaurants = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        return $restaurants;
    }
}