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
    <title>Fatima Rental Property</title>
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
            <div class="logo">
                <!-- <img src="./assets/logo.png" alt="">-->
            </div>
            <div class="address">
                <i class="fas fa-map-marker-alt"></i>
                <span>321-North Escario Street Kamputhaw Cebu City</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="fas fa-phone"></i>
                <span>417-1686 / (0918) 948 1290</span>
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

    <section class="home" id="home">
        <div class="container">
            <h1>Fatima Rental Property</h1>
        </div>
    </section>

    <section class="about" id="about">
        <div class="container">
            <div class="heading">
                <h5></h5>
                <h2>In honor of Our Lady of Fatima
                </h2>
            </div>

            <div class="content flex  top">
                <div class="left">
                    <center>
                        <h3>A Home Where You Can Find Peace
                    </center>
                    </h3>&nbsp; &nbsp;
                    <h2></h2>
                    <p style="color:black;" align="justify" align="left"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; One of the
                        current problems that Cebu City faces is the interruption of water supply from time to
                        time.
                        In Barangay Kamputhaw, MCWD turns on the water supply between 12 am to 5 am daily
                        but sometimes for less time than that. This rotating water interruption
                        is surely a discomfort due to the fact that people will have to wake up around midnight
                        just to store water for the day. Aside from that, the World Health Organization (WHO) declared
                        that water shortages will
                        affect the spread of pathogenic organisms, such as bacteria and viruses, because the lack of
                        water will limit handwashing and compromise
                        the cleaning and sanitation of homes and health care facilities.</p>
                    <p style="color:black;" align="justify">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; That is why Fatima Rental
                        has installed a water tank below the ground to alleviate
                        this problem. To add, Fatima Rental has also passed the Water
                        Quality and Mineral Content Tests to ensure that boarders can have access to clean water 24/7.
                </div>
                <div class="right">
                    <img align="middle" src="./assets/h6.jpg" alt="" style="width: 60%;">
                </div>
            </div>
        </div>
    </section>

    <section class="room wrapper2 top" id="room">
        <div class="container">
            <div class="heading">
                <h5>OUR INTEGRITY</h5>
                <h2>Practical & Budget-Friendly</h2>
            </div>
            <div class="content flex mtop">
                <div class="left grid2">
                    <div class="box">
                        <i class="fas fa-tshirt"></i>

                        <h3>Laundry Service</h3>
                    </div>
                    <div class="box">
                        <i class="fas fa-coins"></i>
                        <h3>Economical Rooms</h3>
                    </div>
                    <div class="box">
                        <i class="fab fa-resolving"></i>

                        <h3>24/7 Reservations </h3>
                    </div>
                    <div class="box">
                        <i class="fal fa-wifi"></i>

                        <h3>High-speed Wi-Fi</h3>
                    </div>
                    <div class="box">
                        <i class="fas fa-key"></i>
                        <h3>Safe & Secure</h3>
                    </div>
                    <div class="box">
                        <i class="fas fa-snowflake"></i>
                        <h3>Aircon Ready</h3>
                    </div>
                    <div class="box">
                        <i class="fas fa-water"></i>
                        <h3>24/7 Water Tank</h3>
                    </div>
                    <div class="box">
                        <i class="fas fa-trash"></i>
                        <h3>Garbage Collection</h3>
                    </div>
                    <div class="box">
                        <i class="fas fa-wrench"></i>
                        <h3>Repair Services</h3>
                    </div>
                    <div class="box">
                        <i class="fas fa-store"></i>
                        <h3>Mini Mart</h3>
                    </div>
                </div>
                <div class="right">
                    <img src="./assets/h3.JPG" alt="">
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
                <?php foreach($rooms as $room): 
                $sql = "SELECT COUNT(*) AS co FROM tenants WHERE room_id = ". $room['id'] ." AND `status` = 0 AND account_status = 0";  
                $occupants = $conn->query($sql)->fetch_assoc()['co'];
                $full = intval($occupants) === intval($room['capacity']);
                ?>
                <div class="box flex">
                    <div class="left">
                        <img src="./../uploads/<?= $room['images'][0]['image_pathname'] ?>" alt="">
                    </div>
                    <div class="right">
                        <h4><?= $room['room_name'] ?></h4>
                        <div class="rate flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p><?= $room['description'] ?></p>
                        <h5>₱<?= $room['price'] ?>.00</h5>
                        <?php
                        if (!isset($_SESSION['user'])) {
                        ?>
                        <button class="flex1" style="cursor: pointer;" <?=$full ? "disabled='disabled'" : ""?>
                            onclick="window.location.href = './rent.php'">
                            <span><?=$full ? "Room is full" : "Reserve now"?></span>
                            <?= !$full ? '<a href="./rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>' : "" ?>
                        </button>
                        <?php } ?>
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

                    <p> This picture was shot at the 3rd floor in Fatima Rental Boarding House.
                        The surrounding area is spacious with sofas and furniture around.
                        The room has a double deck and has its own comfort room. What makes this
                        room unique is the scenery of the main busy road in Escario Street and the view of Goldberry
                        Hotel. </p>
                    <p>Aside from that, there is a balcony that is above the chapel where lodgers can participate
                        may it be in ceremonies or announcements especially every October which
                        is the Feast of Our Lady of Fatima
                        where the district Sitio Fatima is named.</p>
                    <?php if (!isset($_SESSION['user'])) { ?>
                    <button class="flex1" style="cursor: pointer;" onclick="window.location.href = './rent.php'">
                        <span>Reserve now</span>
                        <a href="./rent.php" style="color: white;"> <i class="fas fa-arrow-circle-right"></i></a>
                    </button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>


    <footer id="contact">
        <div class="container top">
            <div class="content grid  top">
                <div class="box">
                </div>
                <div class="box">
                    <h2>Services</h2>
                    <ul>
                        <li><i class="fas fa-angle-double-right"></i>Garbage Collection</li>
                        <li><i class="fas fa-angle-double-right"></i>Laundry</li>
                        <li><i class="fas fa-angle-double-right"></i>Wi-Fi</li>
                        <li><i class="fas fa-angle-double-right"></i>24/7 Water Tank</li>
                        <li><i class="fas fa-angle-double-right"></i>Repair and Maintenance</li>
                        <li><i class="fas fa-angle-double-right"></i>Mini-Mart</li>
                    </ul>
                </div>
                <div class="box">
                    <h2>Contact Us:</h2>
                    <div class="icon flex">
                        <div class="i">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="text">
                            <h3>Address</h3>
                            <p>321-North Escario Street Kamputhaw Cebu City</p>
                        </div>
                    </div>
                    <div class="icon flex">
                        <div class="i">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="text">
                            <h3>Phone</h3>
                            <p>417-1686 / (0918) 948 1290</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>
    </footer>

    <?php include './templates/scripts.php' ?>
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
</body>


</html>