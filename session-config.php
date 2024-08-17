<?php
// config.php

// Define the timeout duration (in seconds)
$timeout_duration = 60; // 15 minutes

// Set session cookie parameters
session_set_cookie_params([
    'lifetime' => $timeout_duration,
    'secure' => true, // Only transmit cookies over HTTPS
    'httponly' => true, // Prevent JavaScript access to session cookies
    'samesite' => 'Strict' // Prevent CSRF attacks
]);

// Start the session
session_start();

// Check if the session has expired
if (isset($_SESSION['last_activity'])) {
    $elapsed_time = time() - $_SESSION['last_activity'];

    if ($elapsed_time >= $timeout_duration) {
        // Session has expired
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>
