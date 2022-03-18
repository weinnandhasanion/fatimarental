<?php
include './connect.php';

$id = $_GET['id'];

$sql = "SELECT * FROM tenants WHERE id = $id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

$username = strtolower($row['first_name'][0] . '-' . $row['last_name'] . $row['id']);

$sql = "UPDATE tenants SET username = '$username' WHERE id = $id";
$res = $conn->query($sql);

if ($res) {
  echo json_encode($username);
} else {
  echo json_encode(null);
}
