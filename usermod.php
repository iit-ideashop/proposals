<?php
include('include/headers.php');
$loginObj = new Login();
if(isset($_POST['submit'])){
	$loginObj->csrfTest();
	if(isset($_POST['change'])){
		if($_POST['password'] != $_POST['password-confirm']){
			FlashBang::addFlashBang("Red", "Failure", "Changing password failed, passwords do not match.");
			header('location:usermod.php');
			exit;
		}
		if(strlen($_POST['password']) < 8){
			FlashBang::addFlashBang("Red", "Failure", "Changing password failed, password must be at least 8 characters.");
			header('location:usermod.php');
			exit;
		}
		// Will fail if the user is not logged in
		if($loginObj->changePassword($_POST['password'])){
			//Success
			FlashBang::addFlashBang("Green", "Success", "Password change successful");
		} else {
			FlashBang::addFlashBang("Red", "Failure", "Password change failed");
		}
		header('location:dashboard.php');
		exit;

	} else {
		if(isset($_POST['email'])){
			$loginObj->resetPassword($_POST['email']);
			FlashBang::addFlashBang("Green", "Success", "If an account exists under the provided email, its password has been reset. Please check your email for the temporary password");
		}
		header('location:index.php');
		exit;
	}
}
?>
<!DOCTYPE html>
<html lang="en  ">
<head>
  <title>IPRO Proposals</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/application.js"></script>
</head>
<body>

<?php
include_once('include/nav.php');
?>

<div id="usermod">
  <div class="container jumbotron">
    <div class="col-lg-4 col-offset-4 text-center">
<?php 
$csrf = $loginObj->csrfToken();
if($_SESSION['proposal_LoggedIn'] == false){
$html = <<<EOT
        <h3>Reset Password</h3>
      <form class="form-horizontal" action="" method="POST">
        <div class="form-group">
          <input type="text" class="form-control input-large" name="email" id="email" placeholder="Email">
        </div>
        <input type="hidden" name="csrf" value="$csrf">
        <button type="submit" name="submit" class="btn btn-primary btn-large">Reset Password</button>
      </form>
EOT;
} else {
$html = <<<EOT
        <h3>Change Password</h3>
      <form class="form-horizontal" action="" method="POST">
        <div class="form-group">
          <input type="password" class="form-control input-large" name="password" id="password" placeholder="Password">
        </div>
        <div class="form-group">
          <input type="password" class="form-control input-large" name="password-confirm" id="password-confirm" placeholder="Confirm Password">
        </div>
        <input type="hidden" name="change" value="change">
        <input type="hidden" name="csrf" value="$csrf">
        <button type="submit" name="submit" class="btn btn-primary btn-large">Reset Password</button>
      </form>
EOT;
}
echo($html);
?>
    </div>
  </div>
</div>

</body>
</html>
