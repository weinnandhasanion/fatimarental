<?php 
include './../../services/connect.php';

$status = 422;
$room_id = intval($_POST['room_id']);
$name = $_POST['name'];
$email_address = $_POST['email_address'];
$contact_number = $_POST['contact_number'];
$move_date = $_POST['move_date'];
$message = $_POST['message'];

if (empty($message)) $message = null;

$sql = "INSERT INTO reservations (room_id, `name`, email_address, contact_number, move_date, `message`)  
  VALUES ($room_id, '$name', '$email_address', '$contact_number', '$move_date', '$message')";
$res = $conn->query($sql);
if ($res) $status = 200;

echo json_encode(['status' => $status]);
?>