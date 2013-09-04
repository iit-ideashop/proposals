<?php 
include_once('include/headers.php');
Login::loginCheck(0);
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

<div id="approvals">
  <div class="container jumbotron">
    <div class="title">
      <h3>
        Active Proposals
      </h3>
    </div>
    <ul id="proposal-list">
      <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-warning">Awaiting Revision</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Unarchive</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-warning">Awaiting Approval</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Unarchive</a></li>
            </ul>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>

</body>
</html>