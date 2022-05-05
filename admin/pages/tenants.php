<?php
include './../../services/connect.php';
include_once './redirect.php';

// Get users
$users = [];
$usersQuery = "SELECT t.*, r.room_number FROM tenants AS t
  INNER JOIN rooms AS r
  ON t.room_id = r.id";
$usersRes = mysqli_query($conn, $usersQuery);
$users = mysqli_fetch_all($usersRes, MYSQLI_ASSOC);

// Get rooms
$rooms = [];
$roomsQuery = "SELECT * FROM rooms";
$roomsRes = mysqli_query($conn, $roomsQuery);
$rooms = mysqli_fetch_all($roomsRes, MYSQLI_ASSOC);
?>

<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>

<body>
  <div class="wrapper">
    <div class="body-overlay"></div>
    <!-- Include navigation bar -->
    <?php include './../templates/nav.php'?>

    <!-- Page Content  -->
    <div id="content">
      <?php include './../templates/topnav.php'?>
      <div class="main-content">
        <div class="row ">
          <div class="col-md-12">
            <div class="card" style="min-height: 485px">
              <div class="card-header card-header-text d-flex justify-content-start align-items-center">
                <h4 class="card-title flex-grow-1">Tenant Status</h4>
                <button class="btn btn-primary" data-toggle="modal" data-target="#add-tenant-modal">Add &#43;</button>
              </div>
              <div class="card-content table-responsive">
                <table class="table table-hover" id='tenants-table'>
                  <thead class="text-primary">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Room</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
foreach ($users as $user) {
    ?>
                    <tr>
                      <td class='align-middle'><?=$user['id']?></td>
                      <td class='align-middle'>
                        <?=$user['first_name'] . " " . $user['middle_initial'] . " " . $user['last_name']?></td>
                      <td class='align-middle'><?=$user['room_number']?></td>
                      <td class='align-middle'>
                        <button class="btn btn-small btn-link" onclick="viewMember($(this).attr('data-id'))"
                          data-id="<?=$user['id']?>">View</button>
                        <button class="btn btn-small btn-link" onclick="updateMember($(this).attr('data-id'))"
                          data-id="<?=$user['id']?>">Update</button>
                      </td>
                    </tr>
                    <?php
}
?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Tenant Modal -->
    <div id="add-tenant-modal" tabindex="-1" class="modal fade" role="dialog">
      <form id="add-tenant-form" enctype="multipart/form-data">
        <div class="modal-dialog" role='document'>
          <div class="modal-content" style="max-width:600px">
            <div class="modal-header">
              <h5 class="modal-title">Add Tenant</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row mb-2">
                <div class="col-sm-5">
                  <label>First Name</label>
                  <input class="form-control" type="text" name="first_name" required>
                  <small id='first_name-error' class='text-danger'></small>
                </div>
                <div class="col-sm-5">
                  <label>Last Name</label>
                  <input class="form-control" type="text" name="last_name" required>
                  <small id='last_name-error' class='text-danger'></small>
                </div>
                <div class="col-sm-2">
                  <label>M.I.</label>
                  <input class="form-control" type="text" name="middle_initial" required>
                  <small id='middle_initial-error' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12">
                  <label>Room</label>
                  <select class="form-control" placeholder="Enter Room" name="room_number" required>
                    <?php
foreach ($rooms as $room) {
    ?>
                    <option value="<?=$room['id']?>"><?=$room['room_number']?></option>
                    <?php
}
?>
                  </select>
                  <small id='room_number-error' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label>Birthdate</label>
                  <input class="form-control" type="date" name="birthdate" required>
                  <small id='birthdate-error' class='text-danger'></small>
                </div>
                <div class="col-sm-6">
                  <label>Gender</label>
                  <select class="form-control" name="gender" required>
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                    <option value="2">Unspecified</option>
                  </select>
                  <small id='gender-error' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label>Email Address</label>
                  <input class="form-control" type="email" name="email_address" required>
                  <small id='email_address-error' class='text-danger'></small>
                </div>
                <div class="col-sm-6">
                  <label>Contact Number</label>
                  <input class="form-control" type="text" name="contact_number" />
                  <small id='contact_number-error' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12">
                  <label>Address</label>
                  <textarea rows="2" class="form-control" type="text" name="address" required></textarea>
                  <small id='address-error' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12 d-flex flex-column">
                  Valid ID
                  <input type="file" hidden="true" id="file-input" name="valid_id">
                  <small id="valid_id-error" class="text-danger"></small>
                  <small id="pathname-cont"></small>
                  <button type="button" class="btn btn-secondary btn-sm" style="width: 100px"
                    onclick="document.querySelector('#file-input').click()">Upload File</button>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- View Tenant Modal -->
    <div id="view-tenant-modal" tabindex="-1" class="modal fade" role="dialog">
      <div class="modal-dialog" role='document'>
        <div class="modal-content" style="max-width:600px">
          <div class="modal-header">
            <h5 class="modal-title">Tenant Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row mb-2">
              <div class="col-sm-5">
                <label>First Name</label>
                <input class="form-control" type="text" name="first_name-view" readonly>
                <small id='first_name-error' class='text-danger'></small>
              </div>
              <div class="col-sm-5">
                <label>Last Name</label>
                <input class="form-control" type="text" name="last_name-view" readonly>
                <small id='last_name-error' class='text-danger'></small>
              </div>
              <div class="col-sm-2">
                <label>M.I.</label>
                <input class="form-control" type="text" name="middle_initial-view" readonly>
                <small id='middle_initial-error' class='text-danger'></small>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-6">
                <label for="">Username</label>
                <input type="text" class="form-control" name='username-view' readonly />
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-12">
                <label>Room</label>
                <select class="form-control" placeholder="Enter Room" name="room_number-view" readonly>
                  <?php
foreach ($rooms as $room) {
    ?>
                  <option value="<?=$room['id']?>"><?=$room['room_number']?></option>
                  <?php
}
?>
                </select>
                <small id='room_number-error' class='text-danger'></small>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-6">
                <label>Birthdate</label>
                <input class="form-control" type="date" name="birthdate-view" readonly>
                <small id='birthdate-error' class='text-danger'></small>
              </div>
              <div class="col-sm-6">
                <label>Gender</label>
                <select class="form-control" name="gender-view" readonly>
                  <option value="0">Male</option>
                  <option value="1">Female</option>
                  <option value="2">Unspecified</option>
                </select>
                <small id='gender-error' class='text-danger'></small>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-6">
                <label>Email Address</label>
                <input class="form-control" type="email" name="email_address-view" readonly>
                <small id='email_address-error' class='text-danger'></small>
              </div>
              <div class="col-sm-6">
                <label>Contact Number</label>
                <input class="form-control" type="text" name="contact_number-view" readonly />
                <small id='contact_number-error' class='text-danger'></small>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-12">
                <label>Address</label>
                <textarea rows="2" class="form-control" type="text" name="address-view" readonly></textarea>
                <small id='address-error' class='text-danger'></small>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-12 d-flex flex-column">
                Valid ID
                <input type="file" hidden="true" id="file-input" name="valid_id">
                <small id="valid_id-error" class="text-danger"></small>
                <small id="pathname-cont-view"></small>
                <button type="button" class="btn btn-primary btn-sm" style="width: 100px"
                  id="view-valid-id">View</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Update Tenant Modal -->
    <div id="update-tenant-modal" tabindex="-1" class="modal fade" role="dialog">
      <form id="update-tenant-form" method="POST">
        <div class="modal-dialog" role='document'>
          <div class="modal-content" style="max-width:600px">
            <div class="modal-header">
              <h5 class="modal-title">Update Tenant</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="id-update" />
              <div class="row mb-2">
                <div class="col-sm-5">
                  <label>First Name</label>
                  <input class="form-control" type="text" name="first_name" id="first_name-update" required>
                  <small id='first_name-error-update' class='text-danger'></small>
                </div>
                <div class="col-sm-5">
                  <label>Last Name</label>
                  <input class="form-control" type="text" name="last_name" id="last_name-update" required>
                  <small id='last_name-error-update' class='text-danger'></small>
                </div>
                <div class="col-sm-2">
                  <label>M.I.</label>
                  <input class="form-control" type="text" name="middle_initial" id="middle_initial-update" required>
                  <small id='middle_initial-error-update' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12">
                  <label for="">Username</label>
                  <div class="d-flex" style='gap: 20px'>
                    <input type="text" class="form-control" name='username' id='username-update' readonly />
                    <button type='button' class="btn btn-sm btn-primary" id='generate-username-btn'>Generate
                      Username</button>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12">
                  <label>Room</label>
                  <select class="form-control" placeholder="Enter Room" name="room_number" id="room_id-update" required>
                    <?php
foreach ($rooms as $room) {
    ?>
                    <option value="<?=$room['id']?>"><?=$room['room_number']?></option>
                    <?php
}
?>
                  </select>
                  <small id='room_number-error-update' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label>Birthdate</label>
                  <input class="form-control" type="date" name="birthdate" id="birthdate-update" required>
                  <small id='birthdate-error-update' class='text-danger'></small>
                </div>
                <div class="col-sm-6">
                  <label>Gender</label>
                  <select class="form-control" name="gender" id="gender-update" required>
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                    <option value="2">Unspecified</option>
                  </select>
                  <small id='gender-error-update' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label>Email Address</label>
                  <input class="form-control" type="email" name="email_address" id="email_address-update" required>
                  <small id='email_address-error-update' class='text-danger'></small>
                </div>
                <div class="col-sm-6">
                  <label>Contact Number</label>
                  <input class="form-control" type="text" name="contact_number" id="contact_number-update" required />
                  <small id='contact_number-error-update' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12">
                  <label>Address</label>
                  <textarea rows="2" class="form-control" type="text" name="address" id="address-update" required></textarea>
                  <small id='address-error-update' class='text-danger'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12 d-flex flex-column">
                  Valid ID
                  <input type="file" hidden="true" id="file-input-update" name="valid_id-new">
                  <small id="valid_id-error-update" class="text-danger"></small>
                  <small id="pathname-cont-update"></small>
                  <div class="d-flex">
                    <button type="button" class="btn btn-secondary btn-sm mr-1" style="width: 70px"
                      onclick="document.querySelector('#file-input-update').click()">Upload</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Update</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <?php include './../templates/scripts.php' ?>

    <script type="text/javascript">
    // Function to populate view tenant modal
    function viewMember(id) {
      $.get('./../functions/user_details.php?id=' + id, function(res) {
        const user = JSON.parse(res);
        for (key in user) {
          $(`input[name=${key}-view]`).val(user[key]);
        }
        $('textarea[name=address-view]').val(user.address);
        $('#pathname-cont-view').text(user.valid_id);
        $('#view-tenant-modal').modal('show');
      });
    };

    // Function to populate update tenant modal
    function updateMember(id) {
      $.get('./../functions/user_details.php?id=' + id, function(res) {
        const user = JSON.parse(res);

        // Put user ID in form data-id attribute
        $('#update-tenant-form').attr('data-id', id);

        for (key in user) {
          $(`#${key}-update`).val(user[key]);
        }

        $('#address-update').val(user.address);

        if ($('#username-update').val() !== '') {
          $('#generate-username-btn').attr('disabled', 'disabled');
        } else {
          $('#generate-username-btn').removeAttr('disabled');
        }

        if ($('#valid_id-update').val() !== '') {
          $('#pathname-cont-update').text(user.valid_id);
          $('#valid_id-delete').removeAttr('disabled');
        } else {
          $('#valid_id-delete').attr('disabled', 'disabled');
        }
        $('#update-tenant-modal').modal('show');
      })
    }

    $(document).ready(function() {
      $('#tenants-table').DataTable();

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

      // Add image in add tenant modal
      $("#file-input").change(function(e) {
        let {
          files
        } = e.target;

        if (files.length > 0) {
          $('#pathname-cont').text(files[0].name);
        }

        $('#valid_id-error').text('');
      });

      // Add tenant
      $("#add-tenant-form").submit(function(e) {
        e.preventDefault();
        let data = $(this).serializeArray();
        let formData = new FormData();
        let file = document.querySelector('#file-input');

        if (file.files.length < 1) {
          $("#valid_id-error").text('Please upload a valid ID.');
          return;
        }

        data.forEach((d) => formData.append(d.name, d.value));
        formData.append('file', file.files[0]);

        $.ajax({
          type: "POST",
          enctype: 'multipart/form-data',
          url: "./../functions/add_tenant.php",
          data: formData,
          processData: false,
          contentType: false,
          cache: false,
          timeout: 800000,
          success: function(data) {
            let res = JSON.parse(data);
            if (res.status === 422) {
              for (error in res.errors) {
                $(`#${error}-error`).text(res.errors[error]);
              }
            } else {
              alert('Tenant successfully added.');
              window.location.reload();
            }
          },
        });
      });

      // Update tenant
      $("#update-tenant-form").submit(function(e) {
        e.preventDefault();
        let data = $(this).serializeArray();
        let formData = new FormData();
        let file = document.querySelector('#file-input-update');

        if ($('#pathname-cont-update').text() === '' && file.files.length < 1) {
          $("#valid_id-error-update").text('Please upload a valid ID.');
          return;
        }

        data.forEach((d) => formData.append(d.name, d.value));
        formData.append('file', file.files[0]);

        $.ajax({
          type: "POST",
          enctype: 'multipart/form-data',
          url: "./../functions/update_tenant.php?id=" + $(this).attr('data-id'),
          data: formData,
          processData: false,
          contentType: false,
          cache: false,
          timeout: 800000,
          success: function(data) {
            console.log(data);
            let res = JSON.parse(data);
            if (res.status === 422) {
              for (error in res.errors) {
                $(`#${error}-error-update`).text(res.errors[error]);
              }
            } else {
              alert('Tenant successfully updated.');
              window.location.reload();
            }
          },
        });
      });

      // Generate username for user
      $('#generate-username-btn').click(function() {
        let id = $('#update-tenant-form').attr('data-id');
        $.get('./../functions/generate_username.php?id=' + id, function(res) {
          let data = JSON.parse(res);
          if (data !== null) {
            $('#username-update').val(data);
            $('#generate-username-btn').attr('disabled', 'disabled');
          } else {
            alert("Error in generating username. Please try again.");
          }
        });
      });

      // View valid id
      $('#view-valid-id').click(function() {
        let filename = $('#pathname-cont-view').text();
        let path = '../../uploads/' + filename;
        $.dialog({
          backgroundDismiss: true,
          title: 'Valid ID',
          content: `<div style='display: flex; justify-content: center; align-items: center;'>
              <img src='${path}' alt='valid-id' />
            </div>`
        });
      });

      // Insert new photo to file input when updating tenant
      $('#file-input-update').change(function(e) {
        let {
          files
        } = e.target;

        if (files.length > 0) {
          $('#pathname-cont-update').text(files[0].name);
        }

        $('#valid_id-error-update').text('');
      });
    });
    </script>
</body>

</html>