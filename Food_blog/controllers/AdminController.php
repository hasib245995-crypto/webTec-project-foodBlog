<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/RestaurantModel.php';
require_once __DIR__ . '/../models/MenuItemModel.php';
require_once __DIR__ . '/../models/ReviewModel.php';
require_once __DIR__ . '/../models/FoodExperienceModel.php';
require_once __DIR__ . '/../config/app.php';

class AdminController {
    public function dashboard(): void {
        requireAdmin();
        $stats = [
            'restaurants' => (new RestaurantModel())->count(),
            'menu_items'  => (new MenuItemModel())->count(),
            'reviews'     => (new ReviewModel())->count(),
            'fe_posts'    => (new FoodExperienceModel())->count(),
        ];
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    public function members(): void {
        requireAdmin();
        $members = (new UserModel())->getAllMembers();
        include __DIR__ . '/../views/admin/members.php';
    }

    public function reviews(): void {
        requireAdmin();
        $reviews = (new ReviewModel())->getAll();
        include __DIR__ . '/../views/admin/reviews.php';
    }
}
