<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: ./../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/login.css">
	<title>Login page</title>
</head>
<body>

	<div style="display: none"></div>
	<div class="container">
		<form action="#" class="login active" id="login-form">
			<h2 class="title">Login with your account</h2>
			<div class="form-group">
				<label for="email">Username</label>
				<div class="input-group">
					<input type="text" id="username" name="username" placeholder="Username">
					<i class='bx bx-envelope'></i>
				</div>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<div class="input-group">
					<input type="password" id="password" name="password" placeholder="Your password">
					<i class='bx bx-lock-alt' ></i>
				</div>
			</div>
			<button type="submit" class="btn-submit">Login</button>
			<!-- <p>I don't have an account. <a href="#" onclick="switchForm('register', event)">Register</a></p> -->
		</form>

		<form action="#" class="register">
			<h2 class="title">Register your account</h2>
			<div class="form-group">
				<label for="email">Email</label>
				<div class="input-group">
					<input type="email" id="email" placeholder="Email address">
					<i class='bx bx-envelope'></i>
				</div>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<div class="input-group">
					<input type="password" pattern=".{8,}" id="password" placeholder="Your password">
					<i class='bx bx-lock-alt' ></i>
				</div>
				<span class="help-text">At least 8 characters</span>
			</div>
			<div class="form-group">
				<label for="confirm-pass">Confirm password</label>
				<div class="input-group">
					<input type="password" id="confirm-pass" placeholder="Enter password again">
					<i class='bx bx-lock-alt' ></i>
				</div>
				<span class="help-text">Confirm password must be same with password</span>
			</div>
			<button type="submit" class="btn-submit">Register</button>
			<p>I already have an account. <a href="#" onclick="switchForm('login', event)">Login</a></p>
		</form>
	</div>

	<script src="js/js.js"></script>

	<?php
		$filename = basename(__FILE__);
		include './templates/scripts.php'
	?>
	<script>
		$(document).ready(function() {
			$("#login-form").submit(function(e) {
				e.preventDefault();

				$.post("./../services/login.php?from=tenant", $(this).serializeArray(), function(data) {
					let res = JSON.parse(data);

					alert(res.message);
					if (res.status === 200) {
						window.location.reload();
					}
				});
			});
		});
	</script>
</body>
</html>