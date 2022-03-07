<!doctype html>
<html lang="en">

<?php include './../templates/topnav.php'?>


<body>




  <div class="wrapper">


    <div class="body-overlay"></div>


    <?php include './../templates/nav.php' ?>




    <!-- Page Content  -->
    <div id="content">

      <div class="top-navbar">
        <nav class="navbar navbar-expand-lg">
          <div class="container-fluid">

            <button type="button" id="sidebarCollapse" class="d-xl-block d-lg-block d-md-mone d-none">
              <span class="material-icons">arrow_back_ios</span>
            </button>

            <a class="navbar-brand" href="#"> Account Settings </a>

            <button class="d-inline-block d-lg-none ml-auto more-button" type="button" data-toggle="collapse"
              data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
              aria-label="Toggle navigation">
              <span class="material-icons">more_vert</span>
            </button>

            <div class="collapse navbar-collapse d-lg-block d-xl-block d-sm-none d-md-none d-none"
              id="navbarSupportedContent">
              <ul class="nav navbar-nav ml-auto">
                <li class="dropdown nav-item active">
                  <a href="#" class="nav-link" data-toggle="dropdown">
                    <span class="material-icons">notifications</span>
                    <span class="notification">1</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li>
                      <a href="#">You have 1 new messages</a>
                    </li>


                  </ul>
                </li>

                <li class="nav-item">
                <li class="dropdown nav-item ">
                  <a href="#" class="nav-link" data-toggle="dropdown">
                    <span class="material-icons">person</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li>
                      <a href="#">Account Settings</a>
                    </li>
                    <li>
                      <a href="#">Change Password</a>
                    </li>
                    <li>
                      <a href="login.html">Logout</a>
                    </li>



                  </ul>
                </li>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>


      <form class="modal-content animate" action="/action_page.php" method="post">
        <div class="imgcontainer">

          <img src="img_avatar2.png" alt="Avatar" class="avatar">
        </div>

        <div class="container">
          <label for="uname"><b>Name</b></label>
          <input type="text" placeholder="Enter Username" name="uname" required>
          <label for="uname"><b>Username</b></label>
          <input type="text" placeholder="Enter Username" name="uname" required>
          <label for="psw"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="psw" required>
          <label for="psw"><b>Confirm Password</b></label>
          <input type="password" placeholder="Enter Password" name="psw" required>
          <button type="submit">Update</button>

        </div>


      </form>
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