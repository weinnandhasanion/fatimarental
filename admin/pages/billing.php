<?php
include './../../services/connect.php';
include_once './redirect.php';

$bills = [];
$sql = "SELECT b.*, r.room_name FROM bills AS b
  INNER JOIN rooms AS r
  ON b.room_id = r.id";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
  $rows = $res->fetch_all(MYSQLI_ASSOC);
  foreach ($rows as $i => $row) {
    $sql = "SELECT sum(p.amount) AS total from payments as p 
      inner join bill_payments as bp on bp.payment_id = p.id 
      inner join bills as b on b.id = bp.bill_id
      WHERE bp.bill_id = " . $row['id'];
    $res = $conn->query($sql);
    $amount = $res->fetch_assoc();
    $row['amount_paid'] = $amount['total'] ? intval($amount['total']) : 0;
    $bills[] = $row;
  }
} 
?>
<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>

<body>
    <div class="wrapper">
        <div class="body-overlay"></div>

        <?php include './../templates/nav.php'?>

        <!-- Page Content  -->
        <div id="content">
            <?php include './../templates/topnav.php'?>
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="min-height: 485px">
                            <div class="card-header card-header-text d-flex justify-content-start align-items-center">
                                <h4 class="card-title flex-grow-1">Billing</h4>
                                <button class="btn btn-success" data-toggle="modal" data-target="#add-bill-modal"
                                    onclick="window.location.href = './bill_rooms.php'" id="add-bill-btn">New Bill
                                    &#43;</button>
                            </div>
                            <div class="card-content table-responsive">
                                <table class="table table-hover" id='billing-table'>
                                    <thead class="text-primary">
                                        <tr>
                                            <th>Room</th>
                                            <th>Period (MM/DD/YY)</th>
                                            <th>Reference ID</th>
                                            <th>Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bills as $bill) {?>
                                        <tr>
                                            <td class="align-middle text-truncate"><?=$bill['room_name']?></td>
                                            <td class="align-middle">
                                                <?=date("m/d/y", strtotime($bill['start_period']))?> -
                                                <?=date("m/d/y", strtotime($bill['end_period']))?>
                                            </td>
                                            <td class="align-middle"><?= $bill['reference_id'] ?></td>
                                            <td class="align-middle"><?php 
                                            echo $bill['amount_paid'] >= $bill['total_amount'] ? "Paid" : "P". (intval($bill['total_amount']) - $bill['amount_paid']) .".00";
                                            ?></td>
                                            <td class="align-middle">
                                                <?php if($bill['total_amount'] > $bill['amount_paid']) {  ?>
                                                <button class="btn btn-link btn-small text-success"
                                                    data-id="<?=$bill['id']?>"
                                                    onclick="window.location.href='./payments.php?bill_id=<?=$bill['id']?>'">Pay</button>
                                                <?php } ?>
                                                <button class="btn btn-link btn-small" data-id="<?=$bill['id']?>"
                                                    onclick="viewBillDetails($(this).attr('data-id'))">Details</button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include './../templates/scripts.php'?>

    <script type="text/javascript">
    let addtlChargeIndex = 0;

    function viewBillDetails(id) {
        window.open("./../../shared/bill_details.php?id=" + id, "_blank");
    }

    $(document).ready(function() {
        $('#billing-table').DataTable();

        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
            $('#content').toggleClass('active');
        });

        $('.more-button,.body-overlay').on('click', function() {
            $('#sidebar,.body-overlay').toggleClass('show-nav');
        });

        $('#logout-link').click(function() {
            let x = confirm("Do you want to logout?");
            if (x) {
                $.get("./../../services/logout.php", function(message) {
                    alert(message);
                    window.location = './../login.php';
                });
            }
        });
    });
    </script>
</body>

</html>