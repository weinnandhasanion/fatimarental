<?php 
include "./../../services/connect.php";
session_start();

$id = $_SESSION['user']['id'];
$current = $_POST['current'];
$new_pass = $_POST['new_pass'];
$confirm_pass = $_POST['confirm_pass'];

$errors = [];

if (empty($current)) {
  $errors['current'] = 'Please enter your current password.';
} else {
  $sql = "SELECT password FROM tenants WHERE id = $id";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  if (!password_verify($current, $row['password'])) {
    $errors['current'] = 'Your current password is incorrect.';
  }
}

if (empty($new_pass)) {
  $errors['new_pass'] = 'Please enter a password.';
}

if (empty($confirm_pass)) {
  $errors['confirm_pass'] = 'Please enter a password.';
} else if ($confirm_pass !== $new_pass && !empty($new_pass)) {
  $errors['confirm_pass'] = 'Passwords must match.';
}

if (count($errors) < 1) {
  $pass = password_hash($new_pass, PASSWORD_DEFAULT);
  $sql = "UPDATE tenants SET `password` = '$pass' WHERE id = $id";
  $res = $conn->query($sql);
  if (!$res) {
    $error['server'] = 'There is an error in changing your password. Please try again.';
  }
}

echo json_encode(['errors' => $errors]);
?>