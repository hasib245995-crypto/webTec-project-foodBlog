# FoodBlog

## Overview

FoodBlog is an **online food blogging platform** that allows users to explore restaurants, browse menu items, and share food experiences. Users can interact with the platform based on their role:

* **ADMIN** – Full control over content, users, restaurants, menu items, and moderation.
* **MEMBER** – Registered users who can browse, post reviews, and comment on blog posts.
* **VISITOR** – Non-registered users who can view content but cannot post or comment.

The application is built with **PHP (MVC)**, **MySQL**, and **JavaScript**, and is optimized to run directly in a **XAMPP environment**.

---

## XAMPP Setup Instructions

### 1. Copy the Project Folder

Place the entire project folder in your XAMPP `htdocs` directory:

```
C:\xampp\htdocs\food_blog
```

Your main entry file should be:

```
C:\xampp\htdocs\food_blog\index.php
```

### 2. Start XAMPP

Launch the following services:

* Apache
* MySQL

### 3. Import the Database

1. Open phpMyAdmin at:

```
http://localhost/phpmyadmin
```

2. Import the SQL file:

```
food_blog/config/schema.sql
```

This automatically creates the `food_blog` database with all required tables.

### 4. Open the Project in Browser

Navigate to:

```
http://localhost/food_blog/
```

### 5. Default Admin Login

* Email: `admin@foodblog.com`
* Password: `Admin@1234`

---

## Database Configuration

The project is pre-configured for XAMPP:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'food_blog');
define('DB_USER', 'root');
define('DB_PASS', '');
```

If your MySQL root account uses a password, update `config/database.php` accordingly.

---

## Project Structure

```
food_blog/
├── index.php               # Main front controller
├── .htaccess               # Clean URL rewrite rules
├── config/
│   ├── app.php             # App settings & helper functions
│   ├── database.php        # DB connection
│   └── schema.sql          # Database schema
├── controllers/
├── models/
├── views/
├── css/
│   └── style.css
├── js/
│   └── app.js
└── uploads/
    ├── menu/
    └── profiles/
```

---

## XAMPP-Specific Fixes

* Moved front controller from `public/index.php` to `index.php` for easier XAMPP access.
* CSS, JS, and upload directories relocated to project root.
* BASE_URL updated to `/food_blog`.
* PHP include paths corrected for config, controllers, models, and views.
* Root `.htaccess` added for clean URLs.
* Upload directories created: `uploads/menu` and `uploads/profiles`.
* Routing updated so URLs like `/restaurants/1/show` and `/menu-items/1/show` work correctly.

---

## Troubleshooting

### 404 Errors on Clean URLs

* Ensure Apache `mod_rewrite` is enabled in `httpd.conf`:

```
LoadModule rewrite_module modules/mod_rewrite.so
```

* Allow `.htaccess` overrides in `htdocs`:

```
AllowOverride All
```

* Restart Apache after changes.

### Database Connection Issues

* Verify MySQL is running.
* Ensure `config/database.php` credentials match your local setup.

### File Upload Issues

* Confirm directories exist:

```
uploads/menu
uploads/profiles
```

---

## Features

* Secure authentication and role-based access.
* CRUD operations for restaurants and menu items (Admin).
* Browse, search, and filter restaurants and menu items (Members & Visitors).
* Post reviews on menu items and restaurants (Members).
* Food Experience blog with posts and comments (Members & Admin).
* Responsive UI and clean design.
* Client-side and server-side input validation.
* AJAX endpoints for dynamic content updates.

---

## Contribution

* Follow **Git Flow**: feature branches per task.
* Make meaningful commits (≥3 per student).
* Merge completed tasks via pull requests to `main`.
