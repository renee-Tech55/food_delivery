<?php
session_start();

include 'includes/db_connection.php';

if (isset($_GET['clear_cart'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
}


if (isset($_POST['add_to_cart'])) {
    $food_id = $_POST['food_id'];
    $qty = $_POST['quantity'];

    if (isset($_SESSION['cart'][$food_id])) {
        $_SESSION['cart'][$food_id] += $qty;
    } else {
        $_SESSION['cart'][$food_id] = $qty;
    }
    header("Location: cart.php");
}

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php");
}

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

    <title>Your Cart</title>

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
    <link>
</head>

<body class="p-3">
    <div class="hero_area">
        <?php if (!empty($setting['homepage_image'])): ?>
            <div class="bg-box">
                <img src="uploads/<?= $setting['homepage_image'] ?>" alt="">
            </div>
        <?php endif; ?>
        <!-- header section strats -->
        <!-- end header section -->
         <?php include 'includes/topbar.php'; ?>
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

    <h2 id="cart">Your Cart</h2>
    <a href="index.php#menu" class="btn btn-secondary mb-3">← Back to Menu</a>

    <?php
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        $total = 0;
        echo "<table class='table align-middle'>";
        echo "<tr><th>Name</th><th>Qty</th><th>Price</th><th>Image</th><th>Total</th><th>Action</th></tr>";

        foreach ($_SESSION['cart'] as $food_id => $qty) {
            $res = mysqli_query($conn, "SELECT * FROM food_items WHERE id = $food_id");
            $item = mysqli_fetch_assoc($res);
            $item_total = $item['price'] * $qty;
            $total += $item_total;

            // Display food item with image
            echo "<tr>
                <td>" . htmlspecialchars($item['name']) . "</td>
                <td>$qty</td>
                <td>Tsh{$item['price']}</td>
                <td><img src='uploads/" . htmlspecialchars($item['image']) . "' width='80' height='80' style='object-fit: cover;' alt='" . htmlspecialchars($item['name']) . "'></td>
                <td>Tsh$item_total</td>
                <td><a href='cart.php?remove=$food_id' class='btn btn-sm btn-danger'>Remove</a></td>
            </tr>";
        }

        echo "<tr><td colspan='4'><strong>Total</strong></td><td><strong>Tsh$total</strong></td><td></td></tr>";
        echo "</table>";
        echo "<a href='checkout.php' class='btn btn-success'>Proceed to Checkout</a>";
    }
    ?>

    <?php include 'includes/footer.php'; ?>

    <br><br>
</body>

</html>