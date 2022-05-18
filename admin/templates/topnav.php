<?php include_once './../constants/titles.php' ?>

<div class="top-navbar">
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <!-- <button type="button" id="sidebarCollapse" class="d-xl-block d-lg-block d-md-mone d-none">
        <span class="material-icons">arrow_back_ios</span>
      </button> -->
      <a class="navbar-brand" href="#"><?= $titles[$path] ?></a>
      <button class="d-inline-block d-lg-none ml-auto more-button" type="button" data-toggle="collapse"
        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="material-icons">more_vert</span>
      </button>
      <div class="collapse navbar-collapse d-lg-block d-xl-block d-sm-none d-md-none d-none"
        id="navbarSupportedContent">
        <ul class="nav navbar-nav ml-auto">
          <!-- <li class="dropdown nav-item active">
            <a href="#" class="nav-link" data-toggle="dropdown">
              <span class="material-icons">notifications</span>
              <span class="notification">1</span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <a href="#">You have 1 new messages</a>
              </li>

            </ul>
          </li> -->
          <li class="nav-item">
          <li class="dropdown nav-item ">
            <a href="#" class="nav-link" data-toggle="dropdown">
              <span class="material-icons">person</span>
            </a>
            <ul class="dropdown-menu" style="left: unset">
              <li>
                <a href="edituser.html">Account Settings</a>
              </li>
              <li>
                <a href="#">Change Password</a>
              </li>
              <li>
                <a href="#" id="logout-link">Logout</a>
              </li>
            </ul>
          </li>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
