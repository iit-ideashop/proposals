<?php
include_once('include/headers.php');
Login::loginCheck(1);
if((isset($_POST['saveDraft']))||(isset($_POST['submitForApproval']))){
    //a button has been pressed!!
    $proposal;
    if((@$_POST['newFlag'] == 1)||(intval(@$_POST['proposalID']) == 0)){
        $proposal = new Proposal(0);
        $proposal->readProposalfromForm();
        if(isset($_POST['submitForApproval'])){
            $proposal->submitForApproval();
        }
        $proposal->saveToDatabase();
        header("Location:Proposal.php");
        exit;
    }else{
        //We are saving an existing proposal
        $proposal = new Proposal($_POST['proposalID']);
        $proposal->readProposalfromForm();
        if(isset($_POST['submitForApproval'])){
            $proposal->submitForApproval();  
        }
        $proposal->saveToDatabase();
        header("Location:Proposal.php?proposalID=".$_POST['proposalID']);
        exit;
    }
    
}
if(isset($_GET['proposalID'])){
    //We are going to be loading an object from the database based on proposalID
    $pageProposal = new Proposal($_GET['proposalID']);//Use this on the rest of the page
    //For this page we have to verify ownership of the proposal to make sure the user viewing the proposal is actually the one who owns it
    if($pageProposal->getOwnerID() != $_SESSION['proposal_userID']){
        FlashBang::addFlashBang("Red", "Unauthorized!", "You must be the owner of a proposal to edit it");
        header("Location:dashboard.php");
        exit;
    }
    //Next we verify that the proposal is in status 0, if it is not, we are not supposed to be able to edit it
    if($pageProposal->getStatus() != 0){
        FlashBang::addFlashBang("Green", "Awaiting Dean Approval", "This proposal has been sent to the dean for approval. Edits are not allowed once the proposal has been submitted.");
        header("Location:dashboard.php");
        exit;
    }
}else{
    //We don't have to do any checks for a new proposal...because it is a new proposal.... and this is a dummy object! HAHAHAHAH!
    $pageProposal = new Proposal(0);
}
?>
<!DOCTYPE html>
<html lang="en">
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
<div id="new-proposal">
  <div class="container jumbotron">

    <div class="proposal-progress">
      <ul class="breadcrumb">
        <li class="active">Proposal Development</li>
        <li>Dean Review</li>
        <li>IPRO Committee Review</li>
        <li>Scheduling and Contracting</li>
      </ul>
    </div>

    <div class="title">
      <div class="pull-right">
         <a href="dashboard.php" class="btn btn-danger">Cancel</a>
         <input type="submit" name="submitForApproval" class="btn btn-primary" value="Submit for Approval">
         <input type="submit" name="saveDraft" class="btn btn-success" value="Save as Draft">
      </div>
      <h3>
        <?php
        if(@$_GET['newFlag'] == 1){
            echo 'New';
        }else{
            echo 'Edit';
        }
        ?>
        Proposal Application
        <form action="" method="POST">
            <?php
                //here we will use hidden form fields to get data
                if(@$_GET['newFlag'] == 1){
                    //We have a new proposal being generated
                    echo '<input type="hidden" name="newFlag" value="1">';
                }
                if((isset($_GET['proposalID']))&&(intval(@$_GET['proposalID']) != 0)){
                    echo '<input type="hidden" name="proposalID" value="'.$_GET['proposalID'].'">';
                }
            ?>
      </h3>
    </div>

    <div class="row">

        <!-- Proposal Details -->
        <div class="col-lg-6">
          <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" name="projectTitle" placeholder="IPRO Project Title" value="<?php echo $pageProposal->getTitle() ?>">
          </div>
          <div class="form-group">
            <label>Problem or Issue</label>
            <textarea class="form-control" rows="6" name="problem" placeholder="What problems will the IPRO Address?"><?php echo $pageProposal->getProblem(); ?></textarea>
          </div>
          <div class="form-group">
            <label>Objective</label>
            <textarea class="form-control" rows="6" name="objectives" placeholder="What objectives will be achieved?"><?php echo $pageProposal->getObjective(); ?></textarea>
          </div>
          <div class="form-group">
            <label>Approach</label>
            <textarea class="form-control" rows="6" name="approach" placeholder="What approach will the team take?"><?php echo $pageProposal->getApproach(); ?></textarea>
          </div>
        </div>

        <!-- Instructor and Scheduling -->
        <div class="col-lg-6">
          <div class="form-group">
            <label>Primary Instructor Information</label>
            <input type="text" class="form-control" name="instructorName" placeholder="Instructor Name" value="<?php echo $pageProposal->getInstructor(); ?>">
          </div>
          <div class="form-group">
            <input type="email" class="form-control" name="instructorEmail" placeholder="Instructor Email" value="<?php echo $pageProposal->getInstructorEmail(); ?>">
          </div>
          <div class="form-group">
            <label>Co-Instructor Information</label>
            <input type="text" class="form-control" name="coInstructorName" placeholder="Co-Instructor Name" value="<?php echo $pageProposal->getCoInstructor(); ?>">
          </div>
          <div class="form-group">
            <input type="email" class="form-control" name="coInstructorEmail" placeholder="Co-Instructor Email" value="<?php echo $pageProposal->getInstructorEmail(); ?>">
          </div>
          <div class="form-group">
            <label>College Dean</label>
            <?php echo Proposal::generateDeanDropdown($pageProposal->getApprovingDean()); ?>
          </div>
          <div class="form-group">
            <label>Sponsor Information</label>
            <input type="text" class="form-control" name="sponsor" placeholder="Sponsor" value="<?php echo $pageProposal->getSponsor(); ?>">
          </div>

          <div class="form-group">
            <label>Class Timing (we will try our best to accommodate)</label>
            <div>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-0" <?php if(array_key_exists(0,$pageProposal->getDays())){ echo 'checked="checked"'; } ?>>M
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-1" <?php if(array_key_exists(1,$pageProposal->getDays())){ echo 'checked="checked"'; } ?>>T
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-2" <?php if(array_key_exists(2,$pageProposal->getDays())){ echo 'checked="checked"'; } ?>>W
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-3" <?php if(array_key_exists(3,$pageProposal->getDays())){ echo 'checked="checked"'; } ?>>Th
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-4" <?php if(array_key_exists(4,$pageProposal->getDays())){ echo 'checked="checked"'; } ?>>F
              </label>
            </div>
            <div>
              <label class="radio-inline">
                <input type="radio" name="time" value="Morning" <?php if($pageProposal->getTime() == "Morning"){ echo 'checked="checked"'; } ?>>Morning
              </label>
              <label class="radio-inline">
                <input type="radio" name="time" value="Afternoon" <?php if($pageProposal->getTime() == "Afternoon"){ echo 'checked="checked"'; } ?>>Afternoon
              </label>
              <label class="radio-inline">
                <input type="radio" name="time" value="Evening" <?php if($pageProposal->getTime() == "Evening"){ echo 'checked="checked"'; } ?>>Evening
              </label>
            </div>
            <div class="form-group">
                <label>Available Semesters</label>
                <?php echo Proposal::generateNextSemesterDropdown($pageProposal->getSemester()); ?>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>