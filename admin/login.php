<?php 
session_start();
if (isset($_SESSION['user'])) {
  header("Location: ./pages/dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="./css/login.css">
    
	<title>Login</title>
</head>
<body>
	
	<div class="container">
		<form action="#" class="login active">
			<h2 class="title">Login with your account</h2>
			<div class="form-group">
				<label for="username">Username</label>
				<div class="input-group">
					<input type="text" name="username" id="username" placeholder="Email address">
					<i class='bx bx-envelope'></i>
				</div>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<div class="input-group">
					<input type="password" name="password" id="password" placeholder="Your password">
					<i class='bx bx-lock-alt' ></i>
				</div>
			</div>
			<button type="submit" class="btn-submit"><p style="color: white;">Login</p></button>
			<a href="#">Forgot password?</a>
		</form>
	</div>

  <script src="./js/jquery-3.3.1.min.js"></script>
  <script>
    $(document).ready(function() {
      $('form').submit(function(e) {
        e.preventDefault();
      
        $.post("./functions/login.php", $(this).serializeArray(), function(data) {
					console.log(data);
          let res = JSON.parse(data);

					alert(res.message);
					if (res.status === 200) {
						window.location.href = './pages/dashboard.php';
					}
        });
      })
    });
  </script>
</body>
</html>