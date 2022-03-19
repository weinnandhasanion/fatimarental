<?php 
session_start();

if (!isset($_SESSION['user'])) {
  header("Location: ./../index.php");
}

if (isset($_SESSION['user']) && !array_key_exists('user_type', $_SESSION['user'])) {
  header("Location: ./../../index.php");
}
?>