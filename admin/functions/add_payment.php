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
  sendMessage('09206013530', "You have paid P$amount.00 to Fatima Rental. Thank you. \nRef. No. $ref_id.");
}
?>