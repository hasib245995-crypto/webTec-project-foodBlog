<?php
require_once __DIR__ . '/../models/RestaurantModel.php';
require_once __DIR__ . '/../models/MenuItemModel.php';
require_once __DIR__ . '/../config/app.php';

class AdminController {
    public function dashboard(): void {
        requireAdmin();

        $restaurantModel = new RestaurantModel();
        $menuItemModel   = new MenuItemModel();

        $stats = [
            'restaurants' => $restaurantModel->count(),
            'menu_items'  => $menuItemModel->count(),
            'reviews'     => 0,
            'fe_posts'    => 0,
        ];

        // Load dashboard view
        include __DIR__ . '/../views/admin/dashboard.php';
    }
}