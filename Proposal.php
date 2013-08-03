<?php
include_once('include/headers.php');
Login::loginCheck(1);
if(isset($_POST['submit'])){
    //The submit button has been pressed
    $proposal;
    if((@$_POST['newFlag'] == 1)||(intval(@$_POST['proposalID']) == 0)){
        $proposal = new Proposal(0);
        $proposal->readProposalfromForm();
        $proposal->saveToDatabase();
        header("Location:Proposal.php");
        exit;
    }else{
        //We should have a proposal object
        $proposal = new Proposal(@$_POST['proposalID']);
    }
    
}
if(isset($_GET['proposalObj'])){
    //We are going to be loading an object from the database based on proposalObj
    $pageProposal = new Proposal($_POST['proposalObj']);//Use this on the rest of the page
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

<header class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <a href="index.html" class="navbar-brand">IPRO Proposals</a>
    <nav class="pull-right">
      <a href="approvals.html" class="btn btn-primary navbar-btn">Approvals <span class="badge">10</span></a>
      <div class="btn-group">
        <button type="button" class="btn btn-primary navbar-btn dropdown-toggle" data-toggle="dropdown">
          Dasboard <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li><a href="dashboard.html">Proposals</a></li>
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
          <li><a href="#">Logout</a></li>
        </ul>
      </div>
    </nav>
  </div>
</header>

<div id="new-proposal">
  <div class="container jumbotron">
    <div class="title">
      <h3>
        New Proposal Application
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
        <div class="pull-right">
          <a href="#" class="btn btn-danger">Cancel</a>
          <input type="submit" name="submit" class="btn btn-primary" value="Submit for Approval">
          <a href="#" class="btn btn-success">Save as Draft</a>
        </div>
      </h3>
    </div>
    <div class="row">
      
        <div class="col-lg-6">
          <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" name="projectTitle" placeholder="IPRO Project Title">
          </div>
          <div class="form-group">
            <label>Problem or Issue</label>
            <textarea class="form-control" rows="6" name="problem" placeholder="What problems will the IPRO Address?"></textarea>
          </div>
          <div class="form-group">
            <label>Objective</label>
            <textarea class="form-control" rows="6" name="objectives" placeholder="What objectives will be achieved?"></textarea>
          </div>
          <div class="form-group">
            <label>Approach</label>
            <textarea class="form-control" rows="6" name="approach" placeholder="What approach will the team take?"></textarea>
          </div>
          <div class="form-group">
            <label>Class Information</label>
            <div>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-0" value="">M
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-1" value="">T
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-2" value="">W
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-3" value="">Th
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" name="day-4" value="">F
              </label>
            </div>
            <div>
              <label class="radio-inline">
                <input type="radio" name="time" value="Morning">Morning
              </label>
              <label class="radio-inline">
                <input type="radio" name="time" value="Afternoon">Afternoon
              </label>
              <label class="radio-inline">
                <input type="radio" name="time" value="Evening">Evening
              </label>
            </div>
            <div class="form-group">
              <label>Available Semesters</label>
              <?php echo Proposal::generateNextSemesterDropdown(0); ?>
              <!-- Semester dropdown prototype
              <select class="form-control">
                <option>Option1</option>
                <option>Option2</option>
                <option>Option3</option>
              </select>
              -->
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>Primary Instructor Information</label>
            <input type="text" class="form-control" name="instructorName" placeholder="Instructor Name">
          </div>
          <div class="form-group">
            <input type="email" class="form-control" name="instructorEmail" placeholder="Instructor Email">
          </div>
          <div class="form-group">
            <label>Co-Instructor Information</label>
            <input type="text" class="form-control" name="coInstructorName" placeholder="Co-Instructor Name">
          </div>
          <div class="form-group">
            <input type="email" class="form-control" name="coInstructorEmail" placeholder="Co-Instructor Email">
          </div>
          <div class="form-group">
            <label>College Dean</label>
            <?php echo Proposal::generateDeanDropdown(0); ?>
            <!--  Dean Dropdown Prototype
            <select class="form-control">
              <option>Dean1</option>
              <option>Dean2</option>
              <option>Dean3</option>
            </select>
            -->
          </div>
          <div class="form-group">
            <label>Sponsor Information</label>
            <input type="text" class="form-control" name="sponsor" placeholder="Sponsor">
          </div>
          
          <div class="form-group">
            <label>Targeted Disciplines</label>
            <div>
                <?php echo Proposal::generateDisciplinesCheckboxes(0); ?>
              <!-- Checkbox Prototype
                <label class="checkbox-inline">
                <input type="checkbox" name="" value="Discipline1"> Discipline 1
              </label>
              -->
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>