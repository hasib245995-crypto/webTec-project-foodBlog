<?php
require_once __DIR__ . '/../models/RestaurantModel.php';
require_once __DIR__ . '/../models/MenuItemModel.php';
require_once __DIR__ . '/../config/app.php';

class RestaurantController {
    private RestaurantModel $restaurants;
    private MenuItemModel   $menuItems;

    public function __construct() {
        $this->restaurants = new RestaurantModel();
        $this->menuItems   = new MenuItemModel();
    }

    public function index(): void {
        $restaurants = $this->restaurants->getAll();
        include __DIR__ . '/../views/restaurants/index.php';
    }

    public function show(int $id): void {
        $restaurant = $this->restaurants->findById($id);
        if (!$restaurant) { http_response_code(404); echo 'Not found'; return; }
        $items = $this->menuItems->getByRestaurant($id);
        include __DIR__ . '/../views/restaurants/show.php';
    }

    public function create(): void {
        requireAdmin();
        $errors = [];
        include __DIR__ . '/../views/restaurants/form.php';
    }

    public function store(): void {
        requireAdmin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/restaurants');
        $data   = $this->validate($_POST);
        $errors = $data['errors'];
        if ($errors) { include __DIR__ . '/../views/restaurants/form.php'; return; }
        $this->restaurants->create($data);
        setFlash('success', 'Restaurant added!');
        redirect('/restaurants');
    }

    public function edit(int $id): void {
        requireAdmin();
        $restaurant = $this->restaurants->findById($id);
        if (!$restaurant) redirect('/restaurants');
        $errors = [];
        include __DIR__ . '/../views/restaurants/form.php';
    }

    public function update(int $id): void {
        requireAdmin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/restaurants');
        $data   = $this->validate($_POST);
        $errors = $data['errors'];
        if ($errors) {
            $restaurant = $this->restaurants->findById($id);
            include __DIR__ . '/../views/restaurants/form.php';
            return;
        }
        $this->restaurants->update($id, $data);
        setFlash('success', 'Restaurant updated!');
        redirect('/restaurants/' . $id . '/show');
    }

    public function delete(int $id): void {
        requireAdmin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/restaurants');
        $this->restaurants->delete($id);
        setFlash('success', 'Restaurant deleted.');
        redirect('/restaurants');
    }

    private function validate(array $post): array {
        $errors = [];
        $name = trim($post['name'] ?? '');
        $location = trim($post['location'] ?? '');
        $area = trim($post['area'] ?? '');
        if (!$name) $errors[] = 'Name is required.';
        if (!$location) $errors[] = 'Location is required.';
        if (!$area) $errors[] = 'Area is required.';
        return [
            'name' => $name, 'location' => $location, 'area' => $area,
            'short_background' => trim($post['short_background'] ?? ''),
            'goals' => trim($post['goals'] ?? ''),
            'errors' => $errors,
        ];
    }
}
