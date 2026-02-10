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

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $about = $_POST['about_us'];
$homepageImagePath = '';
$aboutUsImagePath = '';

// Handle homepage image upload
if (isset($_FILES['homepage_image']) && $_FILES['homepage_image']['error'] == 0) {
    $imgName = basename($_FILES['homepage_image']['name']);
    $homepageImagePath = "../uploads/" . $imgName;
    move_uploaded_file($_FILES['homepage_image']['tmp_name'], $homepageImagePath);
}

// Handle about us image upload
if (isset($_FILES['about_us_image']) && $_FILES['about_us_image']['error'] == 0) {
    $imgName = basename($_FILES['about_us_image']['name']);
    $aboutUsImagePath = "../uploads/" . $imgName;
    move_uploaded_file($_FILES['about_us_image']['tmp_name'], $aboutUsImagePath);
}

if (!empty($homepageImagePath) && !empty($aboutUsImagePath)) {
    $stmt = $conn->prepare("UPDATE system_settings SET about_us = ?, homepage_image = ?, about_us_image = ? WHERE id = 1");
    $stmt->bind_param("sss", $about, $homepageImagePath, $aboutUsImagePath);
} elseif (!empty($homepageImagePath)) {
    $stmt = $conn->prepare("UPDATE system_settings SET about_us = ?, homepage_image = ? WHERE id = 1");
    $stmt->bind_param("ss", $about, $homepageImagePath);
} elseif (!empty($aboutUsImagePath)) {
    $stmt = $conn->prepare("UPDATE system_settings SET about_us = ?, about_us_image = ? WHERE id = 1");
    $stmt->bind_param("ss", $about, $aboutUsImagePath);
} else {
    $stmt = $conn->prepare("UPDATE system_settings SET about_us = ? WHERE id = 1");
    $stmt->bind_param("s", $about);
}


    if ($stmt->execute()) {
        $message = "Settings updated successfully.";
    } else {
        $message = "Failed to update settings.";
    }
}

// Load existing settings
$result = mysqli_query($conn, "SELECT * FROM system_settings WHERE id = 1");

if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
} else {
    // Insert default row if it doesn't exist
    mysqli_query($conn, "INSERT INTO system_settings (id, about_us, homepage_image,about_us_image) VALUES (1, '', '','')");

    // Re-fetch the data after inserting
    $result = mysqli_query($conn, "SELECT * FROM system_settings WHERE id = 1");
    $data = mysqli_fetch_assoc($result);
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
    <title>System Settings</title>
    <link href="img/logo/logo.png" rel="icon">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">
        <!-- Sidebar -->
        <?php include ('includes/sidebar.php'); ?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include ('includes/header.php'); ?>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Manage System Settings</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Forms</li>
                            <li class="breadcrumb-item active" aria-current="page">System Settings</li>
                        </ol>
                    </div>

                    <h2>System Settings</h2>
                    <?php if ($message): ?>
                        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="about_us" class="form-label">About Us</label>
                            <textarea class="form-control" id="about_us" name="about_us"
                                rows="5"><?= htmlspecialchars($data['about_us']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="about_us_image" class="form-label">About Us Image</label>
                            <?php if (!empty($data['about_us_image'])): ?>
                                <div class="mb-2">
                                    <img src="<?= $data['about_us_image'] ?>" alt="About Us Image"
                                        style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="about_us_image" name="about_us_image">
                        </div>
                        <div class="mb-3">
                            <label for="homepage_image" class="form-label">Homepage Image</label>
                            <?php if (!empty($data['homepage_image'])): ?>
                                <div class="mb-2">
                                    <img src="<?= $data['homepage_image'] ?>" alt="Current Image" style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="homepage_image" name="homepage_image">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Settings</button>
                    </form>
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

<body class="p-5">
    <div class="container">

    </div>
</body>

</html>