<?php
include "./../../services/connect.php";
session_start();

$room_id = $_POST['room_id'];
$room_charge = $_POST['room_charge'];
$water_bill = $_POST['water_bill'];
$electricity_bill = $_POST['electricity_bill'];
$start_period = $_POST['start_period'];
$end_period = $_POST['end_period'];
$bill_to_tenant_id = $_POST['bill_to'];
$addtl_charges = [];

if (isset($_POST['additional_charges']) && count($_POST['additional_charges']) > 0) {
    $addtl_charges = $_POST['additional_charges'];
}

$errors = [];
$status = 422;

if (!preg_match("/^[0-9]*$/", $water_bill)) {
    $errors['water_bill'] = 'Water bill should not contain any letters.';
}

if (!isset($bill_to_tenant_id)) {
    $errors['bill_to'] = 'Please select a tenant to bill.';
}

if (!preg_match("/^[0-9]*$/", $electricity_bill)) {
    $errors['electricity_bill'] = 'Electricity bill should not contain any letters.';
}

if (strtotime($start_period) > strtotime($end_period)) {
    $errors['start_period'] = 'Start period must be a date before end period.';
} else {
    $start_period = date("Y-m-d", strtotime($start_period));
}

if (strtotime($end_period) < strtotime($start_period)) {
    $errors['end_period'] = 'End period must be a date after start period.';
} else {
    $end_period = date("Y-m-d", strtotime($end_period));
}

foreach ($addtl_charges as $idx => $addtl) {
    if ($addtl["name-$idx"] === '' && !empty($addtl["charge-$idx"])) {
        $errors["name-$idx"] = 'Please enter an item.';
    }

    if ($addtl["charge-$idx"] === '' && !empty($addtl["name-$idx"])) {
        $errors["name-$idx"] = 'Please enter an amount.';
    }

    if (!preg_match("/^[0-9]*$/", $addtl["charge-$idx"])) {
        $errors["charge-$idx"] = 'Additional charge should not contain any letters.';
    }
}

if (count($errors) < 1) {
    $total = intval($room_charge) + intval($electricity_bill) + intval($water_bill);
    $sql = "INSERT INTO bills (reference_id, room_id, admin_id, bill_to_tenant_id, room_charge, electricity_bill, water_bill, start_period, end_period)
    VALUES ('" . md5(uniqid('', true)) . "', '$room_id', " . $_SESSION['user']['id'] . ", " . intval($bill_to_tenant_id) . ", '$room_charge', '$electricity_bill', '$water_bill', '$start_period', '$end_period')";
    $res = $conn->query($sql);

    if ($res) {
        $bill_id = $conn->insert_id;

        foreach ($addtl_charges as $idx => $charge) {
            $total += intval($charge["charge-$idx"]);
            $sql = "INSERT INTO additional_charges (bill_id, `name`, charge) VALUES ($bill_id, '".$charge["name-$idx"]."', '".$charge["charge-$idx"]."')";
            $res = $conn->query($sql);
        }

        $sql = "UPDATE bills SET `total_amount` = $total WHERE id = $bill_id";
        $conn->query($sql);

        $sql = "SELECT * FROM tenants WHERE room_id = $room_id";
        $res = $conn->query($sql);
        $rows = $res->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            $sql = "INSERT INTO tenant_bills (tenant_id, bill_id) VALUES (".intval($row['id']).", ".intval($bill_id).")";
            $res = $conn->query($sql);
        }
        $status = 200;
    }
}

echo json_encode([
    'errors' => $errors,
    'status' => $status,
]);
