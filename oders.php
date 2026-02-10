<?php
session_start();

// Access control
if (!isset($_SESSION['multi_user_sessions']['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['multi_user_sessions']['user'];
$user_id = $user['user_id'] ?? null;

if (!$user_id) {
    // Safety check in case user_id is not set
    header("Location: login.php");
    exit();
}

include 'includes/db_connection.php';

// Fetch all orders for the logged-in user
$order_query = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$order_query->bind_param("i", $user_id);
$order_query->execute();
$orders_result = $order_query->get_result();
// Fetch system settings for homepage image

$setting = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM system_settings WHERE id = 1"));
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="images/favicon.png" type="">

    <title> Home </title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- nice select  -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
        integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ=="
        crossorigin="anonymous" />
    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />

</head>

<body>

    <div class="hero_area">
        <?php if (!empty($setting['homepage_image'])): ?>
            <div class="bg-box">
                <img src="uploads/<?= $setting['homepage_image'] ?>" alt="">
            </div>
        <?php endif; ?>
        <!-- header section strats -->
        <?php include 'includes/topbar.php'; ?>
        <!-- end header section -->
        <!-- slider section -->
        <section class="slider_section ">
            <div id="customCarousel1" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="container ">
                            <div class="row">
                                <div class="col-md-7 col-lg-6 ">
                                    <div class="detail-box">
                                        <h1>
                                            Welcome to Our Food Delivery System
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end slider section -->
    </div>
    <div class="container">
        <h2 class="mb-4">Your Orders</h2>
        <a href="index.php#menu" class="btn btn-secondary mb-3">← Back to Menu</a>
    
    <?php if ($orders_result->num_rows > 0): ?>
    <div class="table-responsive" id="odd">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th> <!-- Counter column -->
                    <th>Order Token</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; ?>
                <?php while ($order = $orders_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $counter++ ?></td> <!-- Display and increment counter -->
                        <td><?= htmlspecialchars($order['order_token']) ?></td>
                        <td><?= ucfirst($order['status']) ?></td>
                        <td><?= $order['created_at'] ?></td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                <?php
                                $order_id = $order['id'];
                                $items_query = $conn->prepare("
                                    SELECT f.name, oi.quantity 
                                    FROM order_items oi
                                    JOIN food_items f ON oi.food_item_id = f.id
                                    WHERE oi.order_id = ?
                                ");
                                $items_query->bind_param("i", $order_id);
                                $items_query->execute();
                                $items_result = $items_query->get_result();

                                while ($item = $items_result->fetch_assoc()):
                                ?>
                                    <li><?= htmlspecialchars($item['name']) ?> <strong>x<?= $item['quantity'] ?></strong></li>
                                <?php endwhile; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">You have no orders yet.</div>
<?php endif; ?>

</div>


    <?php include 'includes/footer.php' ?>
</body>

</html>