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
      <li class="dropdown d-lg-none d-md-block d-xl-none d-sm-block">
        <a href="#homeSubmenu0" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
          <i class="material-icons">notifications</i><span> notification</span></a>
        <ul class="collapse list-unstyled menu" id="homeSubmenu0">
          <li>
            <a href="#">You have 5 new messages</a>
          </li>
        </ul>
      </li>
      <li class="d-lg-none d-md-block d-xl-none d-sm-block">
        <a href="#"><i class="material-icons">person</i><span>user</span></a>
      </li>
      <li class="d-lg-none d-md-block d-xl-none d-sm-block">
        <a href="#"><i class="material-icons">settings</i><span>setting</span></a>
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
  </ul>
</nav>