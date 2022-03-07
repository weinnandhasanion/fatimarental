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
        <div class="w3-container">
          <h2>Search Rooms</h2>

        </div>
        <form class="example" action="action_page.php">
          <input type="text" placeholder="Room" name="search" style="width: 20%;">
          <button type="submit" style="background-color: rgb(119, 199, 0); color: white;"><i
              class="fa fa-search"></i></button>
        </form>
        <div class="row ">
          <div class="col-lg-7 col-md-12">
            <div class="card" style="min-height: 485px">
              <div class="card-header card-header-text">
                <h4 class="card-title">Room Availability</h4>
              </div>
              <div class="card-content table-responsive">
                <table class="table table-hover">
                  <thead class="text-primary">
                    <tr>
                      <th>Room Number</th>
                      <th>Tenant</th>
                      <th>Room Status</th>
                      <th>Occupacion</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>

                      <td>1</td>
                      <td>Michael James Antiporta</td>
                      <td>Not Available</td>
                      <td>Good for 2</td>


                    </tr>
                    <tr>

                      <td>2</td>
                      <td>Michael James Antiporta</td>
                      <td>Not Available</td>
                      <td>Good for 2</td>


                    </tr>
                    <tr>

                      <td>3</td>
                      <td>Michael James Antiporta</td>
                      <td>Not Available</td>
                      <td>Good for 2</td>


                    </tr>
                    <tr>

                      <td>4</td>
                      <td>Michael James Antiporta</td>
                      <td>Not Available</td>
                      <td>Good for 2</td>


                    </tr>
                    <tr>

                      <td>5</td>
                      <td>Michael James Antiporta</td>
                      <td>Not Available</td>
                      <td>Good for 2</td>


                    </tr>
                    <tr>

                      <td>6</td>
                      <td>Michael James Antiporta</td>
                      <td>Not Available</td>
                      <td>Good for 2</td>


                    </tr>
                    <tr>

                      <td>7</td>
                      <td>Michael James Antiporta</td>
                      <td>Not Available</td>
                      <td>Good for 2</td>


                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>


          <div class="col-lg-5 col-md-12">
            <div class="card" style="min-height: 485px">
              <div class="card-header card-header-text">
                <h4 class="card-title">Add Rooms</h4>
                <hr>
              </div>
              <div class="card-content">
                <div>
                  <form action="/action_page.php">
                    <label for="Room">Room Number</label>
                    <input type="text" id="room" name="Room" placeholder="Room">

                    <label for="Occupacion">Number Occupacion</label>
                    <input type="text" id="Occupacion" name="Number" placeholder="Number Occupacion">
                    <label for="Occupacion">Add Description</label><br>
                    <textarea type="text" name="" id="" style="width: 100%; height: 100px;"></textarea><br>
                    <label for="country">Upload Pictures</label><br>
                    <input type="file" id="myFile" name="filename">
                    <hr>
                    <input type="submit" value="Add &#43">
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>



      </div>
    </div>








    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="./../js/jquery-3.3.1.slim.min.js"></script>
    <script src="./../js/popper.min.js"></script>
    <script src="./../js/bootstrap.min.js"></script>
    <script src="./../js/jquery-3.3.1.min.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
      });

      $('.more-button,.body-overlay').on('click', function() {
        $('#sidebar,.body-overlay').toggleClass('show-nav');
      });

    });
    </script>





</body>

</html>