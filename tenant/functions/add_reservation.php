<?php 
include './../../services/connect.php';
include_once './../../admin/functions/uuid.php';

$status = 422;
$error = null;
$now = strtotime(date("Y-m-d"));

if (!isset($_POST['room_id'])) {
  $error = "Please choose a room!";
} else if (!isset($_POST['first_name'])) {
  $error = "Please enter your first name.";
} else if (!isset($_POST['last_name'])) {
  $error = "Please enter your last name.";
} else if (!isset($_POST['gender'])) {
  $error = "Please enter your gender.";
} else if (!isset($_POST['email_address'])) {
  $error = "Please enter a valid email address.";
} else if (!isset($_POST['contact_number']) && !preg_match("/^(09|\+639)\d{9}$/", $_POST['contact_number'])) {
  $error = "Please enter a valid phone number.";
} else if (isset($_POST['move_date']) && $now >= strtotime(date($_POST['move_date']))) {
  $error = "Please choose a move date greater than the current date.";
}

if ($error === null) {
  $room_id = intval($_POST['room_id']);
  $first_name = $_POST['first_name'];
  $middle_initial = $_POST['middle_initial'] ?? "";
  $last_name = $_POST['last_name'];
  $gender = $_POST['gender'];
  $email_address = $_POST['email_address'];
  $contact_number = $_POST['contact_number'];
  $move_date = $_POST['move_date'];
  $message = $_POST['message'];
  
  if (empty($message)) $message = null;
  
  $id = guidv4();
  $status = 1;
  $sql = "INSERT INTO tenants (id, room_id, first_name, last_name, middle_initial, gender, `status`, email_address, contact_number, reservation_account_expiry_date)
    VALUES ('$id', $room_id, '$first_name', '$last_name', '$middle_initial', ".intval($gender).", $status, '$email_address', '$contact_number', DATE_ADD(NOW(), INTERVAL 3 DAY))";
  $res = $conn->query($sql);
  
  if ($res) {
    $sql = "INSERT INTO reservations (room_id, tenant_id, move_date, `message`)  
    VALUES ($room_id, '$id', '$move_date', '$message')";
  
    $res = $conn->query($sql);
    if ($res) $status = 200;
  }
  
}

echo json_encode(['status' => $status, 'error' => $error]);
?>