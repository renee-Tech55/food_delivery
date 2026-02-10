<?php
session_start();
include 'includes/db_connection.php';

// Generate a unique order token
function generateOrderToken()
{
    return strtoupper(bin2hex(random_bytes(5)) . uniqid());
}

$order_token = generateOrderToken();
$setting = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM system_settings WHERE id = 1"));

// Get user info if logged in
$user_data = [
    'fullname' => '',
    'email' => '',
    'phone' => '',
    'address' => ''
];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT fullname, email, phone, address FROM users WHERE id = $user_id");
    if ($result && mysqli_num_rows($result)) {
        $user_data = mysqli_fetch_assoc($result);
    }
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

     $phone = preg_replace('/\s+/', '', $phone); // Remove whitespace
    if (!preg_match('/^(?:\+255|0)(6|7)[0-9]{8}$/', $phone)) {
        echo "<p style='color:red;'>Invalid Tanzanian phone number format.</p>";
        exit;
    }


    $user_id = $_SESSION['user_id'] ?? null;

    // Insert into orders table
    $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, status, order_token, created_at) VALUES (?, 'pending', ?, NOW())");
    mysqli_stmt_bind_param($stmt, "is", $user_id, $order_token);
    mysqli_stmt_execute($stmt);

    $order_id = mysqli_insert_id($conn);
    $_SESSION['last_order_id'] = $order_id;
    $_SESSION['last_order_token'] = $order_token;

    // Insert each cart item into order_items
    foreach ($_SESSION['cart'] as $food_id => $qty) {
        $check = mysqli_query($conn, "SELECT id FROM food_items WHERE id = $food_id");
        if (mysqli_num_rows($check)) {
            $stmt_item = mysqli_prepare($conn, "INSERT INTO order_items (order_id, food_item_id, quantity) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt_item, "iii", $order_id, $food_id, $qty);
            mysqli_stmt_execute($stmt_item);
        }
    }

    // If user is a guest, store guest info
    if (!$user_id) {
        $stmt = mysqli_prepare($conn, "INSERT INTO guest_info (order_id, fullname, email, phone, address) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issss", $order_id, $name, $email, $phone, $address);
        mysqli_stmt_execute($stmt);
    }

    // Clear cart and redirect
    unset($_SESSION['cart']);
    header("Location: success.php?token=" . urlencode($order_token));
    exit;
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Guest Checkout</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
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
    <h2>Checkout</h2>
    <?php if (isset($_GET['success'])) {
        echo "<p class='text-success'>Order placed successfully!</p>";
        exit();
    } ?>

    <form method="POST">
        <div class="mb-2">
            <label>Full Name</label>
            <input type="text" name="fullname" class="form-control" required
                value="<?= htmlspecialchars($user_data['fullname']) ?>">
        </div>
        <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required
                value="<?= htmlspecialchars($user_data['email']) ?>">
        </div>
        <div class="mb-2">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required
                value="<?= htmlspecialchars($user_data['phone']) ?>">
        </div>
        <div class="mb-2">
            <label>Delivery Address</label>
            <textarea name="address" class="form-control"
                required><?= htmlspecialchars($user_data['address']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Place Order</button>
    </form>

    <?php include 'includes/footer.php'; ?>
</body>

</html>