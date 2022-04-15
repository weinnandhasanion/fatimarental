<?php 
include './../../services/connect.php';

$id = $_GET['id'];

if (empty($id)) {
  echo null;
  return;
}

$sql = "SELECT price FROM rooms WHERE id = " . intval($id);
$res = $conn->query($sql);
$row = $res->fetch_assoc();

echo $row['price'];
?>