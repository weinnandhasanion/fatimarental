<div class="row mb-4">
  <div class="col-sm-12">
    <label for="">Current Password</label>
    <input style="max-width: 400px" type="password" class="form-control" id="current">
    <small class='text-danger error-message' id="current-error"></small>
  </div>
</div>
<div class="row mb-4">
  <div class="col-sm-12">
    <label for="">New Password</label>
    <input style="max-width: 400px" type="password" class="form-control" id="new_pass">
    <small class='text-danger error-message' id="new_pass-error"></small>
  </div>
</div>
<div class="row mb-4">
  <div class="col-sm-12">
    <label for="">Confirm Password</label>
    <input style="max-width: 400px" type="password" class="form-control" id="confirm_pass">
    <small class='text-danger error-message' id="confirm_pass-error"></small>
  </div>
</div>
<button class="btn btn-primary" id="change-pass-btn">Change Password</button>
<br>
<small class="text-success" id="success" style="display: none">Password successfully changed.</small>

<script>
const keys = ['current', 'new_pass', 'confirm_pass'];
const success_msg = $('#success');

$('.form-control').keydown(function() {
  if (success_msg.css('display') === 'block') success_msg.css('display', 'none');
})

$('#change-pass-btn').click(function() {
  $('.error-message').text('');
  const data = keys.reduce((obj, key) => ({
    ...obj,
    [key]: $(`#${key}`).val()
  }), {})

  $.post('./functions/change_password.php', data, function(res) {
    const {
      errors
    } = JSON.parse(res)
    if (Object.keys(errors).length > 0) {
      for (key in errors) {
        $(`#${key}-error`).text(errors[key])
      }
    } else {
      for (key of keys) {
        $(`#${key}`).val('');
        $(`#${key}-error`).text('');
      }
      success_msg.css('display', 'block');
    }
  });
})
</script>