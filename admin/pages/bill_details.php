<?php 
include "./../../services/connect.php";

$id = $_GET['id'];
$sql = "SELECT b.*, r.*, t.first_name, t.last_name FROM bills AS b 
INNER JOIN rooms AS r
ON b.room_id = r.id
INNER JOIN tenant_bills AS tb
ON tb.bill_id = b.id
INNER JOIN tenants AS t
ON tb.tenant_id = t.id
WHERE b.id = $id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();


?>
<!DOCTYPE html>
<html lang="en">
<?php include "./../templates/head.php" ?>

<body class="m-3">
  <h3>Bill Details</h3>
  <ul class="mt-2">
    <li>Room ID: <?= $row['room_id'] ?></li>
    <li> Room Number: <?= $row['room_number'] ?></li>
    <li> Billed To: <?= $row['first_name']." ".$row['last_name'] ?></li>
  </ul>
  <?php include "./../templates/scripts.php" ?>
</body>

</html>