<?php
include './../../services/connect.php';

$id = $_GET['id'];

$sql = "SELECT id, first_name, last_name FROM tenants WHERE account_status = 0 AND room_id = " . intval($id);
$res = $conn->query($sql);

echo json_encode($res->fetch_all(MYSQLI_ASSOC));