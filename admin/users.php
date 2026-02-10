<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['multi_user_sessions']['admin']['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

$adminId = $_SESSION['multi_user_sessions']['admin']['user_id'];

// Fetch admin info
$admin = null;
$admin_query = $conn->prepare("SELECT username, email, role, image FROM users WHERE id = ?");
$admin_query->bind_param("i", $adminId);
$admin_query->execute();
$admin_result = $admin_query->get_result();
if ($admin_result && $admin_result->num_rows > 0) {
    $admin = $admin_result->fetch_assoc();
}

// Handle adding a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Optional: hash password
    // $password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result && $check_result->num_rows > 0) {
        $msg = "Email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if ($stmt->execute()) {
            $msg = "User added successfully.";
        } else {
            $msg = "Error: " . $stmt->error;
        }
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    if ($delete_id != $adminId) {
        $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_stmt->bind_param("i", $delete_id);
        $delete_stmt->execute();
        $msg = "User deleted.";
    } else {
        $msg = "You cannot delete yourself.";
    }
}

// Fetch all users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/logo.png" rel="icon">
    <title>Admin - Manage Food menu</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">
        <!-- Sidebar -->
        <?php include('includes/sidebar.php'); ?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include('includes/header.php'); ?>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Manage Users</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Forms</li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Food</li>
                        </ol>
                    </div>

                    <div class="container-fluid px-4">
                        <?php if (isset($msg)): ?>
                            <div class="alert alert-success w-50"><?= $msg ?></div>
                        <?php endif; ?>

                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Add New Admin or Staff</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-3">
                                            <input type="text" name="username" placeholder="Username"
                                                class="form-control" required>
                                        </div><br>
                                        <div class="col-md-3">
                                            <input type="email" name="email" placeholder="Email" class="form-control"
                                                required>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="password" name="password" placeholder="Password"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="role" class="form-select" required>
                                                <option value="staff">Staff</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button name="add_user" class="btn btn-success w-100">
                                                <i class="fas fa-user-plus me-1"></i> Add User
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle text-center">
                                        <thead class="table-light">
                                            <?php if (isset($success)): ?>
                                                <div class="alert alert-success w-50"><?= $msg ?></div>
                                            <?php endif; ?>

                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($user = mysqli_fetch_assoc($users)): ?>
                                                <tr>
                                                    <td><?= $user['id'] ?></td>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                                    <td><?= ucfirst($user['role']) ?></td>
                                                    <td><?= $user['created_at'] ?></td>
                                                    <td>
                                                        <?php if ($user['id'] != $adminId): ?>
                                                            <a href="?delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Are you sure?')">Delete</a>
                                                        <?php else: ?>
                                                            <span class="badge bg-info">You</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Row-->

                    <!-- Modal Logout -->
                    <?php include('includes/modal.php'); ?>

                </div>
                <!---Container Fluid-->
            </div>
            <!-- Footer -->
            <?php include('includes/footer.php'); ?>
            <!-- Footer -->
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable(); // ID From dataTable 
            $('#dataTableHover').DataTable(); // ID From dataTable with Hover
        });
    </script>

</body>


</html>