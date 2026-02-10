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

if (!isset($_GET['id'])) {
    die("Food ID missing.");
}

$id = intval($_GET['id']);

// Fetch food item
$stmt = $conn->prepare("SELECT * FROM food_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$food = $result->fetch_assoc();

if (!$food) {
    die("Food item not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $available = isset($_POST['available']) ? 1 : 0;
    $image = $food['image'];

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        $filename = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $filename;
        }
    }

    $stmt = $conn->prepare("UPDATE food_items SET name=?, description=?, price=?, image=?, available=? WHERE id=?");
    $stmt->bind_param("ssdssi", $name, $description, $price, $image, $available, $id);

    if ($stmt->execute()) {
        header("Location: manage_food.php");
        exit();
    } else {
        echo "Error updating item.";
    }
}
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
                        <h1 class="h3 mb-0 text-gray-800">Edit Food menu</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Forms</li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Food</li>
                        </ol>
                    </div>
                    <div class="container-fluid px-4">

                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Update Food Information</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name:</label>
                                        <input type="text" name="name" class="form-control"
                                            value="<?= htmlspecialchars($food['name']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description:</label>
                                        <textarea name="description"
                                            class="form-control"><?= htmlspecialchars($food['description']) ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price:</label>
                                        <input type="number" name="price" class="form-control" step="0.01"
                                            value="<?= $food['price'] ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Current Image:</label><br>
                                        <?php if ($food['image']): ?>
                                            <img src="../uploads/<?= htmlspecialchars($food['image']) ?>" width="100"
                                                class="img-thumbnail">
                                        <?php else: ?>
                                            <p class="text-muted">No image</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Upload New Image:</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>

                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="available" class="form-check-input"
                                            <?= $food['available'] ? 'checked' : '' ?>>
                                        <label class="form-check-label">Available</label>
                                    </div>

                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>
                                        Update</button>
                                    <a href="manage_food.php" class="btn btn-secondary ms-2">Cancel</a>
                                </form>
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