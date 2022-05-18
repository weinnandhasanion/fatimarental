<?php 
include './../services/connect.php';

$rooms = [];
$rooms = $conn->query("SELECT * FROM rooms")?->fetch_all(MYSQLI_ASSOC);


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
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
          magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id
          est laborum.
        </p>
        <div class="quote">
          <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam.
          </p>
        </div>
      </div>
      <div class="form">
        <form>
          <div class="inpbox full">
            <span class="flaticon-houses"></span>
            <select id="room_id" name="room_id">
              <option value="">Select Room Number</option>
              <?php foreach($rooms as $room): ?>
              <option value="<?=$room['id']?>"><?=$room['room_number']?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="inpbox">
            <span class="flaticon-user"></span>
            <input type="text" id="fname" placeholder="First Name">
          </div>
          <div class="inpbox">
            <span class="flaticon-user"></span>
            <input type="text" id="lname" placeholder="Last Name">
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
        name: `${$('#fname').val()} ${$('#lname').val()}`,
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