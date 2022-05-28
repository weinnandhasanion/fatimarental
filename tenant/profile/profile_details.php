<div class="row">
  <div class="col-sm-5">
    <label for="">First Name</label>
    <input type="text" class="form-control" readonly value="<?= $user['first_name'] ?>" />
  </div>
  <div class="col-sm-5">
    <label for="">Last Name</label>
    <input type="text" class="form-control" readonly value="<?= $user['last_name'] ?>" />
  </div>
  <div class="col-sm-2">
    <label for="">Middle Initial</label>
    <input type="text" class="form-control" readonly value="<?= $user['middle_initial'] ?>" />
  </div>
</div>
<div class="row mt-4">
  <div class="col-sm-4">
    <label for="">Username</label>
    <input type="text" class="form-control" readonly value="<?= $user['username'] ?>" />
  </div>
  <div class="col-sm-4">
    <label for="">Email Address</label>
    <input type="text" class="form-control" readonly value="<?= $user['email_address'] ?>" />
  </div>
  <div class="col-sm-4">
    <label for="">Contact Number</label>
    <input type="text" class="form-control" readonly value="<?= $user['contact_number'] ?>" />
  </div>
</div>
<div class="row mt-4">
  <div class="col-sm-4">
    <label for="">Birthdate</label>
    <input type="date" class="form-control" readonly value="<?= $user['birthdate'] ?>" />
  </div>
  <div class="col-sm-5">
    <label for="">Address</label>
    <input type="text" class="form-control" readonly value="<?= $user['address'] ?>" />
  </div>
  <div class="col-sm-3">
    <label for="">Room</label>
    <input type="text" class="form-control" readonly value="<?= $user['room_name'] ?>" />
  </div>
</div>