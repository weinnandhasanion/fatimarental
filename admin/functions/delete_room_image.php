<?php 
include "./../../services/connect.php";

$id = $_POST['id'];
$status = 422;

$sql = "DELETE FROM room_images WHERE id = $id";
$res = $conn->query($sql);

if ($res) $status = 200;

echo json_encode(['status' => $status]);

?>