<?php
session_start();
// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Access control
if (!isset($_SESSION['multi_user_sessions']['admin']['user_id'])) {
    header("Location: ../login.php");
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

$result = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">

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
                        <h1 class="h3 mb-0 text-gray-800">Manage User message</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Forms</li>
                            <li class="breadcrumb-item active" aria-current="page">Manage message</li>
                        </ol>
                    </div>

                    <body class="p-4">
                        <div class="container">
                            <h2 class="mb-4">User Messages</h2>
                            <?php if (isset($_SESSION['msg'])): ?>
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($_SESSION['msg']) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['msg']); ?>
                            <?php endif; ?>

                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= htmlspecialchars($row['name']) ?></td>
                                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
                                                <td><?= $row['created_at'] ?></td>
                                                <td>
                                                    <a href="delete_message.php?id=<?= $row['id'] ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this message?');">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="alert alert-info">No messages found.</div>
                            <?php endif; ?>
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