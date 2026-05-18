# Food Blog - XAMPP Ready

This version has been reorganized so it can run directly from XAMPP `htdocs`.

## 1. Copy the folder
Place the whole folder here:

```text
C:\xampp\htdocs\food_blog
```

Your final path should look like this:

```text
C:\xampp\htdocs\food_blog\index.php
```

## 2. Start XAMPP
Start both:

- Apache
- MySQL

## 3. Import the database
Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Import this file:

```text
food_blog/config/schema.sql
```

The SQL file creates the `food_blog` database automatically.

## 4. Open the project
Use this URL:

```text
http://localhost/food_blog/
```

## 5. Default admin login

```text
Email: admin@foodblog.com
Password: Admin@1234
```

## Database settings
The project is already configured for default XAMPP MySQL:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'food_blog');
define('DB_USER', 'root');
define('DB_PASS', '');
```

If your MySQL root account has a password, edit:

```text
config/database.php
```

## Project structure

```text
food_blog/
├── index.php              # Main router / front controller
├── .htaccess              # Clean URL rewrite rules
├── config/
│   ├── app.php            # App settings and helper functions
│   ├── database.php       # Database connection
│   └── schema.sql         # Database schema
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

## What was fixed for XAMPP

- Moved the front controller from `public/index.php` to `index.php`.
- Moved `css`, `js`, and `uploads` to the project root so XAMPP can serve them easily.
- Updated `BASE_URL` to `/food_blog`.
- Fixed PHP include paths so they correctly load `config`, `controllers`, `models`, and `views`.
- Added root `.htaccess` rewrite rules for clean URLs.
- Added upload folders: `uploads/menu` and `uploads/profiles`.
- Updated routing so URLs like `/restaurants/1/show` and `/menu-items/1/show` work.

## Troubleshooting

### 404 on clean URLs
In XAMPP, open Apache `httpd.conf` and make sure this line is enabled:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

Also make sure the `htdocs` directory allows `.htaccess` overrides:

```apache
AllowOverride All
```

Restart Apache after changing the config.

### Database connection error
Check that MySQL is running and `config/database.php` matches your local database username/password.

### Upload not working
Make sure these folders exist:

```text
uploads/menu
uploads/profiles
```
