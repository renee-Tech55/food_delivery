<?php
include 'includes/db_connection.php';

$message = "";

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
     break;
    }
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = sanitizeInput($_POST['username'], 'string');
    $email    = sanitizeInput($_POST['email'], 'email');
    $password = $_POST['password'];
    $role     = 'user'; // Default role

    // Hash password (SECURE)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {

        $message = "Error: Email address already exists.";

    } else {

        // Insert new user
        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

        if ($stmt->execute()) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Error: " . $stmt->error;

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
    <title>Sign Up | Your Application</title>

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

    <body class="bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white text-center">
                            <h4>Sign Up</h4>
                        </div>
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
                        <?php endif; ?>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Register</button>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <small>Already have an account? <a href="login.php">Login</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

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