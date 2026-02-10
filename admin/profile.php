<?php
session_start();
// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Access control

$user_id = $_SESSION['multi_user_sessions']['admin']['user_id'] ?? null;

if (!$user_id) {
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

// Fetch user details
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $imagePath = $user['image']; // Default to current image path

    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "../uploads/";
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;

        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = "uploads/" . $fileName;

                // Optional: Delete old image file if needed
                // if (!empty($user['image']) && file_exists("../" . $user['image'])) {
                //     unlink("../" . $user['image']);
                // }

            } else {
                $message = "Failed to upload image.";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG & GIF allowed.";
        }
    }

    // Update user details
    if (!empty($password)) {
        $hashedPassword =$password;
        $query = "UPDATE users SET username = '$username', email = '$email', password = '$hashedPassword', image = '$imagePath' WHERE id = $user_id";
    } else {
        $query = "UPDATE users SET username = '$username', email = '$email', image = '$imagePath' WHERE id = $user_id";
    }

    if (mysqli_query($conn, $query)) {
        $message = "Profile updated successfully!";
        $_SESSION['username'] = $username;
        // Refresh data
        $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
        $user = mysqli_fetch_assoc($result);
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Profile</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/logo.png" rel="icon">
    <title>Admin - Manage Profile</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Manage Food menu</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Forms</li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Profile</li>
                        </ol>
                    </div>

                    <div class="container">
                        <h2>Update Profile</h2>
                        <?php if ($message): ?>
                            <div class="alert alert-info"><?= $message ?></div>
                        <?php endif; ?>
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control"
                                    value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>New Password (leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Current Profile Image</label><br>
                                <?php if (!empty($user['image']) && file_exists("../" . $user['image'])): ?>
                                    <img src="../<?= htmlspecialchars($user['image']) ?>" class="profile-preview"
                                        alt="Current Image" width="200px"><br>
                                <?php else: ?>
                                    <small>No image uploaded.</small><br>
                                <?php endif; ?>
                                <label>Upload New Image (optional)</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
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