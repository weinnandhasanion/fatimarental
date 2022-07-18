<?php 
include "./../../services/connect.php";
include './send_message.php';

$bill_id = $_POST['bill_id'];
$amount = $_POST['amount'];
$remarks = $_POST['remarks'];

$errors = [];
$status = 422;

if (!preg_match("/^[0-9]+$/", $amount)) {
  $errors['amount'] = 'The amount must only contain digits.';
}

if (count($errors) < 1) {
  $ref_id = md5(uniqid());
  $sql = "INSERT INTO payments (reference_id, amount, remarks) 
    VALUES ('$ref_id', ". intval($amount) .", '$remarks')";
  $res = $conn->query($sql);
  if ($res) {
    include_once "./add_bill_payment.php";
    $status = 200;
  }
}

echo json_encode([
  'status' => $status,
  'errors' => $errors,
]);

if ($status === 200) {
  $sql = "SELECT b.bill_to_tenant_id, t.contact_number FROM bills AS b
    INNER JOIN tenants AS t
    ON t.id = b.bill_to_tenant_id
    WHERE b.id = $bill_id";
  
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();

  $contact_number = $row['contact_number'];

  sendMessage($contact_number, "You have paid P$amount.00 to Fatima Rental. Thank you. \nRef. No. $ref_id.");
}
?>