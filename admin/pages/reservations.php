<?php
include_once './redirect.php';
?>

<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>


<body>




  <div class="wrapper">


    <div class="body-overlay"></div>

    <?php include './../templates/nav.php' ?>




    <!-- Page Content  -->
    <div id="content">

    <?php include './../templates/topnav.php'?>



      <div class="main-content">
        <div class="row ">
          <div class="col-lg-7 col-md-12">
            <div class="card" style="min-height: 485px">
              <div class="card-header card-header-text">
                <h4 class="card-title">Tenant Status</h4>
                <p class="category">New Tentat Rent on 21th December, 2020</p>
              </div>
              <div class="card-content table-responsive">
                <table class="table table-hover">
                  <thead class="text-primary">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Payment Status</th>
                      <th>Room</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>Michael James Antiporta</td>
                      <td>UnPaid</td>
                      <td>Room 1</td>
                      <td style="cursor: pointer;"><i class="material-icons">edit</i>
                        <i class="material-icons">delete</i>
                      </td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Michael James Antiporta</td>
                      <td>UnPaid</td>
                      <td>Room 2</td>
                      <td style="cursor: pointer;"><i class="material-icons">edit</i>
                        <i class="material-icons">delete</i>
                      </td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Michael James Antiporta</td>
                      <td>UnPaid</td>
                      <td>Room 3</td>
                      <td style="cursor: pointer;"><i class="material-icons">edit</i>
                        <i class="material-icons">delete</i>
                      </td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Michael James Antiporta</td>
                      <td>UnPaid</td>
                      <td>Room 4</td>
                      <td style="cursor: pointer;"><i class="material-icons">edit</i>
                        <i class="material-icons">delete</i>
                      </td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Michael James Antiporta</td>
                      <td>UnPaid</td>
                      <td>Room 5</td>
                      <td style="cursor: pointer;"><i class="material-icons">edit</i>
                        <i class="material-icons">delete</i>
                      </td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Michael James Antiporta</td>
                      <td>UnPaid</td>
                      <td>Room 6</td>
                      <td style="cursor: pointer;"><i class="material-icons">edit</i>
                        <i class="material-icons">delete</i>
                      </td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Michael James Antiporta</td>
                      <td>UnPaid</td>
                      <td>Room 7</td>
                      <td style="cursor: pointer;"><i class="material-icons">edit</i>
                        <i class="material-icons">delete</i>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>



        </div>



      </div>
    </div>






    <?php include './../templates/scripts.php' ?>

    <script type="text/javascript">
    $(document).ready(function() {
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