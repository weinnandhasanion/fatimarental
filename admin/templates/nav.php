<?php 
$page = basename($_SERVER['PHP_SELF']);
?>

<nav id="sidebar">
  <div class="sidebar-header">
    <h3><span>Fatima Admin</span></h3>
  </div>
  <ul class="list-unstyled components">
    <li class="<?= $page === 'dashboard.php' ? "active" : "" ?>">
      <a href="./dashboard.php" class="dashboard"><i class="material-icons">dashboard</i><span>Dashboard</span></a>
    </li>
    
    <div class="small-screen navbar-display">
      <li class="d-lg-none d-md-block d-xl-none d-sm-block">
        <a href="#"><i class="material-icons">person</i><span>user</span></a>
      </li>
    </div>

    <li class="<?= $page === 'reservations.php' ? "active" : "" ?>">
      <a href="./reservations.php"> <i class="material-icons">library_books</i><span>Reservations</span></a>
    </li>

    <li class="<?= $page === 'tenants.php' ? "active" : "" ?>">
      <a href="./tenants.php">
        <i class="material-icons">group</i><span>Tenants</span></a>
    </li>

    <li class="<?= $page === 'records.php' ? "active" : "" ?>">
      <a href="./records.php">
        <i class="material-icons">feed</i><span>Records</span></a>
    </li>

    <li class="<?= $page === 'billing.php' ? "active" : "" ?>">
      <a href="./billing.php">
        <i class="material-icons">content_copy</i><span>Billing</span></a>
    </li>

    <li class="<?= in_array($page, ['rooms.php', 'edit_room_images.php']) ? "active" : "" ?>">
      <a href="./rooms.php">
        <i class="material-icons">meeting_room</i><span>Rooms</span></a>
    </li>

    <li class="<?= $page === 'payments.php' ? "active" : "" ?>">
      <a href="./payments.php">
        <i class="material-icons">payments</i><span>Payments</span></a>
    </li>

    <li class="<?= $page === 'reports.php' ? "active" : "" ?>">
      <a href="./reports.php">
        <i class="material-icons">receipt_long</i><span>Reports</span></a>
    </li>
  </ul>
</nav>