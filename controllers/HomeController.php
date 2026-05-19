<?php
require_once __DIR__ . '/../models/RestaurantModel.php';

class HomeController {
    public function index(): void {
        $restaurants = (new RestaurantModel())->getAll();
        include __DIR__ . '/../views/home/index.php';
    }
}
