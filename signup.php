<?php
include('include/headers.php');
if(isset($_POST['submit'])){
    if($_POST['password'] == $_POST['password-confirm']){
        //Passwords match, let's make an account
        
        $loginObj = new Login();
        if($loginObj->createNewUser($_POST['FName'], $_POST['LName'], $_POST['username'], $_POST['password'], $_POST['email'])){
            //success
            echo 'we got this far';
            FlashBang::addFlashBang("Green", "Success", "Your account has been created successfully");
            header('location:index.php');
            exit;
        }else{
            
            //failure
            //header("Location: signup.php");
           // exit;
        }
    }else{
        //passwords dont match
        FlashBang::addFlashBang("Red", "Form Error", "Your passwords do not match");
       // header("Location:signup.php");
       // exit;
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

<div id="signup">
  <div class="container jumbotron">
    <div class="col-lg-4 col-offset-4 text-center">
        <h3>Sign up for a new account</h3>
      <form class="form-horizontal" action="" method="POST">
        <div class="form-group">
          <input type="text" class="form-control input-large" name="FName" id="FName" placeholder="First Name" value="<?php echo @$_POST['FName'] ?>">
        </div>
        <div class="form-group">
          <input type="text" class="form-control input-large" name="LName" id="LName" placeholder="Last Name" value="<?php echo @$_POST['LName'] ?>">
        </div>
        <div class="form-group">
          <input type="text" class="form-control input-large" name="email" id="email" placeholder="Email" value="<?php echo @$_POST['email'] ?>">
        </div>
          <div class="form-group">
          <input type="text" class="form-control input-large" name="username" id="username" placeholder="Username" value="<?php echo @$_POST['username'] ?>">
        </div>
        <div class="form-group">
          <input type="password" class="form-control input-large" name="password" id="password" placeholder="Password" value="<?php echo @$_POST['password'] ?>">
        </div>
        <div class="form-group">
          <input type="password" class="form-control input-large" name="password-confirm" id="password-confirm" placeholder="Confirm Password" value="<?php echo @$_POST['password-confirm'] ?>">
        </div>
        <button type="submit" name="submit" class="btn btn-primary btn-large">Sign Up</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>