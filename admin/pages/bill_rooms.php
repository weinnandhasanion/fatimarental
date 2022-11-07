<?php
include_once './redirect.php';
include './../../services/connect.php';

$rooms = [];
$roomsSql = "SELECT * FROM rooms";
$res = $conn->query($roomsSql);
foreach ($res->fetch_all(MYSQLI_ASSOC) as $row) {
    $sql = "SELECT COUNT(*) AS co FROM tenants WHERE room_id = " . $row['id'];
    $res = $conn->query($sql);
    $count = $res->fetch_assoc()['co'];
    if ($count > 0) {
        $rooms[] = $row;
    }
}
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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Rooms to Bill</h4>
                            </div>
                            <div class="card-content">
                                <form id="bill-form">
                                    <?php foreach ($rooms as $room): ?>
                                    <h5><?= $room['room_name'] ?></h5>
                                    <div class="row mb-4 input-row" data-id="<?= $room["id"] ?>">
                                        <div class="col-sm-4">
                                            <label for="">Room Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="text" name="room_price-<?= $room["id"] ?>"
                                                    class="form-control" readonly value="<?= $room['price'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <label for="">Water Bill</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="text" name="water_bill-<?= $room["id"] ?>"
                                                    class="form-control input-field">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                            <small id="water_bill-<?= $room["id"] ?>-error" class="text-danger"></small>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="">Electricity Bill</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="text" name="electricity_bill-<?= $room["id"] ?>"
                                                    class="form-control input-field">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                            <small id="electricity_bill-<?= $room["id"] ?>-error"
                                                class="text-danger"></small>
                                        </div>
                                        <span id="ref-<?= $room["id"] ?>"></span>
                                        <div class="col-sm-12 mt-2">
                                            <button class="btn btn-sm btn-success" name="addtl-charge" type="button"
                                                data-id="<?= $room["id"] ?>">+ Add
                                                additional charge</button>
                                        </div>
                                    </div>

                                    <?php endforeach ?>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success" type="submit" id="bill-btn" form="bill-form">Send
                                    Bill</button>
                                <button class="btn btn-link text-danger" onclick="cancel()">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php include './../templates/scripts.php' ?>

        <script type="text/javascript">
        let dataMap = {};

        function mapData(key, subKey, value) {
            const [key2] = subKey.split('-');
            return {
                ...dataMap,
                [key]: {
                    ...dataMap[key],
                    [key2]: value
                }
            }
        }

        function getRoomPrices(id, el) {
            const {
                name,
                value
            } = $(el).find('input[name*="room_price"]').get(0);
            dataMap = mapData(id, name, value)
        }

        function getWaterFields(id, el) {
            const {
                name,
                value
            } = $(el).find('input[name*="water_bill"]').get(0);
            dataMap = mapData(id, name, value)
        }

        function getElecFields(id, el) {
            const {
                name,
                value
            } = $(el).find('input[name*="electricity_bill"]').get(0);
            dataMap = mapData(id, name, value)
        }

        function getAddtlCharges(id, idx) {
            const {
                name: nameKey,
                value: nameValue
            } = $(`input[name*="name_${idx}"][name$="${id}"]`).get(0);
            dataMap = mapData(id, nameKey, nameValue);

            const {
                name: chargeKey,
                value: chargeValue
            } = $(`input[name*="charge_${idx}"][name$="${id}"]`).get(0);
            dataMap = mapData(id, chargeKey, chargeValue);
        }

        function renderAdditionalCharge(index, id) {
            return `
                <div class="col-sm-12 mt-3" id="addtl-charge-${id}-${index}">
                    <div class="row mb-2 charge-cont">
                        <div class="col-sm-5">
                            <input type="text" class="form-control addtl-charge-name input-field" name="name_${index}-${id}" placeholder="Name">
                            <small id='name_${index}-${id}-error' class='text-danger name-error-cont error-text'></small>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₱</span>
                            </div>
                            <input type="text" name="charge_${index}-${id}" class="form-control addtl-charge-charge input-field" placeholder="Charge">
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                            </div>
                            <small id='charge_${index}-${id}-error' class='text-danger charge-error-cont error-text'></small>
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-danger delete-charge-btn" onclick="deleteCharge(${id}, ${index})">Delete</button>
                    </div>
                </div>
            `;
        }

        function deleteCharge(id, idx) {
            $(`#addtl-charge-${id}-${idx}`).remove();
            delete dataMap[id][`name_${idx}`];
            delete dataMap[id][`charge_${idx}`];
            dataMap[id].charges -= 1;
        }

        function renderErrors(errors) {
            Object.keys(errors).forEach(key => {
                Object.keys(errors[key]).forEach(subKey => {
                    $(`#${subKey}-${key}-error`).text(errors[key][subKey]);
                });
            });
        }

        $(document).on('change', '.input-field', function(e) {
            const {
                name,
                value
            } = e.target;
            const [, id] = name.split('-');
            dataMap = mapData(id, name, value);
            console.log('change', dataMap);
        });

        $(document).ready(function() {
            const billForm = $('#bill-form');

            $('.input-row').each((idx, e) => {
                const id = $(e).attr('data-id');
                dataMap = mapData(id, 'charges', 0);
                getRoomPrices(id, e);
                getWaterFields(id, e);
                getElecFields(id, e);
            });

            $('button[name="addtl-charge"]').click(e => {
                const id = $(e.target).attr('data-id');
                let {
                    charges
                } = dataMap[id];
                dataMap = mapData(id, 'charges', ++charges);
                $(`#ref-${id}`).append(renderAdditionalCharge(charges, id))
                getAddtlCharges(id, charges);
                console.log('click', dataMap);
            })

            billForm.submit(e => {
                e.preventDefault();
                $('#bill-btn').attr('disabled', 'disabled');
                $('small[id$="-error"]').text('')
                $.post('./../functions/add_bill.php', {
                    data: JSON.stringify(dataMap)
                }, function(resp) {
                    $('#bill-btn').removeAttr('disabled');
                    const res = JSON.parse(resp);
                    if (res.status === 422) {
                        renderErrors(res.errors);
                    }

                    if (res.status === 200) {
                        alert('Bill successfully published.');
                        window.location.href = './billing.php';
                    }
                });
            });
        });

        function cancel() {
            const res = confirm('Are you sure to cancel billing?');
            if (res) window.location.href = "./billing.php";
        }
        </script>
</body>

</html>