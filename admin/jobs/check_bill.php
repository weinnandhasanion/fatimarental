<?php 
include "./../../services/connect.php";
date_default_timezone_set('Asia/Manila');
include './../functions/send_message.php';

/* Uncomment if to be used */

const MY_DATE_FORMAT = "d-m-y h:i:s";
$now = strtotime(date(MY_DATE_FORMAT));

$sql = "SELECT r.*, b.end_period FROM rooms AS r
  INNER JOIN bills AS b
  ON b.room_id = r.id";
$rooms = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

foreach ($rooms as $room) {
  $deadline = formatDate($room['end_period']);
  if ($deadline > $now) {
    $days = abs($now - $deadline)/60/60/24;
    $numbers = getTenantContactNumbers($room['id'], $conn);
    $lastDeadline = date("F d, Y", strtotime($room['end_period'], " + 30 days"));
    $message = "Hi! You are now $days days behind your previous bill. Please pay your bills before $lastDeadline. Thank you.";
    foreach ($numbers as $number) {
      sendMessage($number, $message);

      echo "message sent to $number";
    }
  }
}

function formatDate($str) {
  return strtotime(date(MY_DATE_FORMAT, $str));
}

function getTenantContactNumbers($id, $conn) {
  $sql = "SELECT contact_number FROM tenants WHERE room_id = $id";
  $res = $conn->query($sql);
  return $res->fetch_all(MYSQLI_ASSOC);
}

// Mock send message 
// $number = '09206013530';
// $message = "Hi! You are now 3 days behind your previous bill. Please pay your bills before Aug 4, 2022. Thank you.";
// $res = sendMessage('09152068943', $message);
// echo json_encode($res);
?>