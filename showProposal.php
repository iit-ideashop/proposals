<?php
include_once('include/headers.php');
//Show Proposal page, for this page we need a proposalID to get the proposal from the database. If we don't have it, then we redirect to dash.
if(!isset($_GET['proposalID'])){
  header("Location:dashboard.php");  
  exit;
}
//Next let's secure this page.
Login::loginCheck(0);

$pageProposal;
//Next on the agenda, let's grab the proposal from the database
if(intval(@$_GET['proposalID']) != 0){
    $pageProposal = new Proposal($_GET['proposalID']);
}
/*
//We have to make sure the user is allowed to see this proposal. So if it is Level 0 or 1 the user has to be owner
if((($pageProposal->getStatus() == 0)||($pageProposal->getStatus() == 1))&&($SESSION['proposal_userID'] != $pageProposal->getOwnerID())){
    FlashBang::addFlashBang('Red', 'Access Denied', 'Based on the proposals current status');
    header("Location:dashboard.php");  
    exit;
}

*/

//Login has been checked and proposal has been loaded, next lets see if there are any actions to be done
if(@$_GET['action'] == 'approvedeny'){
    //We are either approving or denying a proposal application
    if(@$_POST['submit'] == "approve"){
        $pageProposal->saveComments($_POST['comment']);
        $pageProposal->approveProposal();
        $pageProposal->saveToDatabase();
    }elseif(@$_POST['submit'] == "deny"){
        $pageProposal->saveComments($_POST['comment']);
        $pageProposal->denyProposal();
        $pageProposal->saveToDatabase();
    }elseif(@$_GET['mode'] == 'approve'){
        $pageProposal->approveProposal();
        $pageProposal->saveToDatabase();
    }elseif(@$_GET['mode'] == 'deny'){
        $pageProposal->denyProposal();
        $pageProposal->saveToDatabase();
    }
    
    
    header("Location:?proposalID=".$pageProposal->getID());
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

<div id="show-proposal">
  <div class="container jumbotron">
       <?php echo Proposal::convertStatusToProgressBox($pageProposal->getStatus()) ?>
    <div class="pull-right">
      <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Actions <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="Proposal.php?proposalID=<?php echo $pageProposal->getID(); ?>">Edit</a></li>
          <li><a href="#">Submit for Approval</a></li>
        </ul>
      </div>
    </div>
    <div class="title">
      <h3>
        <?php
            echo $pageProposal->getTitle();
        ?>
          <span class="label <?php echo Proposal::convertStatusToClassColor($pageProposal->getStatus()) ?>"><?php echo Proposal::convertStatusToText($pageProposal->getStatus()) ?></span>
      </h3>
    </div>
    <div class="row">
      <div class="col-lg-8 ipro-info">
        <h5>Problem or Issue</h5>
        <p>
          <?php
          echo nl2br($pageProposal->getProblem());
          ?>
        </p>
        <h5>Objective</h5>
        <p>
          <?php
          echo nl2br($pageProposal->getObjective());
          ?>
        </p>
        <h5>Approach</h5>
        <p>
          <?php
          echo nl2br($pageProposal->getApproach());
          ?>
        </p>
      </div>
      <div class="col-lg-4 ipro-details">
        <h5>Sponsor Information</h5>
        <p><?php echo $pageProposal->getSponsor(); ?></p>
        <h5>Primary Instructor</h5>
        <p><?php echo $pageProposal->getInstructor(); ?> - <?php echo $pageProposal->getInstructorEmail(); ?></p>
        <h5>Co-Instructor</h5>
        <p><?php echo $pageProposal->getCoInstructor(); ?> - <?php echo $pageProposal->getCoInstructorEmail(); ?></p>
        <h5>Dean</h5>
        <p><?php echo $pageProposal->getDeanSchool(); ?></p>
        <h5>Scheduling</h5>
        <p>Time: <?php echo $pageProposal->getTime(); ?><br>
            Days: <?php echo $pageProposal->displayDays(); ?><br>
            Semester: <?php echo $pageProposal->getSemester(); ?></p>
        
      </div>
       <?php
       

        //This is where we will build our approvals module
       //Let's show some comments
       $commentsArray = $pageProposal->getComments();
       if(count($commentsArray) != 0){
           echo 'Comments';
           echo '<div class="media">';
           for($i =0;$i < count($commentsArray);$i++){
               echo '<div class="media-body"><h4 class="media-heading">'.$commentsArray[$i][2].'</h4>'.$commentsArray[$i][1].'</div>';
           }
           echo '</div>';
       }
       
       
       
       
       
       if((($pageProposal->getStatus() == 2)&&($_SESSION['proposal_UserLevel'] == 2))||(($pageProposal->getStatus() == 4)&&($_SESSION['proposal_UserLevel'] == 3))){
           //This user is a dean/committee, we have to show a form to submit a comment and approve or deny the proposal
           echo 'Approve or Deny this request';
           echo '<form action="?proposalID='.$pageProposal->getID().'&action=approvedeny" method="POST">';
           echo '<textarea name="comment" cols="50" rows="4" placeholder="Enter your comments here" class="form-control"></textarea><br>
                 <input type="submit" name="submit" value="approve" class="btn btn-primary"> <input type="submit" name="submit" value="deny" class="btn btn-danger">';
           echo '</form>';
       }
       
       
       ?> 
    </div>
  </div>
</div>

</body>
</html>