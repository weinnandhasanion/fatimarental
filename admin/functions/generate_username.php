<?php
include './../../services/connect.php';
include './send_message.php';

$id = $_GET['id'];

$sql = "SELECT * FROM tenants WHERE id = '$id'";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

$date_suffix = date('WyG', strtotime(date(DATE_ISO8601)));
$username = strtolower($row['first_name'][0] . '-' . $row['last_name'] . $date_suffix);

$sql = "UPDATE tenants SET username = '$username' WHERE id = '$id'";
$res = $conn->query($sql);

if ($res) {
  $message = "You can now login to your Fatima Rental Online account! Your username is $username and your default password is fatima123.
It is recommended that you change your password to avoid unwantend access to your account. 
Access your account in this link: https://www.fatimarental.online/tenant/login.php. Thank you!";
  sendMessage($row['contact_number'], $message);

  echo json_encode($username);
} else {
  echo json_encode(null);
}