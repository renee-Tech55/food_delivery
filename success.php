<?php
session_start();
$_SESSION['last_order_token'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Successful</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <style>
    body {
      background-color: #f8f9fa;
      padding: 40px;
    }
    .success-box {
      background: #ffffff;
      border-radius: 10px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .success-icon {
      font-size: 50px;
      color: green;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="success-box">
      <h2 class="mt-3">Thank you for your order!</h2>
      <p>Your order has been placed successfully. We'll start preparing it right away.</p>
      
      <?php if (isset($_SESSION['last_order_id'], $_SESSION['last_order_token'])): ?>
      <!--  <p><strong>Order ID:</strong> <?= htmlspecialchars($_SESSION['last_order_id']) ?></p>-->
        <p><strong>Order Token:</strong> <?= htmlspecialchars($_SESSION['last_order_token']) ?></p>
        <?php 
          unset($_SESSION['last_order_id']); 
          unset($_SESSION['last_order_token']); 
        ?>
      <?php endif; ?>

      <a href="index.php#menu" class="btn btn-primary mt-3">Back to Menu</a>
    </div>
  </div>
</body>
</html>
