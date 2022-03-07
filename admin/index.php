<?php
session_start();

if (!isset($_SESSION['user'])) {
  header("Location: ./login.php");
} else {
  switch ($_SESSION['user']['user_type']) {
    case 0:
      header("Location: ./pages/dashboard.php");
      break;
    case 1:
      // Enter redirection to manager dashboard here...
  }
}