<?php 
include './../../services/connect.php';

$id = $_GET['id'];

$sql = "SELECT * FROM reservations WHERE id = $id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

$row['move_date'] = date("F d, Y", strtotime($row['move_date']));
$row['date_added'] = date("F d, Y", strtotime($row['date_added']));

echo json_encode($row);
?>