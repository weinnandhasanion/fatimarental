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
                                        <div class="col-sm-12 mt-2 d-flex align-items-center flex-wrap"
                                            id="filename-cont-<?= $room["id"] ?>" style="gap: 8px">
                                            <input type="file" hidden id="attachment-<?= $room["id"] ?>" multiple
                                                accept="image/png, image/jpeg" />
                                            <div>
                                                <button
                                                    class="btn btn-sm btn-secondary d-flex justify-content-center align-items-center"
                                                    onclick="attachReceipt(this.getAttribute('data-id'))" type="button"
                                                    data-id="<?= $room["id"] ?>"><i class="material-icons"
                                                        style="font-size: 12px; margin-right: 5px">photo_camera</i>
                                                    Attach
                                                    photo of
                                                    receipt</button>
                                                <small class="text-danger" id="files-<?= $room["id"] ?>-error"></small>
                                            </div>
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

            $('button[name="addtl-charge"]').click(function(e) {
                const id = $(e.target).attr('data-id');
                let {
                    charges
                } = dataMap[id];
                dataMap = mapData(id, 'charges', ++charges);
                $(`#ref-${id}`).append(renderAdditionalCharge(charges, id))
                getAddtlCharges(id, charges);
            });

            billForm.submit(function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('data', JSON.stringify(dataMap));
                Object.keys(dataMap).forEach(key => {
                    if (dataMap[key].files?.length > 0) {
                        for ([i, file] of dataMap[key].files.entries()) {
                            formData.append(`${key}-${i+1}`, file);
                        }
                    }
                });

                $('#bill-btn').attr('disabled', 'disabled');
                $('small[id$="-error"]').text('');
                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: "./../functions/add_bill.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 800000,
                    success: function(resp) {
                        $('#bill-btn').removeAttr('disabled');
                        const res = JSON.parse(resp);
                        if (res.status === 422) {
                            renderErrors(res.errors);
                        }

                        if (res.status === 200) {
                            alert('Bill successfully published.');
                            window.location.href = './billing.php';
                        }
                    },
                    error: function(err) {
                        console.error(err);
                    }
                });
            });
        });

        function attachReceipt(id) {
            const fileInput = $(`#attachment-${id}`);
            fileInput.click();

            fileInput.on('change', function(e) {
                const {
                    files
                } = e.target;

                filesToStore = Array.from(files).length < 1 ? dataMap[id].files ?? [] : Array.from(files);

                dataMap = mapData(id, 'files', filesToStore);

                appendImageName(filesToStore, id);
            });
        }

        function appendImageName(files, id) {
            $(`button[id^="receipt-images-${id}"]`).remove();
            $(`#remove-img-${id}`).remove();

            const removeElem = `<button type="button" class="btn btn-sm btn-link text-danger" id="remove-img-${id}" onclick="removeImages(this)">
                Remove
            </button>`;

            for ([i, file] of files.entries()) {
                const elem =
                    `<button type="button" class="text-primary btn btn-link" id="receipt-images-${id}-${i}" onclick="clickImage(this.id)">${file.name}</button>`;
                $(`#filename-cont-${id}`).append(elem);
            }

            $(`#filename-cont-${id}`).append(removeElem);
        }

        function clickImage(elemId) {
            const [index, id] = elemId.split('-').reverse();
            const reader = new FileReader();
            const file = dataMap[id].files[index];
            reader.addEventListener('load', event => {
                $.dialog({
                    backgroundDismiss: true,
                    title: file.name,
                    content: `<div style='display: flex; justify-content: center; align-items: center;'>
              <img src='${event.target.result}' />
            </div>`
                });
            });
            reader.readAsDataURL(file);
        };

        function removeImages(elem) {
            const [id] = elem.id.split('-').reverse();

            dataMap = mapData(id, 'files', []);

            $(`button[id^="receipt-images-${id}"]`).remove();
            $(elem).remove();
        }

        function cancel() {
            const res = confirm('Are you sure to cancel billing?');
            if (res) window.location.href = "./billing.php";
        }
        </script>
</body>

</html>