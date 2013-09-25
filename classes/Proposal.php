<?php
//Proposal Class
//This class is used to load and store proposal objects to and from the database 
class Proposal {
    private $ID;
    private $Instructor; 
    private $InstructorEmail;
    private $CoInstructor;
    private $CoInstructorEmail;
    private $Sponsor;
    private $ApprovingDean; // This will be an integer which is tied to an approving dean from a different table
    private $Title;
    private $Problem;
    private $Objective;
    private $Approach;
    private $Semester; //This will be text in form of SEM+YEAR EXAMPLE: FALL2013
    private $Days; //array of days in form.  0=MON 1=TUES 2=WED 3=THURS 4=FRI so array{1,3} would be TUESDAY + THURSDAY
    private $Time; //3 options, Morning, Afternoon, Night
    private $CourseNumber; // This will be 0 by default and when approved will be assigned an IPRO number IPRO 397-###
    private $OwnerID;
    private $status;
    private $dateLastModified;  //format date("Y-m-d H:i:s")
    private $dbconn;
    
    
    function __construct($proposalID){
        $this->dbconn = new Database();
        $this->dbconn = $this->dbconn->getConnection();
        //next we need to load the proposal from the database if the proposal ID is not 0
        if(intval($proposalID != 0)){
            //Load from database
            $loadProposalSql = "SELECT * FROM proposals WHERE ID='".intval($proposalID)."' LIMIT 1";
            $loadProposalResult = $this->dbconn->query($loadProposalSql);
            //WARNING: THIS WILL LOAD ANY PROPOSAL AND WILL NOT CHECK THE USER'S PERMISSIONS
            //FOR VIEW/EDIT, HOWEVER WHEN MAKING CHANGES THE CLASS WILL CHECK PERMISSIONS
            //FOR VIEWING PURPOSES RUN THE CHECKPERMISSION FUNCTION FIRST
            if($loadProposalResult->num_rows == 1){
                $rows = $loadProposalResult->fetch_assoc();
                $this->ID = $rows['ID'];
                $this->Instructor = $rows['Instructor'];
                $this->InstructorEmail = $rows['InstructorEmail'];
                $this->CoInstructor = $rows['CoInstructor'];
                $this->CoInstructorEmail = $rows['CoInstructorEmail'];
                $this->Sponsor = $rows['Sponsor'];
                $this->ApprovingDean = $rows['ApprovingDean'];
                $this->Title = $rows['Title'];
                $this->Problem = $rows['Problem'];
                $this->Objective = $rows['Objective'];
                $this->Approach = $rows['Approach'];
                $this->Semester = $rows['Semester'];
                $this->Days = unserialize($rows['Days']);
                $this->Time = $rows['Time'];
                $this->CourseNumber = $rows['CourseNumber'];
                $this->OwnerID = $rows['OwnerID'];
                $this->dateLastModified = $rows['dateLastModified'];
                $this->status = $rows['status'];
            }
        }else{
            $this->ID = 0;
            $this->Days = array(0);
            $this->Semester = 0;
            $this->ApprovingDean = 0;
            $this->dateLastModified = date("Y-m-d H:i:s");
            $this->OwnerID = $_SESSION['proposal_userID'];
            $this->CourseNumber = 0;
            $this->status = 0;
        }
        
    }
    static function getMyProposals(){
        //This function returns an array of proposal objects which can be used in a variety of ways....
        $dbconnlocal = new Database();
        $dbconnlocal = $dbconnlocal->getConnection();
        $sql ="SELECT ID FROM proposals WHERE OwnerID='".intval($_SESSION['proposal_userID'])."'";
        $result = $dbconnlocal->query($sql);
        $proposalArray = array();
        while($row = $result->fetch_assoc()){
            //into a temporary object and then into the array
            $tempPropObj = new Proposal($row['ID']);
            array_push($proposalArray, $tempPropObj);
            $tempPropObj = null;
        }
        return $proposalArray;
    }
    
    
    function getRecentID(){
        //This function will get the ID of the recently inserted record in the database
        $sql = "SELECT ID from proposals WHERE OwnerID='".$_SESSION['proposal_userID']."' ORDER BY ID DESC LIMIT 1";
        $query = $this->dbconn->query($sql);
        $data = $query->fetch_assoc();
        return $data['ID'];
    }
    
    function readDayfromForm(){
        //This is going to be really simple, we have to return the day array in the form OF 1-5 being MON-FRI
        $dayArray = array();
        for($i=1;$i<=5;$i++){
            if(isset($_POST['day-'.$i])){
                if($_POST['day-'.$i] == 'on'){
                    $dayArray[$i] = 'on';
                }
            }
        }
        return $dayArray;
    }
    
    function readProposalfromForm(){
        //Here we are going to read posted data from the form and set all of the local variables
        $this->Instructor = $_POST['instructorName'];
        $this->InstructorEmail = $_POST['instructorEmail'];
        $this->CoInstructor = $_POST['coInstructorName'];
        $this->CoInstructorEmail = $_POST['coInstructorEmail'];
        $this->Sponsor = $_POST['sponsor'];
     
        
        $this->ApprovingDean = $_POST['approvingDean'];
        $this->Title = $_POST['projectTitle'];
        $this->Problem = $_POST['problem'];
        $this->Objective = $_POST['objectives'];
        $this->Approach = $_POST['approach'];

        //We use the readDayfromForm function to get an array 0-4 of days MON-FRI
        $this->Days = $this->readDayfromForm();
        
        $this->Time = $_POST['time'];//values are Morning, Afternoon, Evening
        $this->Semester = $_POST['semester'];
   }
   
   function saveToDatabase(){
       if($this->ID === 0){
           //New Proposal
           //Insert the new proposal into the database
           $sql = "INSERT INTO proposals(Instructor,InstructorEmail,CoInstructor,CoInstructorEmail,Sponsor,ApprovingDean,Title,Problem,Objective,Approach,Semester,Days,Time,CourseNumber,OwnerID,status,dateLastModified) 
                    VALUES('".$this->dbconn->real_escape_string($this->Instructor)."',
                            '".$this->dbconn->real_escape_string($this->InstructorEmail)."',
                            '".$this->dbconn->real_escape_string($this->CoInstructor)."',
                            '".$this->dbconn->real_escape_string($this->CoInstructorEmail)."',
                            '".$this->dbconn->real_escape_string($this->Sponsor)."',
                            '".$this->dbconn->real_escape_string($this->ApprovingDean)."',
                            '".$this->dbconn->real_escape_string($this->Title)."',
                            '".$this->dbconn->real_escape_string($this->Problem)."',
                            '".$this->dbconn->real_escape_string($this->Objective)."',
                            '".$this->dbconn->real_escape_string($this->Approach)."',
                            '".$this->dbconn->real_escape_string($this->Semester)."',
                            '".$this->dbconn->real_escape_string(serialize($this->Days))."',
                            '".$this->dbconn->real_escape_string($this->Time)."',
                            '".$this->dbconn->real_escape_string($this->CourseNumber)."',
                            '".$this->dbconn->real_escape_string($this->OwnerID)."',
                            '".$this->dbconn->real_escape_string($this->status)."',
                                '".date("Y-m-d H:i:s")."')";
           $query = $this->dbconn->query($sql);
           $this->ID = $this->getRecentID();
       }else{
           //we have to update the proposal in the database
           $sql = "UPDATE proposals SET Instructor='".$this->dbconn->real_escape_string($this->Instructor)."',
               InstructorEmail='".$this->dbconn->real_escape_string($this->InstructorEmail)."',
               CoInstructor='".$this->dbconn->real_escape_string($this->CoInstructor)."',
               CoInstructorEmail='".$this->dbconn->real_escape_string($this->CoInstructorEmail)."',
               Sponsor='".$this->dbconn->real_escape_string($this->Sponsor)."',
               ApprovingDean='".$this->dbconn->real_escape_string($this->ApprovingDean)."',
               Title='".$this->dbconn->real_escape_string($this->Title)."',
               Problem='".$this->dbconn->real_escape_string($this->Problem)."',
               Objective='".$this->dbconn->real_escape_string($this->Objective)."',
               Approach='".$this->dbconn->real_escape_string($this->Approach)."',
               Semester='".$this->dbconn->real_escape_string($this->Semester)."',
               Days='".$this->dbconn->real_escape_string(serialize($this->Days))."',
               Time='".$this->dbconn->real_escape_string($this->Time)."',
               CourseNumber='".$this->dbconn->real_escape_string($this->CourseNumber)."',
               OwnerID='".$this->dbconn->real_escape_string($this->OwnerID)."',
               status='".$this->dbconn->real_escape_string($this->status)."',
                   dateLastModified='".date("Y-m-d H:i:s")."' WHERE ID='".$this->dbconn->real_escape_string($this->ID)."' LIMIT 1";
           $query = $this->dbconn->query($sql);
       }
   }
   
   
   static function generateDeanDropdown($selectedID){
       //This function generates a dropdown list of the deans + colleges and the value field is a integer for ID
       $sql = "SELECT * FROM deans";
       $dbconnlocal = new Database();
       $dbconnlocal = $dbconnlocal->getConnection();
       $result = $dbconnlocal->query($sql);
       $output = '<select name="approvingDean" class="form-control">';
       while($rows = $result->fetch_assoc()){
           $selected = '';
           if(($rows['id'] == $selectedID)&&($selectedID != 0)){
               $selected='selected="selected"';
           }
           if($rows['deanName'] != ''){
               $deanName = $rows['deanName'].' - ';
           }
           $output .='<option '.$selected.' value="'.$rows['id'].'">'.$deanName.$rows['school'].'</option>';
       }
       $output .= '</select>';
       return $output;
   }
   
   static function generateNextSemesterDropdown($selectedValue){
       //Get the current year and month
       $year = date('Y');
       $month = date('n');
       $semesterDropdownValues = array();
       if(($month >= 1)&&($month <= 4)){
           //Summer same year is first
           $semesterDropdownValues = array('SUMMER'.$year,'FALL'.$year,'SPRING'.($year+1),'SUMMER'.($year+1),'FALL'.($year+1),'SPRING'.($year+2));
       }elseif(($month >=5)&&($month <= 6)){
           //Fall same year is first
           $semesterDropdownValues = array('FALL'.$year,'SPRING'.($year+1),'SUMMER'.($year+1),'FALL'.($year+1),'SPRING'.($year+2),'SUMMER'.($year+2));
       }elseif(($month >=7)&&($month <=12)){
           //Spring next year is first
           $semesterDropdownValues = array('SPRING'.($year+1),'SUMMER'.($year+1),'FALL'.($year+1),'SPRING'.($year+2),'SUMMER'.($year+2),'FALL'.($year+2));
       }
       //Next we generate the dropdown
       $output = '<select class="form-control" name="semester">';
       for($i=0;$i<count($semesterDropdownValues);$i++){
           $selected = '';
           if($semesterDropdownValues[$i] === $selectedValue){
               $selected = 'selected="selected"';
           }
           $output .='<option '.$selected.' value="'.$semesterDropdownValues[$i].'">'.$semesterDropdownValues[$i].'</option>';
           $selected = '';
       }
       $output .='</select>';
       return $output;
   }
   
   //getters and setters
   function getID(){
       return $this->ID;
   }
   
   function getInstructor(){
       return $this->Instructor;
   }
   
   function setInstructor($newInstructor){
       $this->Instructor = $newInstructor;
   }
   
   function getInstructorEmail(){
       return $this->InstructorEmail;
   }
   
   function setInstructorEmail($newEmail){
       $this->InstructorEmail = $newEmail;
   }
    
   function getCoInstructor(){
       return $this->CoInstructor;
   }
   
   function setCoInstructor($newCoInstructor){
       $this->CoInstructor = $newCoInstructor;
   }
    
   function getCoInstructorEmail(){
       return $this->CoInstructorEmail;
   }
   
   function setCoInstructorEmail($newEmail){
       $this->CoInstructorEmail = $newEmail;
   }
    
   function getSponsor(){
       return $this->Sponsor;
   }
    
   function setSponsor($newSponsor){
       $this->Sponsor = $newSponsor;
   }
    
   function getApprovingDean(){
       return $this->ApprovingDean;
   }
   
   function setApprovingDean($newDean){
       $this->ApprovingDean = $newDean;
   }
   
   
   function getTitle(){
       return $this->Title;
   }
   
   function setTitle($newTitle){
       $this->Title;
   }
   
   function getProblem(){
       return $this->Problem;
   }
   
   function setProblem($newProblem){
       $this->Problem = $newProblem;
   }
   
   function getObjective(){
       return $this->Objective;
   }
   
   function setObjective($newObjective){
       $this->Objective = $newObjective;
   }
   
   function getApproach(){
       return $this->Approach;
   }
   
   function setApproach($newApproach){
       $this->Approach = $newApproach;
   }
   
   function getSemester(){
       return $this->Semester;
   }
   
   function setSemester($newSemester){
       $this->Semester = $newSemester;
   }
   
   function getDays(){
       return $this->Days;
   }
   
   function setDays($newDays){
       $this->Days = $newDays;
   }
   
   function getTime(){
       return $this->Time;
   }
   
   function setTime($newTime){
       $this->Time = $newTime;
   }
   
   function getCourseNumber(){
       return $this->CourseNumber;
   }
   
   function setCourseNumber($newCourseNumber){
       $this->CourseNumber = $newCourseNumber;
   }
   
   function getOwnerID(){
       return $this->OwnerID;
   }
   
   function getStatus(){
       return $this->status;
   }
   
   function setStatus($newStatus){
       $this->status = $newStatus;
   }
   
   function getDateLastModified(){
       return $this->dateLastModified;
   }
   
   static function convertStatusToText($statusID){
       $statusText;
       switch ($statusID){
           case 0:
               $statusText = 'Draft';
               break;
           case 1:
               $statusText = 'Denied by Dean';
               break;
           case 2:
               $statusText = 'Sent to Dean';
               break;
           case 3:
               $statusText = 'Denied by Committee';
               break;
           case 4:
               $statusText = 'Sent to Committee';
               break;
           case 5:
               $statusText = 'Scheduling/Contracting';
               break;
           case 6:
               $statusText = 'Approved!';
               break;
           default:
               $statusText = 'Error';
               break;
       }
       return $statusText;
   }
   
   static function convertStatusToClassColor($statusID){
       $statusClass;
       switch ($statusID){
           case 0:
               $statusClass = 'label-1';
               break;
           case 1:
               $statusClass = 'label-3';
               break;
           case 2:
               $statusClass = 'label-2';
               break;
           case 3:
               $statusClass = 'label-3';
               break;
           case 4:
               $statusClass = 'label-2';
               break;
           case 5:
               $statusClass = 'label-6';
               break;
           case 6:
               $statusClass = 'label-7';
               break;
           default:
               $statusClass = 'label-0';
               break;
       }
       return $statusClass;
   }
   static function convertStatusToProgressBox($statusID){
       //current status array
       $currentStatus = array();
       if($statusID == 0){
           $currentStatus[1] = 'current-status';
       }elseif($statusID == 1){
           $currentStatus[1] = 'current-status';           
       }elseif($statusID == 2){
           $currentStatus[2] = 'current-status';           
       }elseif($statusID == 3){
           $currentStatus[2] = 'current-status';           
       }elseif($statusID == 4){
           $currentStatus[3] = 'current-status';           
       }elseif($statusID == 5){
           $currentStatus[4] = 'current-status';           
       }elseif($statusID == 6){
           $currentStatus[5] = 'current-status';           
       }
       $output = '
      <div id="proposal-progress">

      <div class="row text-center">
        <div class="col-lg-2 col-offset-1 '.@$currentStatus[1].'">
          Proposal Development
        </div>
        <div class="col-lg-2 '.@$currentStatus[2].'">
          Dean Review
        </div>
        <div class="col-lg-2 '.@$currentStatus[3].'">
          IPRO Committee Review
        </div>
        <div class="col-lg-2 '.@$currentStatus[4].'">
          Scheduling/Contracting
        </div>
        <div class="col-lg-2 '.@$currentStatus[5].'">
          IPRO Approved!
        </div>
      </div>';
       switch ($statusID){
           case 0:
               $output .='
                   <div class="progress">
                        <div class="progress-bar progress-1"></div>
                    </div>';
               break;
           case 1:
               $output .='
                   <div class="progress">
                        <div class="progress-bar progress-1"></div>
                    </div>';
               
               break;
           case 2:
               $output .='
                   <div class="progress">
                        <div class="progress-bar progress-2"></div>
                    </div>';

               break;
           case 3:
               $output .='
                   <div class="progress">
                        <div class="progress-bar progress-2"></div>
                    </div>';
               
               break;
           case 4:
               $output .='
                   <div class="progress">
                        <div class="progress-bar progress-3"></div>
                    </div>';

               break;
           case 5:
               $output .='
                   <div class="progress">
                        <div class="progress-bar progress-4"></div>
                    </div>';
               
               break;
           case 6:
               $output .='
                   <div class="progress">
                        <div class="progress-bar progress-full"></div>
                    </div>';
               
               break;
           default:
               $output .='
                   <div class="progress">
                        <div class="progress-bar progress-1"></div>
                    </div>';

               break;
       }
       $output .= '</div>';
       return $output;
   }
   
   
   function submitForApproval(){
       //If this proposal is at status 0 or 1 we are submitting directly to the dean
       if(($this->status == 0)||($this->status == 1)){
           $this->status = 2; // Submit to the dean
           $sendmail = new Email();
           $deanArray = Proposal::getApprovingDeanEmailArray($this->ApprovingDean);
           foreach ($deanArray as $value) {
               $sendmail->sendMessage($value, 'You have a Proposal waiting to be approved', 'Hello '.$this->userIDtoFullName($this->ApprovingDean).', You have a proposal waiting to be approved in your queue. Please login to the IPRO Proposal system to approve this IPRO proposal.');           
           }
       }elseif($this->status == 3){ // proposal was denied by committee, we are going to submit directly to them
           $this->status = 4;
           $sendmail = new Email();
           $committeeIDs = $this->getCommitteeIDs();
           for($i =0;$i < count($committeeIDs); $i++){
                $sendmail->sendMessage($this->userIDtoEmail($committeeIDs[$i]), 'You have a Proposal waiting to be approved', 'Hello '.$this->userIDtoFullName($committeeIDs[$i]).', You have a proposal waiting to be approved in your queue. Please login to the IPRO Proposal system to approve this IPRO proposal.');

           }
       }
       //We are going to set this proposal's status to "sent to dean"
       
   }
   //TODO: IMPLEMENT THIS FUNCTION
   static function getDeanProposalInvolvement(){
       return false;
       //We have to make sure that the userLevel is 2 for dean or 9 for admin
       $dbconnlocal = new Database();
       $dbconnlocal = $dbconnlocal->getConnection();
       $sql = "SELECT id FROM proposals WHERE ApprovingDean='' AND status > '0'";
   }
   
   
   
   static function getApprovingDeanEmailArray($approvingDeanID){
       if(intval($approvingDeanID) == 0){
           return false;
       }
       //We are going to pull the array found in the database from approvingDean
       $dbconnlocal = new Database();
       $dbconnlocal = $dbconnlocal->getConnection();
       $sql = "SELECT deanEmail FROM deans WHERE id='".intval($approvingDeanID)."'";
       $query = $dbconnlocal->query($sql);
       $result = $query->fetch_assoc();
       return unserialize($result['deanEmail']);
   }
   
   static function getCommitteeIDs(){
       $dbconnlocal = new Database();
       $dbconnlocal = $dbconnlocal->getConnection();
       $sql = "SELECT id FROM users WHERE Level='3'";
       $query = $dbconnlocal->query($sql);
       $committeeIDs = array();
       while($rows = $query->fetch_assoc()){
           array_push($committeeIDs, $rows['id']);
       }
       return $committeeIDs;
   }
   
   function getDeanName(){
       $deanID = $this->ApprovingDean;
       if(intval($deanID) != 0){
           $sql = "SELECT deanName FROM deans WHERE id='".intval($deanID)."'";
           $result = $this->dbconn->query($sql);
           $row = $result->fetch_assoc();
           return $row['deanName'];
       }else{
           return 'Error';
       }
   }
   
   function getDeanEmail(){
       $deanID = $this->ApprovingDean;
       if(intval($deanID) != 0){
           $sql = "SELECT deanEmail FROM deans WHERE id='".intval($deanID)."'";
           $result = $this->dbconn->query($sql);
           $row = $result->fetch_assoc();
           return $row['deanEmail'];
       }else{
           return 'Error';
       }
   }

   
   function displayDays(){
       $dayArray = array('M','T','W','Th','F');
       $output ='';
       for($i=0;$i<5;$i++){
           if(@$this->Days[$i+1] == 'on'){
               $output .= $dayArray[$i].' ';
           }
       }
       return $output;
   }
  
   static function calculateMyApprovals(){
       //function returns an integer relating to how many approvals the logged in user has
       if(($_SESSION['proposal_LoggedIn'])&&($_SESSION['proposal_UserLevel'] == 2)){
           //Must be logged in & dean
           $sql = "SELECT ID,ApprovingDean FROM proposals WHERE status='2'";
           $dbconnlocal = new Database();
           $dbconnlocal = $dbconnlocal->getConnection();
           $result = $dbconnlocal->query($sql);
           //lets find out our DEAN ID
           $deanIDSql = "SELECT id FROM deans WHERE userID='".intval($_SESSION['proposal_userID'])."'";
           $deanQuery = $dbconnlocal->query($deanIDSql);
           $deanRows = $deanQuery->fetch_assoc();
           $deanID = $deanRows['id'];
           //now that we know our dean id and we know the proposals that are awaiting dean approval, we count
           $approvalCount = 0;
           while($approvals = $result->fetch_assoc()){
               if($approvals['ApprovingDean'] == $deanID){
                   $approvalCount = $approvalCount +1;
               }
           }
           return $approvalCount;
       }elseif(($_SESSION['proposal_LoggedIn'])&&($_SESSION['proposal_UserLevel'] == 3)){//For a committee memeber
           //Must be logged in & committee member
           $sql = "SELECT ID FROM proposals WHERE status='4'";
           $dbconnlocal = new Database();
           $dbconnlocal = $dbconnlocal->getConnection();
           $result = $dbconnlocal->query($sql);
           $approvalCount = 0;
           while($approvals = $result->fetch_assoc()){
                $approvalCount = $approvalCount +1;
           }
           return $approvalCount;
       }
   }
   
   static function getMyApprovals(){
       if(($_SESSION['proposal_LoggedIn'])&&($_SESSION['proposal_UserLevel'] == 2)){
           //Must be logged in & dean
           $sql = "SELECT ID,ApprovingDean FROM proposals WHERE status='2'";
           $dbconnlocal = new Database();
           $dbconnlocal = $dbconnlocal->getConnection();
           $result = $dbconnlocal->query($sql);
           //lets find out our DEAN ID
           $deanIDSql = "SELECT id FROM deans WHERE userID='".intval($_SESSION['proposal_userID'])."'";
           $deanQuery = $dbconnlocal->query($deanIDSql);
           $deanRows = $deanQuery->fetch_assoc();
           $deanID = $deanRows['id'];
           //Now that we know our dean id and the id's + approving deans list we have to make proposal objects and return them
           $proposalsArray = array();
           while($approvals = $result->fetch_assoc()){
               if($approvals['ApprovingDean'] == $deanID){
                   $newProposal = new Proposal($approvals['ID']);
                   array_push($proposalsArray, $newProposal);
               }
           }
           return $proposalsArray;
       }elseif(($_SESSION['proposal_LoggedIn'])&&($_SESSION['proposal_UserLevel'] == 3)){
           //Must be logged in & committee member
           $sql = "SELECT ID FROM proposals WHERE status='4'";
           $dbconnlocal = new Database();
           $dbconnlocal = $dbconnlocal->getConnection();
           $result = $dbconnlocal->query($sql);
           //We have a list of proposals which are in "Sent to committee", lets show this list to the committee member
           $proposalsArray = array();
           while($approvals = $result->fetch_assoc()){
                $newProposal = new Proposal($approvals['ID']);
                array_push($proposalsArray, $newProposal);
           }
           return $proposalsArray;
           
       }
   }
   
   
   function approveProposal(){
       //This function will be used to approve a proposal, based on the user's Level. A dean will approve a proposal and submit it to committee, a committee with approve and move to scheduling
       if(($_SESSION['proposal_UserLevel'] == 2)&&($this->status == 2)){ // user is a dean and the status of the proposal is "sent to dean"
           $this->status = 4;
           //We have to send an email that the proposal has been approved by the Dean to the user
           $sendmail = new Email();
           $sendmail->sendMessage($this->userIDtoEmail($this->OwnerID), 'Your Proposal has been dean approved!', 'Hello '.$this->userIDtoFullName($this->OwnerID).', Your proposal has been approved by your dean and has been forwarded to the committee');
       }elseif(($_SESSION['proposal_UserLevel'] == 3)&&($this->status == 4)){ //user is part of the committee and the proposal is "sent to committee"
           $this->status = 5;
           $sendmail = new Email();
           $sendmail->sendMessage($this->userIDtoEmail($this->OwnerID), 'Your Proposal has been committee approved!', 'Hello '.$this->userIDtoFullName($this->OwnerID).', Your proposal has been approved by the IPRO committee. Your IPRO is currently being planned and scheduled.');
       }
   }
   
   function denyProposal(){
       if(($_SESSION['proposal_UserLevel'] == 2)&&($this->status == 2)){ //user is a dean and the proposal status is level 2 "sent to dean"
           $this->status = 1;//Proposal denied by dean
           $sendmail = new Email();
           $sendmail->sendMessage($this->userIDtoEmail($this->OwnerID), 'Your proposal has been denied by you dean', 'Hello '.$this->userIDtoFullName($this->OwnerID).', Your proposal has been denied by your dean. Please login to the IPRO proposal system to review the decision');           
       }elseif(($_SESSION['proposal_UserLevel'] == 3)&&($this->status == 4)){//user is part of committee and the proposal has been sent to committee
           $this->status = 3; // Proposal denied by committee
           $sendmail = new Email();
           $sendmail->sendMessage($this->userIDtoEmail($this->OwnerID), 'Your proposal has been denied by the committee', 'Hello '.$this->userIDtoFullName($this->OwnerID).', Your proposal has been denied by the committee. Please login to the IPRO proposal system to review the decision');
        }
   }
   
   function saveComments($comment){
       //This function will save any comments entered by a user.
       $comment = Database::sterilizeStr($comment);
       if($comment != ''){
            $sql = "INSERT INTO proposal_comments(comment,timestamp,userID,proposalID) 
                VALUES('".$this->dbconn->real_escape_string($comment)."','".$this->dbconn->real_escape_string(time())."','".$this->dbconn->real_escape_string($_SESSION['proposal_userID'])."','".$this->dbconn->real_escape_string($this->ID)."')";
            $this->dbconn->query($sql);
       }
       return true;
   }
   
   static function userIDtoFullName($userID){
       if(intval($userID) == 0){
           exit;
       }
       $dbconnlocal = new Database();
       $dbconnlocal = $dbconnlocal->getConnection();
       $sql = "SELECT id,FName,LName FROM users WHERE id='".intval($userID)."'";
       $query = $dbconnlocal->query($sql);
       $row = $query->fetch_assoc();
       return $row['FName'].' '.$row['LName'];
   }
   
   static function userIDtoEmail($userID){
       if(intval($userID) == 0){
           exit;
       }
       $dbconnlocal = new Database();
       $dbconnlocal = $dbconnlocal->getConnection();
       $sql = "SELECT id,email FROM users WHERE id='".intval($userID)."'";
       $query = $dbconnlocal->query($sql);
       $row = $query->fetch_assoc();
       return $row['email'];
   }
   
   
   function getComments(){
       //we will be pulling comments for this proposal
       $sql = "SELECT * FROM proposal_comments WHERE proposalID='".$this->ID."'";
       $query = $this->dbconn->query($sql);
       $CommentsArray = array();
       while($rows = $query->fetch_assoc()){
           //0 is the ID, 1 is the comment, 2 is the FullName of the user,3 is the timestamp
           $singleCommentArray = array();
           $singleCommentArray[0] = $rows['id'];
           $singleCommentArray[1] = $rows['comment'];
           $singleCommentArray[2] = Proposal::userIDtoFullName($rows['userID']);
           $singleCommentArray[3] = $rows['timestamp'];
           array_push($CommentsArray, $singleCommentArray);
       }
       return $CommentsArray;
   }
   
    function createRevision(){
        //This function creates a revision of the current proposal to the proposal_revisions database
        $revisionSql = "INSERT INTO `proposals_revisions`(`proposalID`, `Instructor`, `InstructorEmail`, `CoInstructor`, `CoInstructorEmail`, `Sponsor`, `ApprovingDean`, `Title`, `Problem`, `Objective`, `Approach`, `Semester`, `Days`, `Time`, `CourseNumber`, `OwnerID`, `status`, `dateLastModified`) VALUES 
            (".$this->dbconn->real_escape_string($this->ID).",
            ".$this->dbconn->real_escape_string($this->Instructor).",
            ".$this->dbconn->real_escape_string($this->InstructorEmail).",
            ".$this->dbconn->real_escape_string($this->CoInstructor).",
            ".$this->dbconn->real_escape_string($this->CoInstructorEmail).",
            ".$this->dbconn->real_escape_string($this->Sponsor).",
            ".$this->dbconn->real_escape_string($this->ApprovingDean).",
            ".$this->dbconn->real_escape_string($this->Title).",
            ".$this->dbconn->real_escape_string($this->Problem).",
            ".$this->dbconn->real_escape_string($this->Objective).",
            ".$this->dbconn->real_escape_string($this->Approach).",
            ".$this->dbconn->real_escape_string($this->Semester).",
            ".$this->dbconn->real_escape_string($this->Days).",
            ".$this->dbconn->real_escape_string($this->Time).",
            ".$this->dbconn->real_escape_string($this->CourseNumber).",
            ".$this->dbconn->real_escape_string($this->OwnerID).",
            ".$this->dbconn->real_escape_string($this->status).",
            ".date("Y-m-d H:i:s").")";
        $this->dbconn->query($revisionSql);
    }
}
?>
