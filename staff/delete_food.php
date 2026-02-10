<?php
session_start();
// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Access control
if (!isset($_SESSION['multi_user_sessions']['staff'])) {
    header("Location: ../login.php");
    exit();
}

$staff = $_SESSION['multi_user_sessions']['staff'];
include '../includes/db_connection.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM food_items WHERE id = $id");

header("Location: manage_food.php");
