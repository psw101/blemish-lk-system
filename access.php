<?php
session_start();

// Define required roles for the page
$allowed_roles = ['admin'];

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php"); // Redirect to login page
    exit;
}

$user_role = $_SESSION['role'];

if (!in_array($user_role, $allowed_roles)) {
    header("Location: 404.php"); // Redirect to 404 page
    exit;
}
?>
