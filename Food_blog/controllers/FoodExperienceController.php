<?php
require_once __DIR__ . '/../models/FoodExperienceModel.php';
require_once __DIR__ . '/../models/RestaurantModel.php';
require_once __DIR__ . '/../models/MenuItemModel.php';
require_once __DIR__ . '/../config/app.php';

class FoodExperienceController {
    private FoodExperienceModel $fe;
    private RestaurantModel     $restaurants;
    private MenuItemModel       $items;

    public function __construct() {
        $this->fe          = new FoodExperienceModel();
        $this->restaurants = new RestaurantModel();
        $this->items       = new MenuItemModel();
    }

    public function index(): void {
        $posts = $this->fe->getAll();
        include __DIR__ . '/../views/food_experience/index.php';
    }

    public function show(int $id): void {
        $post = $this->fe->findById($id);
        if (!$post) { http_response_code(404); echo 'Not found'; return; }
        $comments = $this->fe->getComments($id);
        include __DIR__ . '/../views/food_experience/show.php';
    }

    public function create(): void {
        requireLogin();
        $restaurants = $this->restaurants->getAll();
        $menuItems   = $this->items->getAll();
        $errors = [];
        $post   = [];
        include __DIR__ . '/../views/food_experience/form.php';
    }

    public function store(): void {
        requireLogin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/food-experience');
        $errors = [];
        $data   = $this->validatePost($_POST, $errors);
        if ($errors) {
            $restaurants = $this->restaurants->getAll();
            $menuItems   = $this->items->getAll();
            $post = $_POST;
            include __DIR__ . '/../views/food_experience/form.php';
            return;
        }
        $data['user_id'] = $_SESSION['user_id'];
        $id = $this->fe->create($data);
        setFlash('success', 'Post created!');
        redirect('/food-experience/' . $id . '/show');
    }

    public function edit(int $id): void {
        requireLogin();
        $post = $this->fe->findById($id);
        if (!$post || ($post['user_id'] !== $_SESSION['user_id'] && !isAdmin())) {
            redirect('/food-experience');
        }
        $restaurants = $this->restaurants->getAll();
        $menuItems   = $this->items->getAll();
        $errors = [];
        include __DIR__ . '/../views/food_experience/form.php';
    }

    public function update(int $id): void {
        requireLogin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/food-experience');
        $post = $this->fe->findById($id);
        if (!$post || ($post['user_id'] !== $_SESSION['user_id'] && !isAdmin())) {
            redirect('/food-experience');
        }
        $errors = [];
        $data   = $this->validatePost($_POST, $errors);
        if ($errors) {
            $restaurants = $this->restaurants->getAll();
            $menuItems   = $this->items->getAll();
            include __DIR__ . '/../views/food_experience/form.php';
            return;
        }
        $this->fe->update($id, $data);
        setFlash('success', 'Post updated!');
        redirect('/food-experience/' . $id . '/show');
    }

    public function delete(int $id): void {
        requireLogin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/food-experience');
        $post = $this->fe->findById($id);
        if (!$post || ($post['user_id'] !== $_SESSION['user_id'] && !isAdmin())) {
            redirect('/food-experience');
        }
        $this->fe->delete($id);
        setFlash('success', 'Post deleted.');
        redirect('/food-experience');
    }

    private function validatePost(array $post, array &$errors): array {
        $title   = trim($post['title'] ?? '');
        $content = trim($post['content'] ?? '');
        $type    = in_array($post['post_type'] ?? '', ['restaurant','food','both']) ? $post['post_type'] : 'both';
        if (!$title)   $errors[] = 'Title is required.';
        if (!$content) $errors[] = 'Content is required.';
        return [
            'title'         => $title,
            'content'       => $content,
            'post_type'     => $type,
            'restaurant_id' => (int)($post['restaurant_id'] ?? 0) ?: null,
            'menu_item_id'  => (int)($post['menu_item_id'] ?? 0) ?: null,
        ];
    }
}
