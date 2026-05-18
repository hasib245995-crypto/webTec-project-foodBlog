-- schema.sql
-- Shared database schema for Food Blog project
-- Run once before starting the application

CREATE DATABASE IF NOT EXISTS food_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE food_blog;

-- Users table (Task 1 owns this)
CREATE TABLE IF NOT EXISTS users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)  NOT NULL,
    email           VARCHAR(150)  NOT NULL UNIQUE,
    password_hash   VARCHAR(255)  NOT NULL,
    role            ENUM('admin','member') NOT NULL DEFAULT 'member',
    profile_picture VARCHAR(255)  DEFAULT NULL,
    remember_token  VARCHAR(64)   DEFAULT NULL,
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Restaurants (Task 2 populates via CRUD)
CREATE TABLE IF NOT EXISTS restaurants (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name              VARCHAR(150) NOT NULL,
    location          VARCHAR(150) NOT NULL,
    area              VARCHAR(100) DEFAULT NULL,
    short_background  TEXT         DEFAULT NULL,
    goals             TEXT         DEFAULT NULL,
    created_at        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Menu items (Task 2 owns CRUD)
CREATE TABLE IF NOT EXISTS menu_items (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT UNSIGNED NOT NULL,
    name          VARCHAR(150) NOT NULL,
    description   TEXT         DEFAULT NULL,
    price         DECIMAL(10,2) NOT NULL CHECK (price > 0),
    image_path    VARCHAR(255) DEFAULT NULL,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Reviews on menu items (Task 3 owns Create/Delete; Task 4 Admin deletes)
CREATE TABLE IF NOT EXISTS reviews (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    menu_item_id INT UNSIGNED NOT NULL,
    user_id      INT UNSIGNED NOT NULL,
    comment      TEXT         NOT NULL,
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)      REFERENCES users(id)      ON DELETE CASCADE
) ENGINE=InnoDB;

-- Food experience posts (Task 4 owns)
CREATE TABLE IF NOT EXISTS food_experience_posts (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       INT UNSIGNED NOT NULL,
    title         VARCHAR(200) NOT NULL,
    content       TEXT         NOT NULL,
    post_type     ENUM('restaurant','food','both') NOT NULL DEFAULT 'both',
    restaurant_id INT UNSIGNED DEFAULT NULL,
    menu_item_id  INT UNSIGNED DEFAULT NULL,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)       REFERENCES users(id)       ON DELETE CASCADE,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE SET NULL,
    FOREIGN KEY (menu_item_id)  REFERENCES menu_items(id)  ON DELETE SET NULL
) ENGINE=InnoDB;

-- Food experience comments (Task 4 owns)
CREATE TABLE IF NOT EXISTS food_experience_comments (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id    INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NOT NULL,
    comment    TEXT         NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id)  REFERENCES food_experience_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)  REFERENCES users(id)                 ON DELETE CASCADE
) ENGINE=InnoDB;
