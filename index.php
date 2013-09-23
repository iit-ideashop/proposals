<?php
include('include/headers.php');
//process the login here using the login object found under classes/login.php
if(isset($_POST['submit'])){
    //process the login
    $loginObj = new Login();
    $loginObj->authenticate($_POST['username'], $_POST['password']);
}
//Let's check to see if the user is already authenticated
if(@$_SESSION['proposal_LoggedIn'] == 1){
    header("Location:dashboard.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en  ">
<head>
  <title>IPRO Proposals</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/application.js"></script>
</head>
<body>

<?php
include_once('include/nav.php');
?>

<div id="main-focus-area">
  <div class="container jumbotron">
    <div class="row text-center">
      <div class="col-lg-8">
        <h1>Proposals, not paperwork</h1>
        <p>
          Welcome to IPRO's new proposal system meant to ease the process and enhance the experience.
          Login to get started!
        </p>
      </div>
      <div class="col-lg-4">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">  
            <h4>
            New user?
            <a href="signup.php">Sign up now!</a>
          </h4>
          <div class="form-group">
            <input type="text" class="form-control input-large" name="username" id="username" placeholder="Username">
          </div>
          <div class="form-group">
            <input type="password" class="form-control input-large" id="password" name="password" placeholder="Password">
          </div>
          <button type="submit" name="submit" class="btn btn-primary btn-large">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>