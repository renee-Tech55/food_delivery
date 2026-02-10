<?php

session_start();
// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Access control
if (!isset($_SESSION['multi_user_sessions']['admin']['user_id'])) {
    header("Location: ../index.php");
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
    <title>Manage Orders</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Manage Food Orders</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Forms</li>
                            <li class="breadcrumb-item active" aria-current="page">Manage All Orders</li>
                        </ol>
                    </div>
                    <div class="container mt-3">
                        <?php if (isset($_SESSION['msg'])): ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_SESSION['msg']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['msg']); ?>
                        <?php endif; ?>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Order Token</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC");
                            while ($order = mysqli_fetch_assoc($orders)) {
                                $order_id = $order['id'];
                                $token = $order['order_token'];
                                $user_id = $order['user_id'];

                                // Get user or guest info
                                if ($user_id) {
                                    $user_res = mysqli_query($conn, "SELECT username FROM users WHERE id = $user_id");
                                    $user = mysqli_fetch_assoc($user_res);
                                    $customer = htmlspecialchars($user['username']);
                                } else {
                                    $guest_res = mysqli_query($conn, "SELECT * FROM guest_info WHERE order_id = $order_id");
                                    $guest = mysqli_fetch_assoc($guest_res);
                                    $customer = htmlspecialchars($guest['fullname']) . "<br><small>" . $guest['phone'] . "</small>";
                                }

                                // Get order items
                                $items = mysqli_query($conn, "SELECT f.name, oi.quantity FROM order_items oi JOIN food_items f ON f.id = oi.food_item_id WHERE oi.order_id = $order_id");
                                $item_list = "";
                                while ($item = mysqli_fetch_assoc($items)) {
                                    $item_list .= "{$item['name']} x{$item['quantity']}<br>";
                                }

                                echo "<tr>
                        <td>#{$order_id}</td>
                        <td>{$customer}</td>
                        <td>{$token}</td>
                        <td>{$item_list}</td>
                        <td>{$order['status']}</td>
                        <td class='d-flex'>
                        <form method='POST' action='update_order_status.php' class='me-2'>
                            <input type='hidden' name='order_id' value='{$order_id}'>
                            <select name='status' class='form-select form-select-sm mb-1'>
                                <option value='pending' " . ($order['status'] == 'pending' ? 'selected' : '') . ">Pending</option>
                                <option value='delivered' " . ($order['status'] == 'delivered' ? 'selected' : '') . ">Delivered</option>
                            </select>
                            <button class='btn btn-sm btn-primary'>Update</button>
                        </form>

                        <form method='POST' action='delete_order.php' onsubmit='return confirm(\"Are you sure you want to delete this order?\")'>
                            <input type='hidden' name='order_id' value='{$order_id}'>
                            <button class='btn btn-sm btn-danger'>Delete</button>
                        </form>
                    </td>

                    </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
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