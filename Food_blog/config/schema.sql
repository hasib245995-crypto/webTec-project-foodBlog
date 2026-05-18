-- Food Blog Database Schema
CREATE DATABASE IF NOT EXISTS food_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE food_blog;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','member') NOT NULL DEFAULT 'member',
    profile_picture VARCHAR(255) DEFAULT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    location VARCHAR(150) NOT NULL,
    area VARCHAR(100) NOT NULL,
    short_background TEXT,
    goals TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_item_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS food_experience_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    post_type ENUM('restaurant','food','both') NOT NULL DEFAULT 'both',
    restaurant_id INT DEFAULT NULL,
    menu_item_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE SET NULL,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS food_experience_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES food_experience_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Default admin account (password: Admin@1234)
INSERT IGNORE INTO users (name, email, password_hash, role)
VALUES ('Admin', 'admin@foodblog.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewLxQBV.bBYGTtLu', 'admin');
