<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['multi_user_sessions']['admin']['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    // Delete related items first (foreign key constraints)
    $conn->query("DELETE FROM order_items WHERE order_id = $order_id");
    $conn->query("DELETE FROM guest_info WHERE order_id = $order_id");
    $conn->query("DELETE FROM orders WHERE id = $order_id");
    
    if ($conn->affected_rows > 0) {
        $_SESSION['msg'] = "Order #$order_id deleted successfully.";
    } else {
        $_SESSION['msg'] = "Failed to delete order #$order_id or it does not exist.";
    }
    header('Location: order.php');
}
?>
