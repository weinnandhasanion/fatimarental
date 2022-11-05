<?php
include './../../services/connect.php';
include_once './redirect.php';

function consoleLog($toLog)
{
    $x = json_encode($toLog);
    echo "<script>console.log($x)</script>";
}

$rooms = [];
$sql = "SELECT * FROM rooms";
$roomsRes = $conn->query($sql);
if ($roomsRes) {
    $rooms = array_map(function ($room) use ($conn) {
        $id = $room['id'];
        $room['tenants'] = [];
        $room['payment_status'] = [
            'paid' => false,
            'payment_id' => null,
        ];
        $name = $room['room_name'];
        $sql = "SELECT * FROM tenants WHERE room_id = $id AND `status` = 0 AND `account_status` = 0";
        $res = $conn->query($sql);
        $tenants = [];
        $room['status'] = ($room['status'] === 0 || empty($room['status'])) ? "Available" : "Under Maintenance";

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                // echo "<script>console.log('room $name', '".$row['id']."')</script>";
                $tenants[] = $row;
            }
            $room['tenants'] = $tenants;
        }

        $sql = "SELECT COUNT(*) AS reserved_tenants FROM tenants WHERE room_id = $id AND `status` = 1";
        $room['reserved_tenants'] = $conn->query($sql)->fetch_assoc()['reserved_tenants'];

        $sql = "SELECT * FROM bills WHERE room_id = $id ORDER BY id DESC LIMIT 1";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            $bill = $res->fetch_all(MYSQLI_ASSOC)[0];
            // var_dump($bill);
            $bill_id = $bill['id'];
            $bill_from = $bill['start_period'];
            $bill_to = $bill['end_period'];
            $amount = $bill['total_amount'];
            $sql = "SELECT bp.*, p.amount FROM bill_payments AS bp
              INNER JOIN payments AS p
              ON bp.payment_id = p.id
              WHERE bp.bill_id = $bill_id AND (bp.date_added >= '$bill_from' AND bp.date_added <= '$bill_to') ORDER BY bp.id DESC LIMIT 1";
            $res = $conn->query($sql);
            if ($res->num_rows > 0) {
                $payment = $res->fetch_all(MYSQLI_ASSOC)[0];
                $payment_id = $payment['id'];
                $payment_amt = $payment['amount'];
                if (intval($amount) <= intval($payment_amt)) {
                    $room['payment_status']['paid'] = true;
                    $room['payment_status']['payment_id'] = $payment_id;
                }

            }
        }

        return $room;
    }, $roomsRes->fetch_all(MYSQLI_ASSOC));

    // echo "<script>console.log(JSON.parse('".json_encode($rooms)."'))</script>";

}

function renderTenants($tenants)
{
    if (count($tenants) > 0) {
        return implode(", ", array_map(function ($tenant) {
            return $tenant['first_name'] . " " . $tenant['last_name'];
        }, $tenants));
    }

    return "N/A";
}
?>

<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>

<body>
    <div class="wrapper">
        <div class="body-overlay"></div>

        <!-- Include navigation bar -->
        <?php include './../templates/nav.php'?>

        <!-- Page Content  -->
        <div id="content">
            <?php include './../templates/topnav.php'?>
            <div class="main-content">
                <div class="row ">
                    <div class="col-sm-12">
                        <div class="card" style="min-height: 485px">
                            <div class="card-header card-header-text d-flex justify-content-start align-items-center">
                                <h4 class="card-title flex-grow-1">Rooms</h4>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#add-room-modal">Add
                                    &#43;</button>
                            </div>
                            <div class="card-content table-responsive">
                                <table class="table table-hover" id='rooms-table'>
                                    <thead class="text-primary">
                                        <tr>
                                            <th>Room</th>
                                            <th>Occupants</th>
                                            <th>Room Status</th>
                                            <th>Payment Status</th>
                                            <th>Capacity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rooms as $room) {?>
                                        <tr>
                                            <td class="align-middle"><?=$room['room_name']?></td>
                                            <td class="align-middle text-truncate"><?=count($room['tenants'])?></td>
                                            <td class="align-middle">
                                                <?=count($room['tenants']) >= intval($room['capacity']) ? 'Full' : $room['status']?>
                                            </td>
                                            <td class="align-middle">
                                                <?php
                                                if (count($room['tenants']) === 0):
                                                ?>
                                                N/A
                                                <?php 
                                                elseif($room['payment_status']['paid']):
                                                ?>
                                                <button class='btn btn-link text-success'
                                                    onclick='viewPaymentDetails(<?=$room["payment_status"]["payment_id"]?>)'>Paid</button>
                                                <?php
                                                else:
                                                ?>
                                                Unpaid
                                                <?php
                                                endif;
                                                ?>
                                            </td>
                                            <td class="align-middle"><?=$room['capacity']?></td>
                                            <td class="align-middle">
                                                <button class="btn btn-link btn-small" data-id="<?=$room['id']?>"
                                                    onclick="viewRoomDetails($(this).attr('data-id'))">Details</button>
                                                <button class="btn btn-small btn-link" data-id="<?=$room['id']?>"
                                                    onclick="updateRoom($(this).attr('data-id'))">Update</button>
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

        <!-- Add Room Modal -->
        <div id="add-room-modal" tabindex="-1" class="modal fade" role="dialog">
            <form id="add-room-form" enctype="multipart/form-data">
                <div class="modal-dialog modal-lg" role='document'>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Room</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label>Room</label>
                                    <input class="form-control" type="text" name="room_name" required>
                                    <small id='room_name-error' class='text-danger'></small>
                                </div>
                                <div class="col-sm-4">
                                    <label for="price">Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₱</span>
                                        </div>
                                        <input type="text" name="price" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                    <small class="text-danger" id="price-error"></small>
                                </div>
                                <div class="col-sm-4">
                                    <label for="capacity">Capacity</label>
                                    <div class="input-group">
                                        <input type="number" name="capacity" class="form-control" min="0">
                                        <div class="input-group-append">
                                            <span class="input-group-text">pax</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label>Room Description</label>
                                    <textarea style="resize: none" rows="5" class="form-control" type="text"
                                        name="description" required></textarea>
                                    <small id='description-error' class='text-danger'></small>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12 d-flex flex-column">
                                    Room Images
                                    <input type="file" hidden multiple id="file-input" name="room_images">
                                    <small id="room_images-error" class="text-danger"></small>
                                    <small id="pathname-cont"></small>
                                    <button type="button" class="btn btn-secondary btn-sm" style="width: 120px"
                                        onclick="document.querySelector('#file-input').click()">Upload Image/s</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="add-room-btn" class="btn btn-success">Add</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Update Room Modal -->
        <div id="update-room-modal" tabindex="-1" class="modal fade" role="dialog">
            <form id="update-room-form" enctype="multipart/form-data">
                <input type="hidden" id="room_id-update" name="id-update" />
                <div class="modal-dialog modal-lg" role='document'>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Room</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label>Room</label>
                                    <input class="form-control" type="text" name="room_name-update" required>
                                    <small id='room_name-error-update' class='text-danger'></small>
                                </div>
                                <div class="col-sm-4">
                                    <label for="price">Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₱</span>
                                        </div>
                                        <input type="text" name="price-update" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                    <small class="text-danger" id="price-error-update"></small>
                                </div>
                                <div class="col-sm-4">
                                    <label for="capacity">Capacity</label>
                                    <div class="input-group">
                                        <input type="number" name="capacity-update" class="form-control" min="0">
                                        <div class="input-group-append">
                                            <span class="input-group-text">pax</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <label>Room Description</label>
                                    <textarea style="resize: none" rows="5" class="form-control" type="text"
                                        name="description-update" required></textarea>
                                    <small id='description-error-update' class='text-danger'></small>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label for="">Room Status</label>
                                    <select name="status-update" id="status-update" class="form-control">
                                        <option value="0">Available</option>
                                        <option value="2">Under Maintenance</option>
                                    </select>
                                    <small id='status-error-update' class='text-danger'></small>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12 d-flex flex-column">
                                    Room Images
                                    <!-- <input type="file" hidden multiple id="file-input-update" name="room_images-update">
                  <small id="room_images-error-update" class="text-danger"></small>
                  <small id="pathname-cont-update"></small>
                  <button type="button" class="btn btn-secondary btn-sm" style="width: 120px"
                    onclick="document.querySelector('#file-input-update').click()">Upload Image/s</button> -->
                                    <button type="button" class="btn btn-secondary btn-sm" style="width: 120px"
                                        id="update-images-btn">Update
                                        images</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- View Room Modal -->
        <div id="view-room-modal" tabindex="-1" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg" role='document'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Room Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label>Room</label>
                                <input class="form-control" type="text" name="room_name-view" required readonly>
                            </div>
                            <div class="col-sm-4">
                                <label for="price">Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₱</span>
                                    </div>
                                    <input type="text" name="price-view" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="capacity">Capacity</label>
                                <div class="input-group">
                                    <input type="number" name="capacity-view" class="form-control" min="0" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">pax</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <label>Room Description</label>
                                <textarea style="resize: none" rows="5" class="form-control" type="text"
                                    name="description-view" required readonly></textarea>
                                <small id='description-error' class='text-danger'></small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <label>Occupants</label>
                                <input class="form-control" type="text" name="tenants-view" required readonly>
                            </div>

                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label>Reserved Tenants</label>
                                <input class="form-control" type="text" name="reserved_tenants-view" required readonly>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Room Status</label>
                                <input class="form-control" type="text" name="status-view" required readonly>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Total Income</label>
                                <input class="form-control" type="text" name="total_amount-view" required readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12 d-flex flex-column">
                                Room Images
                                <div id="room-images-cont"
                                    style='display: flex; gap: 15px; flex-wrap: wrap; align-items: center;'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include './../templates/scripts.php'?>

    <script type="text/javascript">
    // View room modal
    function viewRoomDetails(id) {
        $.get('./../functions/room_details.php?id=' + id, function(res) {
            let data = JSON.parse(res);
            for (key in data) {
                $(`input[name=${key}-view]`).val(data[key]);
            }
            $('textarea[name=description-view]').val(data.description);
            if (data.room_images.length > 0) {
                $('#room-images-cont').html('');
                data.room_images.forEach(function(img) {
                    $('#room-images-cont').append(`
            <div style='width: 32%; height: 200px; overflow: hidden;'>
              <img src="./../../uploads/${img.image_pathname}" style='min-height: 100%; width: 100%; object-fit: cover;' />
            </div>
          `);
                })
            } else {
                $('#room-images-cont').html('<small>No images to show.</small>');
            }
            $('#view-room-modal').modal('show');
        });
    }

    // Update room modal
    function updateRoom(id) {
        $.get('./../functions/room_details.php?id=' + id, function(res) {
            let data = JSON.parse(res);
            for (key in data) {
                $(`input[name=${key}-update]`).val(data[key]);
            }
            $('#status-update').val((data.status === 'Available') ? '0' : '2');
            $('textarea[name=description-update]').val(data.description);
            $('#room_id-update').val(id);
            $('#update-room-modal').modal('show');
        })
    }

    // View room payment if paid
    function viewPaymentDetails(id) {
        window.location.href = "./payments.php?pid=" + id;
    }

    function removeErrorTexts() {
        $('.text-danger').each(function(_, el) {
            if (el.innerText !== '') el.innerText = '';
        });
    }

    $('#add-room-modal').on('hidden.bs.modal', function() {
        removeErrorTexts()
    });
    $('#update-room-modal').on('hidden.bs.modal', function() {
        removeErrorTexts()
    });
    $('#view-room-modal').on('hidden.bs.modal', function() {
        removeErrorTexts()
        $('input[name=total_amount-view]').val('')
    });

    $(document).ready(function() {
        $('#rooms-table').DataTable();

        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
            $('#content').toggleClass('active');
        });

        $('.more-button,.body-overlay').on('click', function() {
            $('#sidebar,.body-overlay').toggleClass('show-nav');
        });

        // This function runs after choosing files
        $('#file-input').change(function() {
            let files = $(this)[0].files;
            let textCont = $('#pathname-cont');
            if (files.length > 1) {
                textCont.text(`${files.length} files`)
            } else {
                textCont.text(files[0].name);
            }
        });

        //  // This function runs after choosing files
        //  $('#file-input-update').change(function() {
        //   let files = $(this)[0].files;
        //   let textCont = $('#pathname-cont-update');
        //   if (files.length > 1) {
        //     textCont.text(`${files.length} files`)
        //   } else {
        //     textCont.text(files[0].name);
        //   }
        // });

        $('#add-room-form').submit(function(e) {
            e.preventDefault();

            $('#add-room-btn').attr('disabled', 'disabled');

            let data = $(this).serializeArray();
            let formData = new FormData();
            let file = document.querySelector('#file-input');

            removeErrorTexts();

            data.forEach((d) => formData.append(d.name, d.value));
            $.each($("#file-input")[0].files, function(i, file) {
                formData.append('file[]', file);
            });

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "./../functions/add_room.php",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 800000,
                success: function(data) {
                    let res = JSON.parse(data);
                    if (res.status === 422) {
                        $('#add-room-btn').removeAttr('disabled');

                        for (error in res.errors) {
                            $(`#${error}-error`).text(res.errors[error]);
                        }
                    } else {
                        alert('Room successfully added.');
                        window.location.reload();
                    }
                },
            });
        });

        // Updating room
        $('#update-room-form').submit(function(e) {
            e.preventDefault();

            removeErrorTexts();

            const postData = $(this).serializeArray().reduce((obj, item) => ({
                ...obj,
                [item.name.split('-')[0]]: item.value
            }), {});

            $.post('./../functions/update_room.php', postData, function(res) {
                const data = JSON.parse(res);
                if (data.status === 422) {
                    for (error in data.errors) {
                        $(`#${error}-error-update`).text(data.errors[error]);
                    }
                } else {
                    alert('Room successfully updated.');
                    window.location.reload();
                }
            });
        });


        $('#update-images-btn').click(function() {
            const id = parseInt($('#room_id-update').val());

            window.location.href = './edit_room_images.php?id=' + id;
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