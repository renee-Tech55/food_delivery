<?php
include 'includes/db_connection.php';

$token = $_GET['token'] ?? '';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Use UTC time in query to match saved time
    $now = gmdate('Y-m-d H:i:s');

    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > ?");
    $stmt->bind_param("ss", $token, $now);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
        $update->bind_param("si", $password, $user['id']);
        $update->execute();

        $message = "Password reset successful. href='login.php'>Log in</a>";
    } else {
        $message = "Invalid or expired token.";
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
                        <h2><i></i>Reset Password</h2>
                    </div>

                    <div class="login-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $message ? "<p>$message</p>" : '' ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php if (!$message && $token): ?>
                            <form method="POST" action="">
                                <div class="form-floating mb-3">
                                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                                    <input type="text" class="form-control" id="email" name="password" required>
                                    <label for="email"><i class="fas fa-envelope me-2"></i>New Password</label>
                                </div>
                            <?php elseif (!$token): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p>Invalid access.</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php endif; ?>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i> Reset Now
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
        document.addEventListener('DOMContentLoaded', function () {
            // Example of client-side validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
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