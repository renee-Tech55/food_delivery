<?php
session_start();
include '../includes/db_connection.php';

// Ensure the user is logged in as admin
if (!isset($_SESSION['multi_user_sessions']['admin']['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$adminId = $_SESSION['multi_user_sessions']['admin']['user_id'];

// Check if `id` is passed in query string
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: users.php?error=Invalid+user+ID");
    exit();
}

$userIdToDelete = intval($_GET['id']);

// Prevent deleting self
if ($userIdToDelete === $adminId) {
    header("Location: users.php?error=You+cannot+delete+yourself");
    exit();
}

// Perform the deletion
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $userIdToDelete);

if ($stmt->execute()) {
    header("Location: users.php?success=User+deleted+successfully");
} else {
    header("Location: users.php?error=Failed+to+delete+user");
}

$stmt->close();
$conn->close();
?>
