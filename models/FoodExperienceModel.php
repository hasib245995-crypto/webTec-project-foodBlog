<?php
require_once __DIR__ . '/../config/database.php';

class FoodExperienceModel {
    private PDO $db;
    public function __construct() { $this->db = getDB(); }

    public function getAll(): array {
        return $this->db->query(
            "SELECT fep.*, u.name AS author_name,
             r.name AS restaurant_name, mi.name AS item_name
             FROM food_experience_posts fep
             JOIN users u ON u.id=fep.user_id
             LEFT JOIN restaurants r ON r.id=fep.restaurant_id
             LEFT JOIN menu_items mi ON mi.id=fep.menu_item_id
             ORDER BY fep.created_at DESC"
        )->fetchAll();
    }

    public function findById(int $id): ?array {
        $s = $this->db->prepare(
            "SELECT fep.*, u.name AS author_name,
             r.name AS restaurant_name, mi.name AS item_name
             FROM food_experience_posts fep
             JOIN users u ON u.id=fep.user_id
             LEFT JOIN restaurants r ON r.id=fep.restaurant_id
             LEFT JOIN menu_items mi ON mi.id=fep.menu_item_id
             WHERE fep.id=? LIMIT 1"
        );
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function create(array $data): int {
        $s = $this->db->prepare(
            "INSERT INTO food_experience_posts (user_id,title,content,post_type,restaurant_id,menu_item_id)
             VALUES (?,?,?,?,?,?)"
        );
        $s->execute([
            $data['user_id'], $data['title'], $data['content'],
            $data['post_type'],
            $data['restaurant_id'] ?: null,
            $data['menu_item_id'] ?: null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $s = $this->db->prepare(
            "UPDATE food_experience_posts SET title=?,content=?,post_type=?,restaurant_id=?,menu_item_id=? WHERE id=?"
        );
        $s->execute([
            $data['title'], $data['content'], $data['post_type'],
            $data['restaurant_id'] ?: null,
            $data['menu_item_id'] ?: null,
            $id
        ]);
    }

    public function delete(int $id): void {
        $s = $this->db->prepare("DELETE FROM food_experience_posts WHERE id=?");
        $s->execute([$id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM food_experience_posts")->fetchColumn();
    }

    // Comments
    public function getComments(int $postId): array {
        $s = $this->db->prepare(
            "SELECT fec.*, u.name AS user_name FROM food_experience_comments fec
             JOIN users u ON u.id=fec.user_id WHERE fec.post_id=? ORDER BY fec.created_at ASC"
        );
        $s->execute([$postId]);
        return $s->fetchAll();
    }

    public function addComment(int $postId, int $userId, string $comment): int {
        $s = $this->db->prepare("INSERT INTO food_experience_comments (post_id,user_id,comment) VALUES (?,?,?)");
        $s->execute([$postId, $userId, $comment]);
        return (int)$this->db->lastInsertId();
    }

    public function findComment(int $id): ?array {
        $s = $this->db->prepare("SELECT * FROM food_experience_comments WHERE id=? LIMIT 1");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function deleteComment(int $id): void {
        $s = $this->db->prepare("DELETE FROM food_experience_comments WHERE id=?");
        $s->execute([$id]);
    }
}
