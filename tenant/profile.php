<?php 
session_start();
if (isset($_SESSION['user']) && array_key_exists('user_type', $_SESSION['user'])) {
  header("Location: ./../index.php");
}
include './../services/connect.php';

$tab = $_GET['tab'] ?? 'details';

$user = $_SESSION['user'];

$sql = "SELECT room_name FROM rooms WHERE id = " . $user['room_id'];
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$user['room_name'] = $row['room_name'];

$tabs = [
  'details' => "./profile/profile_details.php",
  'bills' => './profile/bills.php',
  'payments' => './profile/payments.php',
  'history' => './profile/history.php',
  'change-password' => './profile/change-password.php',
];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" style="height: 100%">

<head>
  <title>Hotel Booking Website</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <link rel="stylesheet" href="css/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"
    integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


  <style>
  .nav-item a {
    color: white;
  }

  .nav-link.active {
    background: white;
    color: #1884cc !important;
  }
  </style>
</head>

<body style="height: 100%">
  <div class="d-flex flex-column" style="height: 100%">
    <header class="header">
      <div class="container">
        <nav class="navbar flex1">
          <div class="sticky_logo logo">
            <img src="./assets/logo.png" alt="">
          </div>

          <ul class="nav-menu">
            <li> <a href="./index.php">Home</a> </li>
            <?php
          if (!isset($_SESSION['user'])) {
          ?>
            <li> <a href="login.php">Login</a> </li>
            <?php
          } else {
          ?>
            <li> <a href="#" id="logout-link">Logout</a> </li>
            <?php
          }
          ?>
          </ul>
          <div class="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
          </div>

        </nav>
      </div>
    </header>

    <section class="home"
      style="display: flex; justify-content: center; align-items: center; height: unset; flex-grow: 1" id="home">
      <div class="container">
        <div class="card" style="height: 500px; padding: 0; border: 0">
          <div class="card-body d-flex" style="padding: 0;">
            <div
              style="height: 100%; min-width: 175px; background-color: #1884cc; border-right: 1px solid #1884cc; padding: 5px; color: white">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link <?= $tab === 'details' ? 'active' : '' ?>" href="./profile.php?tab=details">Profile
                    Details</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?= $tab === 'bills' ? 'active' : '' ?>" href="./profile.php?tab=bills">Bills</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?= $tab === 'payments' ? 'active' : '' ?>"
                    href="./profile.php?tab=payments">Payment History</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?= $tab === 'history' ? 'active' : '' ?>" href="./profile.php?tab=history">Room
                    History</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?= $tab === 'change-password' ? 'active' : '' ?>" href="./profile.php?tab=change-password">
                    Change Password</a>
                </li>
              </ul>
            </div>
            <div style="flex-grow: 1; padding: 25px; text-align: left">
              <?php include $tabs[$tab] ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</body>

<?php include './templates/scripts.php' ?>
<script>
$(document).ready(function() {
  $('#logout-link').click(function(e) {
    e.preventDefault();
    let x = confirm('Do you want to logout?');
    if (x) {
      $.get('./../services/logout.php', function(res) {
        alert(res);
        window.location.href = './login.php';
      });
    }
  });
});
</script>

</html>