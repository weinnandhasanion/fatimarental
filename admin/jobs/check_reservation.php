<?php
include "./../../services/connect.php";

$sql = "SELECT id, `expiry_date` FROM reservations
        WHERE `status` = 0";
$res = $conn->query($sql);
$reservations = $res->fetch_all(MYSQLI_ASSOC);

$now = strtotime(date(DATE_RSS));

foreach ($reservation as $reservations) {
    if ($now > strtotime($reservation['expiry_date'])) {
        $sql = "UPDATE reservation SET `status` = 2 WHERE id = $id";
        $conn->query($sql);
    }
}