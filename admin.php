<?php 
include_once('include/headers.php');
Login::loginCheck(9);
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
        Administration Panel - All Proposals
        
      </h3>
    </div>
      <?php
      //Let's get the "My Proposal" array!
        $proposalArray = Proposal::getAllProposals();
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
                    <a class="btn btn-default" style="color:white;" href="showProposal.php?proposalID='.$proposalArray[$i]->getID().'" >View</a>
                    </div>
                </li>';
       }
       if(count($proposalArray) == 0){
           echo 'No Proposals to display';
       }
       ?>
</body>
</html>



