<?php
/**
 * Database Configuration
 * Aryan Uraw Portfolio - Sovryx Tech Pvt. Ltd.
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'aryan_portfolio');

function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        // In production, log this instead of displaying
        error_log("DB Connection failed: " . $conn->connect_error);
        return null;
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Site configuration
define('SITE_NAME', 'Aryan Uraw');
define('SITE_TITLE', 'Acting Managing Director · Sovryx Tech Pvt. Ltd.');
define('SITE_EMAIL', 'aryan@sovryx.com');
define('ADMIN_EMAIL', 'aryan@sovryx.com');
