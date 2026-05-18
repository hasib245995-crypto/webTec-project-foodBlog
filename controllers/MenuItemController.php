<?php
require_once __DIR__ . '/../models/MenuItemModel.php';
require_once __DIR__ . '/../models/RestaurantModel.php';
require_once __DIR__ . '/../models/ReviewModel.php';
require_once __DIR__ . '/../config/app.php';

class MenuItemController {
    private MenuItemModel   $items;
    private RestaurantModel $restaurants;
    private ReviewModel     $reviews;

    public function __construct() {
        $this->items       = new MenuItemModel();
        $this->restaurants = new RestaurantModel();
        $this->reviews     = new ReviewModel();
    }

    public function show(int $id): void {
        $item = $this->items->findById($id);
        if (!$item) { http_response_code(404); echo 'Not found'; return; }
        $restaurant = $this->restaurants->findById($item['restaurant_id']);
        $reviews    = $this->reviews->getByMenuItem($id);
        include __DIR__ . '/../views/menu_items/show.php';
    }

    public function create(): void {
        requireAdmin();
        $restaurantId = (int)($_GET['restaurant_id'] ?? 0);
        $restaurant   = $this->restaurants->findById($restaurantId);
        if (!$restaurant) redirect('/restaurants');
        $errors = [];
        $item   = [];
        include __DIR__ . '/../views/menu_items/form.php';
    }

    public function store(): void {
        requireAdmin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/restaurants');
        $restaurantId = (int)($_POST['restaurant_id'] ?? 0);
        $errors = [];
        $data   = $this->validate($_POST, $errors);
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $imagePath = uploadFile($_FILES['image'], MENU_UPLOAD_DIR, 'menu_');
            if (!$imagePath) $errors[] = 'Invalid image (JPEG/PNG, max 2MB).';
        }
        if ($errors) {
            $restaurant = $this->restaurants->findById($restaurantId);
            $item = [];
            include __DIR__ . '/../views/menu_items/form.php';
            return;
        }
        $data['image_path'] = $imagePath;
        $data['restaurant_id'] = $restaurantId;
        $this->items->create($data);
        setFlash('success', 'Menu item added!');
        redirect('/restaurants/' . $restaurantId . '/show');
    }

    public function edit(int $id): void {
        requireAdmin();
        $item = $this->items->findById($id);
        if (!$item) redirect('/restaurants');
        $restaurant = $this->restaurants->findById($item['restaurant_id']);
        $errors = [];
        include __DIR__ . '/../views/menu_items/form.php';
    }

    public function update(int $id): void {
        requireAdmin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/restaurants');
        $item   = $this->items->findById($id);
        $errors = [];
        $data   = $this->validate($_POST, $errors);
        if (!empty($_FILES['image']['name'])) {
            $img = uploadFile($_FILES['image'], MENU_UPLOAD_DIR, 'menu_');
            if (!$img) $errors[] = 'Invalid image (JPEG/PNG, max 2MB).';
            else $data['image_path'] = $img;
        }
        if ($errors) {
            $restaurant = $this->restaurants->findById($item['restaurant_id']);
            include __DIR__ . '/../views/menu_items/form.php';
            return;
        }
        $this->items->update($id, $data);
        setFlash('success', 'Item updated!');
        redirect('/menu-items/' . $id . '/show');
    }

    public function delete(int $id): void {
        requireAdmin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/restaurants');
        $item = $this->items->findById($id);
        $rid  = $item ? $item['restaurant_id'] : 0;
        $this->items->delete($id);
        setFlash('success', 'Item deleted.');
        redirect('/restaurants/' . $rid . '/show');
    }

    private function validate(array $post, array &$errors): array {
        $name  = trim($post['name'] ?? '');
        $price = (float)($post['price'] ?? 0);
        if (!$name) $errors[] = 'Name is required.';
        if ($price <= 0) $errors[] = 'Price must be greater than 0.';
        return ['name' => $name, 'description' => trim($post['description'] ?? ''), 'price' => $price];
    }
}
