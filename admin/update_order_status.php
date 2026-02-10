<?php
session_start();
// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Access control
if (!isset($_SESSION['multi_user_sessions']['admin']['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$adminId = $_SESSION['multi_user_sessions']['admin']['user_id'] ?? null;

include '../includes/db_connection.php';

$admin = null;
$admin_query = $conn->prepare("SELECT username, email, role, image FROM users WHERE id = ?");
$admin_query->bind_param("i", $adminId);
$admin_query->execute();
$admin_result = $admin_query->get_result();
if ($admin_result && $admin_result->num_rows > 0) {
    $admin = $admin_result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $allowed_statuses = ['pending', 'preparing', 'delivered', 'cancelled'];
    if (in_array($status, $allowed_statuses)) {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['msg'] = "Order #$order_id updated to '$status'.";
        } else {
            $_SESSION['msg'] = "Failed to update order #$order_id.";
        }
    } else {
        $_SESSION['msg'] = "Invalid status.";
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
