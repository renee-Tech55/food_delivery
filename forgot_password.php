<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'includes/db_connection.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $token = bin2hex(random_bytes(4)); // 8-character token
            $expiry = gmdate('Y-m-d H:i:s', time() + 3600); // 1 hour in UTC

            $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?");
            $update->bind_param("ssi", $token, $expiry, $user['id']);
            $update->execute();

            $reset_link = "http://localhost/food/reset_password.php?token=$token";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'gody12919@gmail.com';
                $mail->Password = 'dnhw yklv vsvb mgoj';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('gody12919@gmail.com', 'Food Delivery Sys');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "Click this link to reset your password:<br><a href='$reset_link'>$reset_link</a><br>This link expires in 1 hour.";

                $mail->send();
                $message = "A reset link has been sent to your email.";
            } catch (Exception $e) {
                $message = "Mailer error: {$mail->ErrorInfo}";
            }
        } else {
            $message = "Email not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="User login page" />
    <title>Forget Password</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .login-header {
            background-color: #0d6efd;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
            background-color: white;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .btn-login {
            background-color: #0d6efd;
            border: none;
            padding: 12px;
            font-weight: 600;
            width: 100%;
        }
        .btn-login:hover {
            background-color: #0b5ed7;
        }
        .form-floating label {
            padding: 12px 15px;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <h2><i ></i>Forget Password</h2>
                    </div>
                    <div class="login-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                             <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email address</label>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Send Reset Link
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS for form validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Example of client-side validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                
                if (!email.value || !password.value) {
                    e.preventDefault();
                    alert('Please fill in all fields');
                }
            });
        });
    </script>
</body>
</html>
