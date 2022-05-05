<?php
include_once './redirect.php';
include './../../services/connect.php';

$url = $_SERVER['REQUEST_URI'];
$room_id = explode("=", parse_url($url, PHP_URL_QUERY))[1];
$sql = "SELECT * FROM room_images WHERE room_id = " . intval($room_id);
$res = $conn->query($sql);
// $images = [];
if ($res) {
    $images = $res->fetch_all(MYSQLI_ASSOC);
}
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
            <div class="container my-4">
              <ul>
                <?php foreach ($images as $image) {?>
                <li>
                  <div class="d-flex align-items-center" style="width: 250px">
                    <p style="flex-grow: 1; margin: 0"><?=$image['image_pathname']?></p>
                    <button class="btn btn-link" onclick="viewImage('<?= $image['image_pathname'] ?>')">View</button>
                    <button class="btn btn-link text-danger" onclick="deleteImage(<?= $image['id'] ?>)">Delete</button>
                  </div>
                </li>
                <?php }?>
              </ul>
            </div>
            <form class="dropzone" id="my-great-dropzone"></form>

            <button type="submit" form="my-great-dropzone" class="btn btn-success mb-3"
              style="margin-top: 15px">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include './../templates/scripts.php'?>

  <script type="text/javascript">
  Dropzone.autoDiscover = false;

  const room_id = new URLSearchParams(window.location.search).get('id');

  const dropzone = new Dropzone('#my-great-dropzone', {
    url: "./../functions/upload_room_image.php?id=" + room_id,
    paramName: "file",
    maxFilesize: 10,
    uploadMultiple: true,
    acceptedFiles: 'image/*',
    autoProcessQueue: false,
    addRemoveLinks: true,
  });

  dropzone.on("complete", function(e) {
    $.alert({
      title: 'Success',
      content: 'File/s successfully uploaded.',
      buttons: {
        ok: function() {
          window.location.reload();
        }
      }
    });
  });

  function viewImage(pathname) {
    $.dialog({
      title: pathname,
      content: `<img src="./../../uploads/${pathname}"} />`,
      backgroundDismiss: true,
      closeIcon: true,
    });
  }

  function deleteImage(id) {
    const resp = confirm("Do you want to delete this image?");
    if (resp) {
      $.post("./../functions/delete_room_image.php", {
        id
      }, function(res) {
        const data = JSON.parse(res);
        if (data.status === 200) {
          $.alert({
            title: 'Success',
            content: 'Image successfully deleted.',
            buttons: {
              ok: function() {
                window.location.reload();
              }
            }
          });
        }
      })
    }
  }

  $(document).ready(function() {
    $.get('./../functions/room_details.php?id=' + room_id, function(
      res) {
      const data = JSON.parse(res);
      $('#title').text(`Room ${data.room_number} Images`)
    });

    $('#my-great-dropzone').submit(function(e) {
      e.preventDefault();
      dropzone.processQueue();
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