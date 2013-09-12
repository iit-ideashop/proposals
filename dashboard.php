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
<div id="dashboard">
  <div class="container jumbotron">
    <div class="title">
      <h3>
        My Proposals
        <div class="pull-right">
          <!-- Archives currently not implemented <a href="archive.html" class="btn btn-default">View Archive</a> -->
          <a href="Proposal.php?newFlag=1" class="btn btn-success">Create a new proposal</a>
        </div>
      </h3>
    </div>
      <?php
      //Let's get the "My Proposal" array!
        $proposalArray = Proposal::getMyProposals();
      ?>
    <ul id="proposal-list">
       
       <?php
       for($i=0;$i<count($proposalArray);$i++){
           echo '<li class="proposal-item row">
                    <div class="col-lg-7">
                        <a href="showProposal.php?proposalID='.$proposalArray[$i]->getID().'"><span>'.$proposalArray[$i]->getTitle().'</span></a>
                    </div>
                    <div class="col-lg-5 text-right">
                        <span class="label '.Proposal::convertStatusToClassColor($proposalArray[$i]->getStatus()).'">'.Proposal::convertStatusToText($proposalArray[$i]->getStatus()).'</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                              Actions <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="Proposal.php?proposalID='.$proposalArray[$i]->getID().'">Edit</a></li>
                                <li><a href="showProposal.php?proposalID='.$proposalArray[$i]->getID().'">View</a></li>
                                <li><a href="#">Submit</a></li>
                                <li><a href="#">Archive</a></li>
                            </ul>
                        </div>
                    </div>
                </li>';
       }
       if(count($proposalArray) == 0){
           echo 'No Proposals to display';
       }
       ?>
      <!-- Proposal item prototypes
        <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-1">Awaiting Submission</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Submit</a></li>
              <li><a href="#">Archive</a></li>
            </ul>
          </div>
        </div>
      </li>
      
      
      <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-2">Submitted to Dean</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Submit</a></li>
              <li><a href="#">Archive</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-3">Denied - Dean Revised</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Submit</a></li>
              <li><a href="#">Archive</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-4">Approved by Committee</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Submit</a></li>
              <li><a href="#">Archive</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-5">Denied - Committee Revised</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Submit</a></li>
              <li><a href="#">Archive</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-6">Awaiting Final Approval</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Submit</a></li>
              <li><a href="#">Archive</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-7">IPRO Approved!</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Submit</a></li>
              <li><a href="#">Archive</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="proposal-item row">
        <div class="col-lg-7">
          <a href="#"><span>Project Title</span></a>
        </div>
        <div class="col-lg-3 text-center">
          <span class="label label-0">DENIED</span>
        </div>
        <div class="col-lg-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Edit</a></li>
              <li><a href="#">View</a></li>
              <li><a href="#">Submit</a></li>
              <li><a href="#">Archive</a></li>
            </ul>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>
-->
</body>
</html>



