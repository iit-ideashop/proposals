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
             ';
        //Here we will do some calculation of approvals and if the link should be shown
        //Approvals will only be shown to committee and deans so
        if(($_SESSION['proposal_UserLevel'] == 2)||($_SESSION['proposal_UserLevel'] == 3)){
            echo '<a href="approvals.php" class="btn btn-primary navbar-btn">Approvals <span class="badge">'.Proposal::calculateMyApprovals().'</span></a>
                ';
        }
        echo '
            <a href="dashboard.php" class="btn btn-primary navbar-btn">Proposals</a>
            <!-- since archives are not yet implemented we are disabling the drop down button 
            <div class="btn-group">
                    <button type="button" class="btn btn-primary navbar-btn dropdown-toggle" data-toggle="dropdown">
                    Dasboard <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="dashboard.php">Proposals</a></li>
                        <li><a href="archive.html">Archive</a></li>
                    </ul>
                </div>
                -->
                <!-- Settings not yet implemented for users, changing this to just 
                <div class="btn-group">
                    <button type="button" class="btn btn-primary navbar-btn dropdown-toggle" data-toggle="dropdown">
                    User <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#">Settings</a></li>
                        <li class="divider"></li>
                    <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div> -->
                <a href="logout.php" class="btn btn-primary navbar-btn">Logout</a>
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