<?php 
include "./../../services/connect.php";

$sql = "SELECT id, reservation_account_expiry_date FROM tenants 
        WHERE `status`= 1";
$res = $conn->query($sql);
$tenants = $res->fetch_all(MYSQLI_ASSOC);

$now = strtotime(date(DATE_RSS));

foreach ($tenants as $tenant) {
  if ($now > strtotime($tenant['reservation_account_expiry_date'])) {
    $sql = "UPDATE tenants SET account_status = 2 WHERE id = '".$tenant['id']."'";
    $conn->query($sql);
  }
}
?>