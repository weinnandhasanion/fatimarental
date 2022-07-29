<?php 
session_start();
if (isset($_SESSION['user']) && array_key_exists('user_type', $_SESSION['user'])) {
  header("Location: ./../index.php");
}
include './../services/connect.php';

$rooms = [];
$sql = "SELECT * FROM rooms";
$res = $conn->query($sql);
if ($res) $rooms = $res->fetch_all(MYSQLI_ASSOC);
$rooms = array_map(function($room) use ($conn) {
  $room['images'] = [];
  $sql = "SELECT * FROM room_images WHERE room_id = " . $room['id'];
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      $room['images'][] = $row;
    }
  }

  return $room;
}, $rooms);
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
        <!-- <img src="./assets/logo.png" alt="">-->
      </div>
      <div class="address">
        <i class="fas fa-map-marker-alt"></i>
        <span>321-N Escario Street Kamputhaw Cebu City</span>
      </div>
    </div>
  </section>

  <header class="header">
    <div class="container">
      <nav class="navbar flex1">
        <div class="sticky_logo logo">
          <img src="./assets/logo.png" alt="">
        </div>

        <ul class="nav-menu">
          <li> <a href="#home">Home</a> </li>
          <li> <a href="#about">about</a> </li>
          <li> <a href="#room">rooms</a> </li>
          <li> <a href="#contact">contact</a> </li>
          <?php
          if (!isset($_SESSION['user'])) {
          ?>
          <li> <a href="login.php">Login</a> </li>
          <?php
          } else {
          ?>
          <li> <a href="./profile.php">Profile</a> </li>
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
      <h1>Fatima Rental Property</h1>
    </div>
  </section>

  <section class="about" id="about">
    <div class="container">
      <div class="heading">
        <h5>EXPLORE</h5>
        <h2>We can provide safety, security and peaceful environment
        </h2>
      </div>

      <div class="content flex  top">
        <div class="left">
          <h3>As much as comfort want to get from us everything
          </h3>
          <p>One of the current problems that Cebu City faces is the interruption of water supply from time to time.
             In Barangay Kamputhaw, MCWD turns on the water supply between 12 am to 5 am daily
             but sometimes for less time than that. This rotating water interruption
             is surely a discomfort due to the fact that people will have to wake up around midnight
             just to store water for the day. Aside from that, the World Health Organization (WHO) declared that water shortages will 
             affect the spread of pathogenic organisms, such as bacteria and viruses, because the lack of water will limit handwashing and compromise
              the cleaning and sanitation of homes and health care facilities.
            sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          <p>That is why Fatima Rental has installed a water tank below the ground to alleviate
             this problem. To add, Fatima Rental has also passed the Water
             Quality and Mineral Content Tests to ensure that boarders can have access to clean water 24/7p>
        </div>
        <div class="right">
          <img src="./assets/h2.jpg" alt="" style="width: 60%;">
        </div>
      </div>
    </div>
  </section>


  <section class="room wrapper2 top" id="room">
    <div class="container">
      <div class="heading">
        <h5>OUR ROOMS</h5>
        <h2>Fascinating rooms</h2>
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
          <img src="./assets/IMG_7317.JPG" alt="">
        </div>
      </div>
    </div>
  </section>

  <section class="offer mtop" id="services">
    <div class="container">
      <div class="heading">
        <h5>Rooms</h5>
        <h3>All the rooms to offer</h3>
      </div>

      <div class="content grid2 mtop">
        <?php foreach($rooms as $room): ?>
        <div class="box flex">
          <div class="left">
            <img src="./../uploads/<?= $room['images'][0]['image_pathname'] ?>" alt="">
          </div>
          <div class="right">
            <h4>Room <?= $room['room_name'] ?></h4>
            <div class="rate flex">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <p><?= $room['description'] ?></p>
            <h5>₱<?= $room['price'] ?>.00</h5>
            <button class="flex1" style="cursor: pointer;">
              <span>Reserve Now</span>
              <a href="rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="area top" style="margin-bottom: 80px">
    <div class="container">
      <div class="heading">
        <h5>Place</h5>
        <h3>The area we cover under security</h3>
      </div>

      <div class="content flex mtop">
        <div class="left">
          <img src="./assets/IMG_7307.JPG" alt="" style="width:70%;">
        </div>
        <div class="right">
          <ul>
            <li>Safe </li>
            <li>No Curfew</li>
            <li>lobby</li>
          </ul>

          <p>This picture was shot at the 3rd floor in Fatima Rental Boarding House.
             The surrounding area is spacious with sofas and furniture around.
             The room has a double deck and has its own comfort room. What makes this
             room unique is the scenery of the main busy road in Escario Street and the view of Goldberry Hotel. </p>
          <p>Aside from that, there is a balcony that is above the chapel where lodgers can participate
             may it be in ceremonies or announcements especially every October which 
             is the Feast of Our Lady of Fatima
             where the district Sitio Fatima is named. </p>
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
            <img src="./assets/b1.jpg" alt="">
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
            <img src="./assets/b2.jpg" alt="">
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
            <img src="./assets/b3.jpg" alt="">
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
  <!-- 
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
  </section> -->

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
            <img src="./assets/logo.png" alt="">
          </div>
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
              <p>321-N Escario Street Kamputhaw Cebu City</p>
            </div>
          </div>
          <div class="icon flex">
            <div class="i">
              <i class="fas fa-phone"></i>
            </div>
            <div class="text">
              <h3>Phone</h3>
              <p>4171 6862</p>
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