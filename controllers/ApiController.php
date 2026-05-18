<?php
require_once __DIR__ . '/../models/ReviewModel.php';
require_once __DIR__ . '/../models/RestaurantModel.php';
require_once __DIR__ . '/../models/MenuItemModel.php';
require_once __DIR__ . '/../models/FoodExperienceModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/app.php';

class ApiController {

    // GET /api/search?q=&location=&area=
    public function search(): void {
        $q        = trim($_GET['q'] ?? '');
        $location = trim($_GET['location'] ?? '');
        $area     = trim($_GET['area'] ?? '');
        $restaurants = (new RestaurantModel())->search($q, $location, $area);
        $items       = (new MenuItemModel())->search($q);
        jsonResponse(['restaurants' => $restaurants, 'items' => $items]);
    }

    // POST /api/reviews/add
    public function addReview(): void {
        if (!isMember()) jsonResponse(['error' => 'Unauthorized'], 401);
        $body        = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $menuItemId  = (int)($body['menu_item_id'] ?? 0);
        $comment     = trim($body['comment'] ?? '');
        if (!$menuItemId || !$comment) jsonResponse(['error' => 'Missing fields'], 422);
        if (strlen($comment) > 1000) jsonResponse(['error' => 'Comment too long'], 422);
        $item = (new MenuItemModel())->findById($menuItemId);
        if (!$item) jsonResponse(['error' => 'Item not found'], 404);
        $id = (new ReviewModel())->create($menuItemId, $_SESSION['user_id'], $comment);
        jsonResponse(['success' => true, 'id' => $id, 'user_name' => $_SESSION['name'], 'comment' => h($comment), 'created_at' => date('Y-m-d H:i:s')]);
    }

    // DELETE /api/reviews/{id}
    public function deleteReview(int $id): void {
        if (!isLoggedIn()) jsonResponse(['error' => 'Unauthorized'], 401);
        $rm     = new ReviewModel();
        $review = $rm->findById($id);
        if (!$review) jsonResponse(['error' => 'Not found'], 404);
        if ($review['user_id'] !== $_SESSION['user_id'] && !isAdmin()) {
            jsonResponse(['error' => 'Forbidden'], 403);
        }
        $rm->delete($id);
        jsonResponse(['success' => true]);
    }

    // POST /api/food-exp/comments/add
    public function addComment(): void {
        if (!isLoggedIn()) jsonResponse(['error' => 'Unauthorized'], 401);
        $body    = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $postId  = (int)($body['post_id'] ?? 0);
        $comment = trim($body['comment'] ?? '');
        if (!$postId || !$comment) jsonResponse(['error' => 'Missing fields'], 422);
        $fe = new FoodExperienceModel();
        if (!$fe->findById($postId)) jsonResponse(['error' => 'Post not found'], 404);
        $id = $fe->addComment($postId, $_SESSION['user_id'], $comment);
        jsonResponse(['success' => true, 'id' => $id, 'user_name' => $_SESSION['name'], 'comment' => h($comment), 'created_at' => date('Y-m-d H:i:s')]);
    }

    // DELETE /api/food-exp/comments/{id}
    public function deleteComment(int $id): void {
        if (!isLoggedIn()) jsonResponse(['error' => 'Unauthorized'], 401);
        $fe      = new FoodExperienceModel();
        $comment = $fe->findComment($id);
        if (!$comment) jsonResponse(['error' => 'Not found'], 404);
        if ($comment['user_id'] !== $_SESSION['user_id'] && !isAdmin()) jsonResponse(['error' => 'Forbidden'], 403);
        $fe->deleteComment($id);
        jsonResponse(['success' => true]);
    }

    // DELETE /api/food-exp/{id}
    public function deleteFoodExpPost(int $id): void {
        if (!isLoggedIn()) jsonResponse(['error' => 'Unauthorized'], 401);
        $fe   = new FoodExperienceModel();
        $post = $fe->findById($id);
        if (!$post) jsonResponse(['error' => 'Not found'], 404);
        if ($post['user_id'] !== $_SESSION['user_id'] && !isAdmin()) jsonResponse(['error' => 'Forbidden'], 403);
        $fe->delete($id);
        jsonResponse(['success' => true]);
    }

    // Admin endpoints
    public function deleteMember(int $id): void {
        if (!isAdmin()) jsonResponse(['error' => 'Forbidden'], 403);
        (new UserModel())->delete($id);
        jsonResponse(['success' => true]);
    }

    public function adminDeleteReview(int $id): void {
        if (!isAdmin()) jsonResponse(['error' => 'Forbidden'], 403);
        (new ReviewModel())->delete($id);
        jsonResponse(['success' => true]);
    }

    public function adminDeleteFEPost(int $id): void {
        if (!isAdmin()) jsonResponse(['error' => 'Forbidden'], 403);
        (new FoodExperienceModel())->delete($id);
        jsonResponse(['success' => true]);
    }

    public function adminDeleteFEComment(int $id): void {
        if (!isAdmin()) jsonResponse(['error' => 'Forbidden'], 403);
        (new FoodExperienceModel())->deleteComment($id);
        jsonResponse(['success' => true]);
    }
}
