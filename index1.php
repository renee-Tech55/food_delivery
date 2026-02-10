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

  <title> Home </title>

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
                    <h1 style="color: yellow;">
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

  <!-- food section -->

  <section class="food_section layout_padding-bottom">
    <div class="container" id="menu">
      <div class="heading_container heading_center">
        <h2>
          Our Menu
        </h2>
      </div>
      <a href="cart.php" class="btn btn-info float-end mb-3">View Cart</a>
      <div class="container my-4">
        <div class="row">
          <?php while ($food = mysqli_fetch_assoc($result)) { ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
              <div class="card h-100 shadow-sm">
                <?php if (!empty($food['image'])): ?>
                  <img src="uploads/<?= htmlspecialchars($food['image']) ?>" class="card-img-top"
                    style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($food['name']) ?>">
                <?php else: ?>
                  <img src="../assets/img/placeholder.png" class="card-img-top" style="height: 200px; object-fit: cover;"
                    alt="No image">
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

        <div class="btn-box">
          <a href="menu_details.php">
            View More
          </a>
        </div>
      </div>
  </section>

  <!-- end food section -->

  <!-- about section -->

  <section class="about_section layout_padding" id="about">
    <div class="container  ">
      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
            <?php if (!empty($setting['about_us_image'])): ?>
            <img src="uploads/<?= $setting['about_us_image'] ?>" alt="">
          </div>
          <?php endif; ?>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                We Are Providing Good Services.
              </h2>
            </div>
            <p>
              <?= nl2br(htmlspecialchars($setting['about_us'])) ?>
            </p>
            <!--<a href="">
              Read More
            </a>-->
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  <!-- Message section -->
  <section class="book_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2 style="font-family:arial;">Message Us in case You encounter confusion</h2>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form_container">
            <form action="message_handler.php" method="POST">
              <div>
                <input type="text" name="name" class="form-control" placeholder="Your Name" required />
              </div>
              <div>
                <input type="text" name="phone" class="form-control" placeholder="Phone Number" />
              </div>
              <div>
                <input type="email" name="email" class="form-control" placeholder="Your Email" required />
              </div>
              <div>
                <textarea name="content" class="form-control" placeholder="Your Message Here" required></textarea>
              </div>
              <div class="btn_box">
                <button type="submit">Send Now</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end book section -->

  <!-- client section -->

  <!-- end client section -->

  <!-- footer section -->
  <?php include 'includes/footer.php' ?>
  <!-- footer section -->

  <!-- jQery -->
  <script src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- isotope js -->
  <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
  </script>
  <!-- End Google Map -->

</body>

</html>