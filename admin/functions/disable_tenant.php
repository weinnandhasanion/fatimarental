<?php 
include "./../../services/connect.php";

$id = $_POST['id'];

$sql = "UPDATE tenants SET account_status = 1 WHERE id = '$id'";
$res = $conn->query($sql);

if ($res) {
  echo 200;
} else {
  echo 422;
}
?>