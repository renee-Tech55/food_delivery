<?php
include 'includes/db_connection.php'; // adjust path if needed

function sanitizeInput($data,$type) {
    switch ($type) {
        case 'email':
            $data = filter_var($data, FILTER_SANITIZE_EMAIL);
            break;
        case 'string':
            $data = filter_var($data, FILTER_SANITIZE_STRING);
            break;
        default:
            $data = htmlspecialchars(stripslashes(trim($data)));
    }
    return $data;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitizeInput($_POST['name'], 'string');
    $phone   = sanitizeInput($_POST['phone'], 'string');
    $email   = sanitizeInput($_POST['email'], 'email');
    $content = sanitizeInput($_POST['content'], 'string');

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
