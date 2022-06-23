<?php 

$payment_id = $conn->insert_id;
$sql = "INSERT INTO bill_payments (bill_id, payment_id)
  VALUES (". intval($bill_id) .", $payment_id)";
$res = $conn->query($sql);


?>