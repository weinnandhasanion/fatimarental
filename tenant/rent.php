<?php 
include './../services/connect.php';

$rooms = [];
$sql = "SELECT * FROM rooms WHERE capacity > (SELECT COUNT(*) FROM tenants WHERE room_id = rooms.id AND `status` != 2)
        AND `status` != 2";
$rooms = $conn->query($sql)?->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>Rental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

  <link rel="stylesheet" href="css/form.css">
  <link rel="stylesheet" type="text/css" href="font/flaticon.css">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"
    integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

  <style>
  .inpbox.full textarea {
    border: 0;
    color: var(--font-light);
    padding: 0 4%;
    font-family: inherit;
    width: 100%;
    resize: none
  }
  .inpbox.full textarea:focus {
    outline: none;
  }
  </style>

</head>

<body>

  <div class="container">
    <div class="book">
      <div class="description">
        <h1><strong>Reserve</strong> your room</h1>
        <p>
        Please provide your personal information and the select a room you want for your reservation, Thank you
        </p>
        <div class="quote">
          <p>
          We are happy to accommodate you. :)
          </p>
        </div>
      </div>
      <div class="form">
        <form>
          <div class="inpbox full">
            <span class="flaticon-houses"></span>
            <select id="room_id" name="room_id">
              <option value="">Select Room</option>
              <?php foreach($rooms as $room): ?>
              <option value="<?=$room['id']?>"><?=$room['room_name']?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="inpbox">
            <span class="flaticon-user"></span>
            <input type="text" id="fname" placeholder="First Name">
          </div>
          <div class="inpbox">
            <span class="flaticon-user"></span>
            <input type="text" id="mi" placeholder="Middle Initial">
          </div>
          <div class="inpbox">
            <span class="flaticon-user"></span>
            <input type="text" id="lname" placeholder="Last Name">
          </div>
          <div class="inpbox" style="display: flex">
            <span class="flaticon-user"></span>
            <select name="" id="gender" placeholder="Gender">
              <option value="0">Male</option>
              <option value="1">Female</option>
            </select>
          </div>
          <div class="inpbox">
            <span class="flaticon-email"></span>
            <input type="email" id="email" placeholder="Email">
          </div>
          <div class="inpbox">
            <span class="flaticon-email"></span>
            <input type="text" id="number" placeholder="Contact #">
          </div>
          <div>
            <p style="color: white; padding-right: 5px;">Move Date:</p>
          </div>
          <div class="inpbox">
            <span class="flaticon-calendar"></span>
            <input type="date" id="move_date" placeholder="From Date">
          </div>
          <div class="inpbox full">
            <textarea placeholder="Message (Optional)" id="message" rows="3"></textarea>
          </div>
          <button type="submit" class="subt">Submit</button>
          <button type="button" class="rst"> <a href="index.php" style="color: white; text-decoration: none;">
              Back</a></button>
        </form>
      </div>
    </div>
  </div>


  <script>
  $(document).ready(function() {
    $('form').submit(function(e) {
      e.preventDefault()

      const data = {
        room_id: $('#room_id').val(),
        first_name: $('#fname').val(),
        middle_initial: $('#mi').val(),
        last_name: $('#lname').val(),
        gender: $('#gender').val(),
        email_address: $('#email').val(),
        contact_number: $('#number').val(),
        move_date: $('#move_date').val(),
        message: $('#message').val()
      }

      $.post('./functions/add_reservation.php', data, function(resp) {
        console.log(resp);
        const res = JSON.parse(resp);
        if (res.status === 200) {
          alert('Successfully reserved room. Please check your email regarding reservation details.')
          window.location.href = 'index.php'
        }
      })
    })
  })
  </script>
</body>


</html>