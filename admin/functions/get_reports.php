<?php
include "./../../services/connect.php";

$type = $_POST['type'];
$from_date = $_POST['fromDate'];
$to_date = $_POST['toDate'];

$errors = null;
$data = [["Month", "Year", "Sales Amount"]];
$total = 0;
$filename = "";

if ($type === 'custom' && empty($from_date)) {
    if ($errors === null) {
        $errors = [];
    }

    $errors['from-date'] = "Please select a starting period";
}

if ($type === 'custom' && empty($to_date)) {
    if ($errors === null) {
        $errors = [];
    }

    $errors['to-date'] = "Please select an ending period";
}

switch ($type) {
    case "yearly":
        $year = date("Y");
        $sql = "SELECT date_added, SUM(amount) AS total
          FROM payments
          WHERE YEAR(date_added) = $year GROUP BY MONTH(date_added)";
        $filename = $year . "_income_report.csv";
        break;
    case "previous":
        $year = date("Y", strtotime(date("Y") . " -1 year"));
        $sql = "SELECT date_added, SUM(amount) AS total
          FROM payments
          WHERE YEAR(date_added) = $year GROUP BY MONTH(date_added)";
        $filename = $year . "_income_report.csv";
        break;
    case "alltime":
        $sql = "SELECT date_added, SUM(amount) AS total FROM `payments` GROUP BY MONTH(date_added);";
        $filename = "all_time_income_report.csv";
        break;
    case "custom":
        $from = date("my", strtotime($from_date));
        $to = date("my", strtotime($to_date));
        $sql = "SELECT date_added, SUM(amount) AS total FROM `payments`
            WHERE MONTH(date_added) >= " . date("n", strtotime($from_date)) . "
            AND MONTH(date_added) <= " . date("n", strtotime($to_date)) . "
           GROUP BY MONTH(date_added);";
        $filename = "$from-$to" . "_income_report.csv";
        break;
}

if ($errors === null) {
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        $rows = $res->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            $monthName = date("F", strtotime($row['date_added']));
            $data[] = [$monthName, date("Y"), "P" . $row['total'] . ".00"];
            $total += $row['total'];
        }

        $data[] = [null, "Total Amount", "P$total.00"];
        $newArr = array_map(function ($arr) {
            return implode(",", $arr);
        }, $data);
        $data = implode("\n", $newArr);
    } else {
        $data = null;
    }
}

echo json_encode(['data' => $data, 'filename' => $filename, 'errors' => $errors]);
