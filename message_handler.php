<?php
include 'includes/db_connection.php'; // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $stmt = mysqli_prepare($conn, "INSERT INTO messages (name, phone, email, content) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $email, $content);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p>Thank you for your message!</p>";
    } else {
        echo "<p>Failed to send message. Please try again.</p>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
