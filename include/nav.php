<?php
    if(!$header_loaded){
        exit;
    }
?>
<header class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <a href="index.php" class="navbar-brand">IPRO Proposals</a>
    <?php
    if(@$_SESSION['proposal_LoggedIn'] == 0){
        echo '<nav class="pull-right">
              <a href="http://ipro.iit.edu/" target="_blank" class="btn btn-danger navbar-btn">Visit IPRO Homepage</a>
              </nav>';
    }elseif(@$_SESSION['proposal_LoggedIn'] == 1){
        echo '<nav class="pull-right">
                <a href="approvals.html" class="btn btn-primary navbar-btn">Approvals <span class="badge">10</span></a>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary navbar-btn dropdown-toggle" data-toggle="dropdown">
                    Dasboard <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="dashboard.php">Proposals</a></li>
                        <li><a href="archive.html">Archive</a></li>
                    </ul>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary navbar-btn dropdown-toggle" data-toggle="dropdown">
                    User <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#">Settings</a></li>
                        <li class="divider"></li>
                    <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
             </nav>';
    }
    ?>
  </div>
</header>

<?php
//here we will process the flashbangs
include_once('classes/FlashBang.php');
echo FlashBang::getFlashBang();
?>