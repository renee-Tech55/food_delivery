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

// Fetch food items (if needed for dashboard)
$foodItemsResult = mysqli_query($conn, "SELECT * FROM food_items");

// Count helper function
function getCount($conn, $table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM $table");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

$foodCount = getCount($conn, 'food_items');
$orderCount = getCount($conn, 'orders');
$orderItemCount = getCount($conn, 'order_items');
$messageCount = getCount($conn, 'messages');
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/logo.png" rel="icon">
  <title>Admin - Food Order System</title>
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
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Food ItemCount </div>
                      <?php
                      ?>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $foodCount ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Orders</div>
                      <?php
                      ?>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $orderCount ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-shopping-cart fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">message</div>
                      <?php
                      ?>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"> <?= $messageCount ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- Invoice Example -->
        <?php include('includes/modal.php'); ?>
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
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>
</body>

</html>