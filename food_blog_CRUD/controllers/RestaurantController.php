<?php
require_once __DIR__ . '/../models/RestaurantModel.php';
require_once __DIR__ . '/../models/MenuItemModel.php';
require_once __DIR__ . '/../config/app.php';

class RestaurantController {
    private RestaurantModel $restaurants;
    private MenuItemModel $menuItems;

    public function __construct() {
        $this->restaurants = new RestaurantModel();
        $this->menuItems = new MenuItemModel();
    }

    //List all restaurants
    public function index(): void {
        $restaurants = $this->restaurants->getAll();
        include __DIR__ . '/../views/restaurants/index.php';
    }

    //Show single restaurant + menu items
    public function show(int $id): void {
        $restaurant = $this->restaurants->findById($id);

        if (!$restaurant) {
            http_response_code(404);
            echo 'Restaurant not found';
            return;
        }

        $items = $this->menuItems->getByRestaurant($id);

        include __DIR__ . '/../views/restaurants/show.php';
    }

    //Create restaurant form
    public function create(): void {
        requireAdmin();

        $errors = [];
        $restaurant = [];

        include __DIR__ . '/../views/restaurants/form.php';
    }

    //Store restaurant
    public function store(): void {
        requireAdmin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/restaurants');

        $data = $this->validate($_POST);
        $errors = $data['errors'];

        if ($errors) {
            $restaurant = $data;
            include __DIR__ . '/../views/restaurants/form.php';
            return;
        }

        unset($data['errors']);
        $this->restaurants->create($data);

        setFlash('success', 'Restaurant added successfully!');
        redirect('/restaurants');
    }

    //Edit restaurant form
    public function edit(int $id): void {
        requireAdmin();

        $restaurant = $this->restaurants->findById($id);
        if (!$restaurant) redirect('/restaurants');

        $errors = [];
        include __DIR__ . '/../views/restaurants/form.php';
    }

    //Update restaurant
    public function update(int $id): void {
        requireAdmin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/restaurants');

        $restaurant = $this->restaurants->findById($id);
        if (!$restaurant) redirect('/restaurants');

        $data = $this->validate($_POST);
        $errors = $data['errors'];

        if ($errors) {
            $restaurant = $data;
            $restaurant['id'] = $id;
            include __DIR__ . '/../views/restaurants/form.php';
            return;
        }

        unset($data['errors']);
        $this->restaurants->update($id, $data);

        setFlash('success', 'Restaurant updated successfully!');
        redirect('/restaurants/' . $id . '/show');
    }

    //Delete restaurant
    public function delete(int $id): void {
        requireAdmin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) redirect('/restaurants');

        $restaurant = $this->restaurants->findById($id);
        if (!$restaurant) redirect('/restaurants');

        // Cascade delete: remove all menu items
        $items = $this->menuItems->getByRestaurant($id);
        foreach ($items as $item) {
            $this->menuItems->delete($item['id']);
        }

        $this->restaurants->delete($id);

        setFlash('success', 'Restaurant and its menu items deleted successfully.');
        redirect('/restaurants');
    }

    //Validate input
    private function validate(array $post): array {
        $errors = [];

        $name = trim($post['name'] ?? '');
        $location = trim($post['location'] ?? '');
        $area = trim($post['area'] ?? '');
        $shortBackground = trim($post['short_background'] ?? '');
        $goals = trim($post['goals'] ?? '');

        if ($name === '') $errors[] = 'Restaurant name is required.';
        if ($location === '') $errors[] = 'Location is required.';
        if ($area === '') $errors[] = 'Area is required.';
        if ($shortBackground === '') $errors[] = 'Short background is required.';
        if ($goals === '') $errors[] = 'Goals are required.';

        return [
            'name' => $name,
            'location' => $location,
            'area' => $area,
            'short_background' => $shortBackground,
            'goals' => $goals,
            'errors' => $errors
        ];
    }
}