<?php
include "./../../services/connect.php";
include "./send_message.php";
session_start();

$data = $_POST['data'] ? json_decode(json_encode(json_decode($_POST['data'])), true) : [];
$errors = [];
$status = 422;

foreach ($data as $roomKey => $room) {
    // Copy data to errors
    $errors[$roomKey] = $room;
    foreach ($errors[$roomKey] as $subKey => $_) {
        $errors[$roomKey][$subKey] = "";
    }
    
    // Unset unnecessary values for errors
    unset($errors[$roomKey]["room_price"]);
    unset($errors[$roomKey]["charges"]);
}

foreach ($data as $roomKey => $room) {
    // Generate errors
    foreach ($data[$roomKey] as $key => $value) {
        if ($key === "charges") continue;
        if (empty($value)) {
            $errors[$roomKey][$key] = "Please provide a value";
        } else if (!str_contains($key, "name") && !is_numeric($value)) {
            $errors[$roomKey][$key] = "Value should only contain digits.";
        }

        if (empty(($errors[$roomKey][$key]))) {
            unset($errors[$roomKey][$key]);
        }
    }

    // Unset room if no errors
    if (count($errors[$roomKey]) < 1) {
        unset($errors[$roomKey]);
    }
}

if (count($errors) < 1) {
    $sqls = [];
    $status = 200;

    foreach ($data as $roomId => $room) {
        $electricity_bill = intval($room['electricity_bill']);
        $water_bill = intval($room['water_bill']);
        $start_period = date("Y-m-01");
        $end_period = date("Y-m-t");
        $room_charge = intval($room['room_price']);
        $total = intval($room_charge) + intval($electricity_bill) + intval($water_bill);
        foreach (['charges', 'room_price', 'water_bill', 'electricity_bill'] as $key) {
            unset($data[$roomId][$key]);
        }
        
        $sql = "INSERT INTO bills (reference_id, room_id, admin_id, room_charge, electricity_bill, water_bill, start_period, end_period)
                VALUES ('" . md5(uniqid('', true)) . "', '$roomId', " . $_SESSION['user']['id'] . ", '$room_charge', '$electricity_bill', '$water_bill', '$start_period', '$end_period')";
        $res = $conn->query($sql);

        if ($res) {
            $bill_id = $conn->insert_id;

            if (count($data[$roomId]) > 0) {
                $vals = array_values($data[$roomId]);
                $charges = [];
                $i = 0;
                
                while ($i < count($vals)) {
                    $charges[] = [$vals[$i], $vals[++$i]];
                    $i++;
                }
    
                foreach ($charges as $ch) {
                    $total += intval($ch[1]);
                    $sql = "INSERT INTO additional_charges (bill_id, `name`, charge) VALUES ($bill_id, '".$ch[0]."', '".$ch[1]."')";
                    $conn->query($sql);
                }
            }

            $sql = "UPDATE bills SET `total_amount` = $total WHERE id = $bill_id";
            $conn->query($sql);
            $sql = "SELECT contact_number FROM tenants WHERE room_id = $roomId";
            $numbers = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)['contact_number'];
                foreach ($numbers as $number) {
                if (isset($number)) {
                    sendMessage($number, "Your bill for $start_period to $end_period is P$total.00. Please make sure to pay to avoid penalties.");
    }
}

            $sql = "SELECT id, contact_number FROM tenants WHERE room_id = $roomId";
            $tenants = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
            foreach ($tenants as $tenant) {
                if (isset($tenant['contact_number'])) {
                    $sql = "INSERT INTO tenant_bills (tenant_id, bill_id) VALUES ('".$tenant['id']."', ".intval($bill_id).")";
                    $res = $conn->query($sql);
                    sendMessage($tenant['contact_number'], "Your bill for $start_period to $end_period is P$total.00. Please make sure to pay to avoid penalties.");
                }
            }
        }
    }
}

echo json_encode([
    "status" => $status,
    "errors" => $errors
]);

// $room_id = $_POST['room_id'];
// $room_charge = $_POST['room_charge'];
// $water_bill = $_POST['water_bill'];
// $electricity_bill = $_POST['electricity_bill'];
// $start_period = $_POST['start_period'];
// $end_period = $_POST['end_period'];
// $bill_to_tenant_id = $_POST['bill_to'];
// $addtl_charges = [];

// if (isset($_POST['additional_charges']) && count($_POST['additional_charges']) > 0) {
//     $addtl_charges = $_POST['additional_charges'];
// }

// if (empty($_POST['water_bill'])) $water_bill = "0";
// if (empty($_POST['electricity_bill'])) $electricity_bill = "0";

// $errors = [];
// $status = 422;

// if (!preg_match("/^[0-9]*$/", $water_bill)) {
//     $errors['water_bill'] = 'Water bill should not contain any letters.';
// }


// if (!isset($bill_to_tenant_id)) {
//     $errors['bill_to'] = 'Please select a tenant to bill.';
// }

// if (!preg_match("/^[0-9]*$/", $electricity_bill)) {
//     $errors['electricity_bill'] = 'Electricity bill should not contain any letters.';
// }


// if (strtotime($start_period) > strtotime($end_period)) {
//     $errors['start_period'] = 'Start period must be a date before end period.';
// } else {
//     $start_period = date("Y-m-d", strtotime($start_period));
// }

// if (strtotime($end_period) < strtotime($start_period)) {
//     $errors['end_period'] = 'End period must be a date after start period.';
// } else {
//     $end_period = date("Y-m-d", strtotime($end_period));
// }

// foreach ($addtl_charges as $idx => $addtl) {
//     if ($addtl["name-$idx"] === '' && !empty($addtl["charge-$idx"])) {
//         $errors["name-$idx"] = 'Please enter an item.';
//     }

//     if ($addtl["charge-$idx"] === '' && !empty($addtl["name-$idx"])) {
//         $errors["name-$idx"] = 'Please enter an amount.';
//     }

//     if (!preg_match("/^[0-9]*$/", $addtl["charge-$idx"])) {
//         $errors["charge-$idx"] = 'Additional charge should not contain any letters.';
//     }
// }

// if (count($errors) < 1) {
//     $total = intval($room_charge) + intval($electricity_bill) + intval($water_bill);
//     $sql = "INSERT INTO bills (reference_id, room_id, admin_id, bill_to_tenant_id, room_charge, electricity_bill, water_bill, start_period, end_period)
//     VALUES ('" . md5(uniqid('', true)) . "', '$room_id', " . $_SESSION['user']['id'] . ", '$bill_to_tenant_id', '$room_charge', '$electricity_bill', '$water_bill', '$start_period', '$end_period')";
//     $res = $conn->query($sql);

//     if ($res) {
//         $bill_id = $conn->insert_id;

//         foreach ($addtl_charges as $idx => $charge) {
//             $total += intval($charge["charge-$idx"]);
//             $sql = "INSERT INTO additional_charges (bill_id, `name`, charge) VALUES ($bill_id, '".$charge["name_$idx"]."', '".$charge["charge_$idx"]."')";
//             $res = $conn->query($sql);
//         }

//         $sql = "UPDATE bills SET `total_amount` = $total WHERE id = $bill_id";
//         $conn->query($sql);

//         $sql = "SELECT * FROM tenants WHERE room_id = $room_id";
//         $res = $conn->query($sql);
//         $rows = $res->fetch_all(MYSQLI_ASSOC);

//         foreach ($rows as $row) {
//             $sql = "INSERT INTO tenant_bills (tenant_id, bill_id) VALUES ('".$row['id']."', ".intval($bill_id).")";
//             $res = $conn->query($sql);
//         }
//         $status = 200;
//     }
// }

// echo json_encode([
//     'errors' => $errors,
//     'status' => $status,
// ]);