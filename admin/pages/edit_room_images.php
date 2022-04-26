<?php
include_once './redirect.php';
?>
<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>

<body>
  <div class="wrapper">
    <div class="body-overlay"></div>

    <?php include './../templates/nav.php'?>

    <!-- Page Content  -->
    <div id="content">
      <?php include './../templates/topnav.php'?>
      <div class="main-content">
        <div class="row">
          <div class="col-sm-12">
            <h4 class="font-weight-normal" id="title"></h4>
            <form class="dropzone" id="my-great-dropzone"></form>

            <button type="submit" form="my-great-dropzone" class="btn btn-success" style="margin-top: 15px">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include './../templates/scripts.php'?>

  <script type="text/javascript">
  const dropzone = new Dropzone('#my-great-dropzone', {
    url: "./../functions/upload_room_image.php",
    paramName: "file",
    maxFilesize: 2,
    uploadMultiple: true,
    acceptedFiles: 'image/*',
    autoProcessQueue: false,
    addRemoveLinks: true,
    accept: function(file, done) {
      if (file.name == "justinbieber.jpg") {
        done("Naha, you don't.");
      } else {
        done();
      }
    }
  });

  $(document).ready(function() {
    $.get('./../functions/room_details.php?id=' + new URLSearchParams(window.location.search).get('id'), function(res) {
      const data = JSON.parse(res);
      $('#title').text(`Room ${data.room_number} Images`)
    });

    $('#my-great-dropzone').submit(function(e) {
      e.preventDefault();
      dropzone.processQueue();
      console.log(e);
    })

    $('#sidebarCollapse').on('click', function() {
      $('#sidebar').toggleClass('active');
      $('#content').toggleClass('active');
    });

    $('.more-button,.body-overlay').on('click', function() {
      $('#sidebar,.body-overlay').toggleClass('show-nav');
    });

    $('.sidebar-link').click(function(e) {
      if ($(this).hasClass('active')) {
        e.preventDefault();
        return;
      }
      $('.sidebar-link').removeClass('active');
      $(this).addClass('active');
    });
  });
  </script>
</body>

</html>