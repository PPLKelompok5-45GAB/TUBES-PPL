<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Run this to navigate to the admin dashboard with error display enabled
$_SERVER['REQUEST_URI'] = '/admin/dashboard';
include_once __DIR__ . '/index.php';
?>
