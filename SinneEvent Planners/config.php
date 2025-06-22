<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root'); // Your MySQL username
define('DB_PASSWORD', ''); // Your MySQL password
define('DB_NAME', 'staff_management'); // Your database name

// Create database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

// File upload directory
define('UPLOAD_DIR', 'uploads/');

// Create upload directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
?>