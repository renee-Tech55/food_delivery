<?php
session_start();
// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Access control
if (!isset($_SESSION['multi_user_sessions']['staff']['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$staff_id = $_SESSION['multi_user_sessions']['staff']['user_id'];


include '../includes/db_connection.php';

// Fetch staff info
$staffInfo = null;
$staff_query = $conn->prepare("SELECT username, email, role, image FROM users WHERE id = ?");
$staff_query->bind_param("i", $staff_id);
$staff_query->execute();
$staff_result = $staff_query->get_result();

if ($staff_result && $staff_result->num_rows > 0) {
    $staffInfo = $staff_result->fetch_assoc();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $allowed_statuses = ['pending', 'preparing', 'delivered', 'cancelled'];
    if (in_array($status, $allowed_statuses)) {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['msg'] = "Order #$order_id status updated to $status.";
        } else {
            $_SESSION['msg'] = "Failed to update order status.";
        }
    } else {
        $_SESSION['msg'] = "Invalid status selected.";
    }
}

header("Location: manage_orders.php");
exit();
