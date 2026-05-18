<?php
// config/database.php

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "food_blog";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}