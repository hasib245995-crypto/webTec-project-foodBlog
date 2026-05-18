<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'food_blog');
define('DB_USER', 'root');
define('DB_PASS', '');

// Show messages only if this file is opened directly
$showMessages = realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__;

function showMessage($message) {
    global $showMessages;

    if ($showMessages) {
        echo $message . "<br>";
    }
}

function getDB() {
    static $conn = null;

    if ($conn === null) {


        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            showMessage("Connected successfully");
        }

        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;

        if (mysqli_query($conn, $sql)) {
            showMessage("Database created successfully");
        } else {
            die("Error creating database: " . mysqli_error($conn));
        }

        if (mysqli_select_db($conn, DB_NAME)) {
            showMessage("Database selected successfully");
        } else {
            die("Error selecting database: " . mysqli_error($conn));
        }


        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            role ENUM('admin','member') NOT NULL DEFAULT 'member',
            profile_picture VARCHAR(255) DEFAULT NULL,
            remember_token VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if (mysqli_query($conn, $sql)) {
            showMessage("Users table created successfully");
        } else {
            echo "Error creating users table: " . mysqli_error($conn) . "<br>";
        }

        $sql = "CREATE TABLE IF NOT EXISTS restaurants (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            location VARCHAR(150) NOT NULL,
            area VARCHAR(100) NOT NULL,
            short_background TEXT,
            goals TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if (mysqli_query($conn, $sql)) {
            showMessage("Restaurants table created successfully");
        } else {
            echo "Error creating restaurants table: " . mysqli_error($conn) . "<br>";
        }

        $sql = "CREATE TABLE IF NOT EXISTS menu_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            restaurant_id INT NOT NULL,
            name VARCHAR(150) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            image_path VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
        )";

        if (mysqli_query($conn, $sql)) {
            showMessage("Menu items table created successfully");
        } else {
            echo "Error creating menu_items table: " . mysqli_error($conn) . "<br>";
        }

        $sql = "CREATE TABLE IF NOT EXISTS reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            menu_item_id INT NOT NULL,
            user_id INT NOT NULL,
            comment TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        if (mysqli_query($conn, $sql)) {
            showMessage("Reviews table created successfully");
        } else {
            echo "Error creating reviews table: " . mysqli_error($conn) . "<br>";
        }

        $sql = "CREATE TABLE IF NOT EXISTS food_experience_posts (
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
        )";

        if (mysqli_query($conn, $sql)) {
            showMessage("Food experience posts table created successfully");
        } else {
            echo "Error creating food_experience_posts table: " . mysqli_error($conn) . "<br>";
        }

        $sql = "CREATE TABLE IF NOT EXISTS food_experience_comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id INT NOT NULL,
            comment TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES food_experience_posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        if (mysqli_query($conn, $sql)) {
            showMessage("Food experience comments table created successfully");
        } else {
            echo "Error creating food_experience_comments table: " . mysqli_error($conn) . "<br>";
        }

        $admin_password = password_hash("Admin@1234", PASSWORD_DEFAULT);
        $admin_password = mysqli_real_escape_string($conn, $admin_password);

        $sql = "INSERT INTO users (name, email, password_hash, role)
                SELECT 'Admin', 'admin@foodblog.com', '$admin_password', 'admin'
                WHERE NOT EXISTS (
                    SELECT 1 FROM users WHERE email = 'admin@foodblog.com'
                )";

        if (mysqli_query($conn, $sql)) {
            showMessage("Default admin checked/inserted successfully");
        } else {
            echo "Error inserting default admin: " . mysqli_error($conn) . "<br>";
        }

        showMessage("Database setup finished successfully");
    }

    return $conn;
}

$conn = getDB();

?>