<?php
session_start();

// Check if a role is specified (e.g., ?role=admin)
if (isset($_GET['role'])) {
    $role = $_GET['role'];

    // Remove only that user's session
    if (isset($_SESSION['multi_user_sessions'][$role])) {
        unset($_SESSION['multi_user_sessions'][$role]);
    }

    // Redirect to login or custom location
    header("Location: index.php");
    exit();
}

// Default: logout all users if no specific role provided
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}
session_destroy();
header("Location: index.php");
exit();
