# FoodBlog

## Overview

FoodBlog is an **online food blogging platform** that allows users to explore restaurants, browse menu items, and share food experiences. The platform supports three types of users:

* **ADMIN** – Manages the platform, including restaurants, menu items, members, and content moderation.
* **MEMBER** – Registered users who can browse restaurants, post reviews, and interact on the "Food Experience" page.
* **VISITOR** – Unregistered users who can view restaurants, menu items, and blog posts but cannot post or comment.

The platform is designed to deliver a **clean, responsive, and interactive user experience** for food enthusiasts.

## Team & Tasks

| Student ID | Task   | Main Features                                                       |
| ---------- | ------ | ------------------------------------------------------------------- |
| 23-53246-3 | Task 1 | User Authentication, Registration, Profile, Home Page, Basic Browse |
| 23-55710-3 | Task 2 | Admin: Restaurant & Menu Item Management (Full CRUD)                |
| 23-53996-3 | Task 3 | Member: Browse, Search & Filtering, Post/Delete Reviews             |
| 23-50895-1 | Task 4 | Food Experience Page, Admin Removal of Members & Comments           |

## Features

### User Management

* Registration and login with secure password hashing and session management.
* Role-based access control with dynamic navigation.
* Profile management including profile picture uploads.

### Admin Features

* Full CRUD operations for restaurants and menu items.
* Dashboard displaying summary statistics (restaurants, menu items, reviews, posts).
* Content moderation: remove members, reviews, and blog posts/comments.

### Member Features

* Browse and search restaurants and menu items using filters (location, area, cuisine, price).
* Post, edit, and delete reviews for menu items and restaurants.
* Comment on “Food Experience” posts.

### Food Experience

* Members and admins can post descriptive reviews.
* Commenting and moderation system for posts.
* Visitors can view content in read-only mode.

## Technical Details

* **Backend:** PHP (MVC architecture)
* **Database:** MySQL (shared schema)
* **Frontend:** HTML, CSS, JavaScript (AJAX support)
* **Security:** SQL injection prevention, XSS protection, CSRF awareness
* **Validation:** Client-side and server-side validation
* **File Uploads:** Profile pictures and menu item images (MIME and size validation)
* **Git Workflow:** Feature branches, meaningful commits, PR merge into protected main branch

## Database Schema

Key tables include:

* `users` – User accounts
* `restaurants` – Restaurant information
* `menu_items` – Menu items for restaurants
* `reviews` – Reviews on menu items
* `food_experience_posts` – Descriptive blog posts
* `food_experience_comments` – Comments on posts

## Installation

1. Clone the repository and navigate to the project root.
2. Import the database schema into MySQL.
3. Update `config/database.php` with your DB credentials.
4. Ensure `public/uploads/` has write permissions.
5. Run the project on a local PHP server (e.g., XAMPP).
6. Access the application at `http://localhost/FoodBlog`.

## Usage

* Admins can log in and manage content.
* Members can register, log in, and interact with restaurants and posts.
* Visitors can browse restaurants, menu items, and posts without logging in.

## Security

* Passwords are stored using `password_hash()`.
* All database queries use prepared statements.
* Sessions are validated for role-based access control.
* Client-side and server-side validation prevents invalid input.

## Contribution

* Follow Git Flow: feature branches per task, at least 3 commits per student.
* Merge completed tasks via pull requests to the main branch.
