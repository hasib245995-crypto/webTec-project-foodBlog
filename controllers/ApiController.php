<?php
require_once __DIR__ . '/../models/RestaurantModel.php';
require_once __DIR__ . '/../models/MenuItemModel.php';
require_once __DIR__ . '/../config/app.php';

class ApiController {

    public function deleteMenuItem(int $id): void {
        requireAdmin();

        $menuItemModel = new MenuItemModel();
        $item = $menuItemModel->findById($id);

        if (!$item) {
            jsonResponse([
                'success' => false,
                'message' => 'Menu item not found'
            ], 404);
        }

        $menuItemModel->delete($id);

        jsonResponse([
            'success' => true,
            'message' => 'Menu item deleted successfully'
        ]);
    }

    public function deleteRestaurant(int $id): void {
        requireAdmin(); // session check

        $restaurantModel = new RestaurantModel();
        $restaurant = $restaurantModel->findById($id);

        if (!$restaurant) {
            jsonResponse([
                'success' => false,
                'message' => 'Restaurant not found'
            ], 404);
        }

        $menuItemModel = new MenuItemModel();
        $menuItems = $menuItemModel->getByRestaurant($id);
        foreach ($menuItems as $item) {
            $menuItemModel->delete($item['id']);
        }

        $restaurantModel->delete($id);

        jsonResponse([
            'success' => true,
            'message' => 'Restaurant and all its menu items deleted successfully'
        ]);
    }
}