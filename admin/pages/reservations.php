<?php
include_once './redirect.php';
include './../../services/connect.php';

$sort = $_GET['sort'] ?? 'pending';

$statuses = [
  "pending" => [0, "New"],
  "approved" => [1, "Approved"],
  "rejected" => [2, "Rejected"]
];

$sql = "SELECT re.*, ro.room_name FROM reservations AS re
  INNER JOIN rooms AS ro
  ON re.room_id = ro.id
  WHERE re.`status` = ". $statuses[$sort][0];
$res = $conn->query($sql);
$reservations = [];
if ($res) $reservations = $res->fetch_all(MYSQLI_ASSOC);
?>

<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>

<body>
    <div class="wrapper">
        <div class="body-overlay"></div>

        <?php include './../templates/nav.php' ?>

        <!-- Page Content  -->
        <div id="content">

            <?php include './../templates/topnav.php'?>
            <div class="main-content">
                <div class="row ">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link <?= $sort === 'pending' ? "active" : ""?>"
                                    href="./reservations.php">New
                                    Reservations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $sort === 'approved' ? "active" : ""?>"
                                    href="./reservations.php?sort=approved">Approved Reservations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $sort === 'rejected' ? "active" : ""?>"
                                    href="./reservations.php?sort=rejected">Rejected Reservations</a>
                            </li>
                        </ul>
                        <div class="card"
                            style="min-height: 485px; margin-top: 0; border: 1px solid rgba(0,0,0,.125) !important; border-top: 0px !important">
                            <div class="card-header card-header-text d-flex justify-content-start align-items-center">
                                <h4 class="card-title flex-grow-1"><?=$statuses[$sort][1]?> Reservations</h4>
                            </div>
                            <div class="card-content table-responsive">
                                <table class="table table-hover" id='reservations-table'>
                                    <thead class="text-primary">
                                        <tr>
                                            <th style="width: 200px">Name</th>
                                            <th>Room #</th>
                                            <th>Move Date</th>
                                            <th style="width: 250px">Message</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reservations as $res) {?>
                                        <tr>
                                            <td class="align-middle"><?=$res['first_name']." ".$res['last_name']?></td>
                                            <td class="align-middle">
                                                <?=$res['room_name']?>
                                            </td>
                                            <td class="align-middle"><?=date("F d, Y", strtotime($res['move_date']))?>
                                            </td>
                                            <td class="align-middle">
                                                <?=empty($res['message']) ? "N/A" : $res['message']?></td>
                                            <td class="align-middle">
                                                <button class="btn btn-link btn-small" data-id="<?=$res['id']?>"
                                                    onclick="viewReservationDetails($(this).attr('data-id'))">Details</button>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservation Details Modal -->
        <div id="details-modal" tabindex="-1" class="modal fade" role="dialog">
            <form id="details-form" method="POST">
                <div class="modal-dialog" role='document'>
                    <div class="modal-content" style="max-width:600px">
                        <div class="modal-header">
                            <h5 class="modal-title">Reservation Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="details-id" />
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label>Name</label>
                                    <input readonly class="form-control" type="text" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label>Email Address</label>
                                    <input readonly class="form-control" type="text" name="email_address"
                                        id="email_address" required>
                                </div>
                                <div class="col-sm-6">
                                    <label>Contact Number</label>
                                    <input readonly class="form-control" type="text" name="contact_number"
                                        id="contact_number" required>
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label>Date Created</label>
                                    <input readonly class="form-control" type="text" name="date_added" id="date_added"
                                        required>
                                </div>
                                <div class="col-sm-6">
                                    <label>Move Date</label>
                                    <input readonly class="form-control" type="text" name="move_date" id="move_date"
                                        required>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label>Room</label>
                                    <input readonly class="form-control" type="text" name="room_name" id="room_name"
                                        required>
                                </div>
                                <div class="col-sm-6">
                                    <label>Downpayment Deadline</label>
                                    <input readonly class="form-control" type="text"
                                        name="reservation_account_expiry_date" id="reservation_account_expiry_date"
                                        required>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label>Message</label>
                                    <textarea readonly class='form-control' name="" id="message" rows="5"
                                        style="resize: none"></textarea>
                                </div>
                            </div>
                        </div>
                        <?php
            if ($sort === 'pending'):
              ?>
                        <div class="modal-footer">
                            <button type="button" id="approve" class="btn btn-success">Approve</button>
                            <button type="button" id="reject" class="btn btn-danger">Reject</button>
                        </div>
                        <?php
            elseif ($sort === 'approved'):
                ?>
                        <div class="modal-footer">
                            <button type="button" id="create-tenant" class="btn btn-success">Create Tenant</button>
                        </div>
                        <?php
            endif;
            ?>
                    </div>
                </div>
            </form>
        </div>

        <?php include './../templates/scripts.php' ?>

        <script type="text/javascript">
        function viewReservationDetails(id) {
            $.get("./../functions/reservation_details.php?id=" + id, function(resp) {
                const data = JSON.parse(resp);
                $('#details-id').val(data.id);
                for (key in data) {
                    $(`#${key}`).val(data[key]);
                }
                $('#details-modal').modal('show');

                $('#approve').click(function() {
                    updateReservation(id, 'approve');
                })
                $('#reject').click(function() {
                    updateReservation(id, 'reject');
                })
            });
        }

        function updateReservation(id, status) {
            $.get("./../functions/update_reservation.php?id=" + id + "&status=" + status, function(resp) {
                const res = JSON.parse(resp);
                if (res.status === 200) {
                    alert(res.message);
                    window.location.reload();
                }
            });
        }

        $(document).ready(function() {
            $('#reservations-table').DataTable()

            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });

            $('.more-button,.body-overlay').on('click', function() {
                $('#sidebar,.body-overlay').toggleClass('show-nav');
            });

            $('.sidebar-link').click(function(e) {
                if ($(this).hasClass('active')) {
                    e.preventDefault();
                    return;
                }
                $('.sidebar-link').removeClass('active');
                $(this).addClass('active');
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

            $('#create-tenant').click(function() {
                const id = $('#details-id').val();
                $.get(`./../functions/reservation_details.php?id=${id}&create_tenant=true`,
                    function(res) {
                        const data = JSON.parse(res);

                        const keys = ['room_id', 'first_name', 'middle_initial', 'last_name',
                            'email_address', 'contact_number'
                        ];

                        let params = keys.map(key => `${key}=${data[key]}`);

                        window.location.href = './tenants.php?' + params.join('&') +
                            '&create_tenant=true';
                    });
            });
        });
        </script>
</body>

</html>