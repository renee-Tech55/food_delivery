<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['multi_user_sessions']['admin']['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['msg'] = "Message deleted successfully.";
    } else {
        $_SESSION['msg'] = "Failed to delete the message or it doesn't exist.";
    }

    $stmt->close();
}

header("Location: messages.php"); // Change if your file has a different name
exit();
?>