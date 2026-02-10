<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
    <div class="sidebar-brand-icon">
      Hi! | <?= htmlspecialchars($staffInfo['username']) ?>
    </div>
    <div class="sidebar-brand-text mx-3"></div>
  </a>
  <hr class="sidebar-divider my-0">
  <li class="nav-item active">
    <a class="nav-link" href="dashboard.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
      Features
    </div>
   <li class="nav-item">
    <a class="nav-link" href="manage_food.php">
      <i class="fas fa-fw fa-utensils"></i>
      <span>Manage Food</span>
    </a>
  </li>
    <li class="nav-item">
    <a class="nav-link" href="order.php">
      <i class="fas fa-fw fa-shopping-cart"></i>
      <span>Orders</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="add_food.php">
      <i class="fas fa-fw fa-utensils"></i>
      <span>Add Food</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="messages.php">
      <i class="fas fa-comments"></i>
      <span>Messages</span>
    </a>
  </li>
</ul>