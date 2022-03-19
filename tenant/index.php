<?php 
session_start();
if (isset($_SESSION['user']) && array_key_exists('user_type', $_SESSION['user'])) {
  header("Location: ./../index.php");
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>Hotel Booking Website</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <link rel="stylesheet" href="css/style.css">

  <script src="https://code.jquery.com/jquery-1.12.4.min.js"
    integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>




</head>

<body>
  <section class="head">
    <div class="container flex1">
      <div class="scoial">
        <i class="fab fa-facebook-f"></i>
        <i class="fab fa-twitter"></i>
        <i class="fab fa-instagram"></i>
      </div>
      <div class="logo">
        <!-- <img src="image/logo.png" alt="">-->
      </div>
      <div class="address">
        <i class="fas fa-map-marker-alt"></i>
        <span>FATIMA PROPERTY RENTAL</span>
      </div>
    </div>
  </section>


  <header class="header">
    <div class="container">
      <nav class="navbar flex1">
        <div class="sticky_logo logo">
          <img src="image/logo.png" alt="">
        </div>

        <ul class="nav-menu">
          <li> <a href="#home">Home</a> </li>
          <li> <a href="#about">about</a> </li>
          <li> <a href="#room">room</a> </li>
          <li> <a href="#gallary">gallary</a> </li>
          <li> <a href="#contact">contact</a> </li>
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

        <div class="head_contact">
          <i class="fas fa-phone-volume"></i>
          <span>+000 0000 0000</span>
        </div>
      </nav>
    </div>
  </header>
  <script>
  const hamburger = document.querySelector(".hamburger");
  const navMenu = document.querySelector(".nav-menu");

  hamburger.addEventListener("click", mobliemmenu);

  function mobliemmenu() {
    hamburger.classList.toggle("active");
    navMenu.classList.toggle("active");
  }

  window.addEventListener("scroll", function() {
    var header = document.querySelector("header");
    header.classList.toggle("sticky", window.scrollY > 0)
  })
  </script>


  <section class="home" id="home">
    <div class="container">
      <h1>Fatima Rental</h1>
      <p>Discover the place where you have fun & enjoy a lot</p>


    </div>
  </section>

  <section class="about" id="about">
    <div class="container">
      <div class="heading">
        <h5>EXPLORE</h5>
        <h2>We are cool to give you pleasure
        </h2>
      </div>

      <div class="content flex  top">
        <div class="left">
          <h3>As much as comfort want to get from us everything
          </h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
            ea commodo consequat. Duis
            aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
            sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          <p> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
            laborum.</p>
        </div>
        <div class="right">
          <img src="image/h2.jpg" alt="" style="width: 60%;">
        </div>
      </div>
    </div>
  </section>


  <section class="room wrapper2 top" id="room">
    <div class="container">
      <div class="heading">
        <h5>OUR ROOMS</h5>
        <h2>Fascinating rooms & suites </h2>
      </div>
      <div class="content flex mtop">
        <div class="left grid2">
          <div class="box">
            <i class="fas fa-desktop"></i>
            <p>Free Cost</p>
            <h3>No booking fee</h3>
          </div>
          <div class="box">
            <i class="fas fa-dollar-sign"></i>
            <p>Free Cost</p>
            <h3>Best rate guarantee</h3>
          </div>
          <div class="box">
            <i class="fab fa-resolving"></i>
            <p>Free Cost</p>
            <h3>Reservations 24/7</h3>
          </div>
          <div class="box">
            <i class="fal fa-alarm-clock"></i>
            <p>Free Cost</p>
            <h3>Free High-speed Wi-Fi</h3>
          </div>
          <div class="box">
            <i class="fas fa-mug-hot"></i>
            <p>Free Cost</p>
            <h3>No Curfew</h3>
          </div>
          <div class="box">
            <i class="fas fa-user-tie"></i>
            <p>Free Cost</p>
            <h3>With Aircon</h3>
          </div>
        </div>
        <div class="right">
          <img src="image/IMG_7317.JPG" alt="">
        </div>
      </div>
    </div>
  </section>

  <section class="offer mtop" id="services">
    <div class="container">
      <div class="heading">
        <h5>EXCLUSIVE OFFERS </h5>
        <h3>You can get an exclusive offer </h3>
      </div>

      <div class="content grid2 mtop">
        <div class="box flex">
          <div class="left">
            <img src="image/h3.jpg" alt="">
          </div>
          <div class="right">
            <h4>Room for 2</h4>
            <div class="rate flex">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <p>With Advance Deposit</p>
            <p> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
              laborum.</p>
            <h5>From ₱150/night or 2,500/monthly</h5>
            <button class="flex1" style="cursor: pointer;">
              <span>Reserve Now</span>
              <a href="rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
            </button>
          </div>
        </div>
        <div class="box flex">
          <div class="left">
            <img src="image/h7.jpg" alt="">
          </div>
          <div class="right">
            <h4>Room for 1</h4>
            <div class="rate flex">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <p>With Advance Deposit</p>
            <p> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
              laborum.</p>
            <h5>From ₱150/night or 2,500/monthly</h5>
            <button class="flex1" style="cursor: pointer;">
              <span>Reserve Now</span>
              <a href="rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
            </button>
          </div>
        </div>
        <div class="box flex">
          <div class="left">
            <img src="image/h1.jpg" alt="">
          </div>
          <div class="right">
            <h4>Room for 4</h4>
            <div class="rate flex">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <p>With Advance Deposit</p>
            <p> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
              laborum.</p>
            <h5>From ₱150/night or 2,500/monthly</h5>
            <button class="flex1" style="cursor: pointer;">
              <span>Reserve Now</span>
              <a href="rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
            </button>
          </div>
        </div>
        <div class="box flex">
          <div class="left">
            <img src="image/195321679_439017010505242_3162937492356312932_n.jpg" alt="">
          </div>
          <div class="right">
            <p>With Advance Deposit</p>
            <h4>Room for 2 w/ Aircon</h4>
            <div class="rate flex">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <p> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
              laborum.</p>
            <h5>From ₱250/night or 3,500/monthly</h5>
            <button class="flex1" style="cursor: pointer;">
              <span>Reserve Now</span>
              <a href="rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="area top">
    <div class="container">
      <div class="heading">
        <h5>Place</h5>
        <h3>The area we cover under security</h3>
      </div>

      <div class="content flex mtop">
        <div class="left">
          <img src="image/IMG_7307.JPG" alt="" style="width:70%;">
        </div>
        <div class="right">
          <ul>
            <li>Safe </li>
            <li>No Curfew</li>
            <li>lobby</li>
          </ul>

          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
            ea commodo consequat. Duis
            aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
            sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          <p>Duis
            aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
            sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          <button class="flex1" style="cursor: pointer;">
            <span>Reserve Now</span>
            <a href="rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!--<section class="blog top" id="blog">
    <div class="container">
      <div class="heading">
        <h5>OUR BLOG</h5>
        <h3>News & articles updates</h3>
      </div>

      <div class="content grid mtop">
        <div class="box">
          <div class="img">
            <img src="image/b1.jpg" alt="">
            <span>Room</span>
          </div>
          <div class="text">
            <div class="flex">
              <i class="far fa-user"> <label>Admin</label> </i>
              <i class="far fa-comments"> <label>Comment</label> </i>
            </div>
            <h3></h3>
            <p> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <a href="#">Read More <i class='far fa-long-arrow-alt-right'></i> </a>
          </div>
        </div>
        <div class="box">
          <div class="img">
            <img src="image/b2.jpg" alt="">
            <span>Room</span>
          </div>
          <div class="text">
            <div class="flex">
              <i class="far fa-user"> <label>Admin</label> </i>
              <i class="far fa-comments"> <label>Comment</label> </i>
            </div>
            <h3></h3>
            <p> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <a href="#">Read More <i class='far fa-long-arrow-alt-right'></i> </a>
          </div>
        </div>
        <div class="box">
          <div class="img">
            <img src="image/b3.jpg" alt="">
            <span>Room</span>
          </div>
          <div class="text">
            <div class="flex">
              <i class="far fa-user"> <label>Admin</label> </i>
              <i class="far fa-comments"> <label>Comment</label> </i>
            </div>
            <h3></h3>
            <p> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <a href="#">Read More <i class='far fa-long-arrow-alt-right'></i> </a>
          </div>
        </div>
      </div>
    </div>
  </section><br>-->

  <section class="offer2 about wrapper timer top" id="shop">
    <div class="container">
      <div class="heading">
        <h5 style="color: black;">EXCLUSIVE OFFERS </h5>
        <h3>You can get an exclusive offer </h3>
      </div>

      <div class="content grid  top">
        <div class="box">
          <h3>Good For Two</h3>
          <span>4.5 <label>(432 Reviews)</label> </span>

          <div class="flex">
            <i class="fal fa-alarm-clock"> Duration: 2Hours</i>
            <i class="far fa-dot-circle"> 18+ years</i>
          </div>
          <button class="flex1" style="cursor: pointer;">
            <span>Reserve Now</span>
            <a href="rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
          </button>
        </div>
        <div class="box">
          <h3>Good For Two</h3>
          <span>4.5 <label>(432 Reviews)</label> </span>

          <div class="flex">
            <i class="fal fa-alarm-clock"> Duration: 2Hours</i>
            <i class="far fa-dot-circle"> 18+ years</i>
          </div>
          <button class="flex1" style="cursor: pointer;">
            <span>Reserve Now</span>
            <a href="rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
          </button>
        </div>
        <div class="box">
          <h3>Good For Two</h3>
          <span>4.5 <label>(432 Reviews)</label> </span>

          <div class="flex">
            <i class="fal fa-alarm-clock"> Duration: 2Hours</i>
            <i class="far fa-dot-circle"> 18+ years</i>
          </div>
          <button class="flex1" style="cursor: pointer;">
            <span>Reserve Now</span>
            <a href="rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
          </button>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="container top">
      <div class="subscribe" id="contact">
        <h2>Looking for more Details?</h2>
        <p> Log your email address so we can sent more details thru your email account</p>
        <div class="input flex">
          <input type="email" placeholder="Your Email address">
          <button class="flex1">
            <span>Subscribe</span>
            <i class="fas fa-arrow-circle-right"></i>
          </button>
        </div>
      </div>

      <div class="content grid  top">
        <div class="box">
          <div class="logo">
            <img src="image/logo.png" alt="">
          </div>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
            ea commodo consequat. </p>
          <div class="social flex">
            <i class="fab fa-facebook-f"></i>
            <i class="fab fa-twitter"></i>
            <i class="fab fa-instagram"></i>
          </div>
        </div>

        <div class="box">
          <h2>Quick Links</h2>
          <ul>
            <li><i class="fas fa-angle-double-right"></i>Reservation</li>
            <li><i class="fas fa-angle-double-right"></i>FAQ</li>
            <li><i class="fas fa-angle-double-right"></i>Contact</li>
          </ul>
        </div>

        <div class="box">
          <h2>Services</h2>
          <ul>
            <li><i class="fas fa-angle-double-right"></i>Maintenance</li>
            <li><i class="fas fa-angle-double-right"></i>House Keeping</li>
          </ul>
        </div>

        <div class="box">
          <h2>Services</h2>
          <div class="icon flex">
            <div class="i">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="text">
              <h3>Address</h3>
              <p>Fatima Rental</p>
            </div>
          </div>
          <div class="icon flex">
            <div class="i">
              <i class="fas fa-phone"></i>
            </div>
            <div class="text">
              <h3>Phone</h3>
              <p>+123 456 7898</p>
            </div>
          </div>
          <div class="icon flex">
            <div class="i">
              <i class="far fa-envelope"></i>
            </div>
            <div class="text">
              <h3>Email</h3>
              <p>fatimarental@gmail.com</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
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