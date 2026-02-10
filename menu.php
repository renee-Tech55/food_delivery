<?php
include 'includes/db_connection.php';

// Fetch only 5 available items
$result = mysqli_query($conn, "SELECT * FROM food_items WHERE available = 1 LIMIT 4");
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

  <title>Food Menu</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
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
    <h2>Menu</h2>
    <a href="cart.php" class="btn btn-info float-end mb-3">View Cart</a>
    <div class="container my-4">
        <div class="row">
            <?php while ($food = mysqli_fetch_assoc($result)) { ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($food['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($food['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($food['name']) ?>">
                    <?php else: ?>
                        <img src="../assets/img/placeholder.png" class="card-img-top" style="height: 200px; object-fit: cover;" alt="No image">
                    <?php endif; ?>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($food['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($food['description']) ?></p>
                        <p class="fw-bold mb-3">Tsh<?= $food['price'] ?></p>
                        
                        <form method="POST" action="cart.php" class="mt-auto">
                            <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                            <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" required>
                            <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- View More Button -->
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
