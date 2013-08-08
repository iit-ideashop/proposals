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
    private $Disciplines; // this is going to be an array of discipline ID's
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
                $this->Disciplines = unserialize($rows['Disciplines']);
                $this->Title = $rows['Title'];
                $this->Problem = $rows['Problem'];
                $this->Objective = $rows['Objective'];
                $this->Approach = $rows['Approach'];
                $this->Semester = $rows['Semester'];
                $this->Days = unserialize($rows['Days']);
                $this->Time = $rows['Time'];
                $this->CourseNumber = $rows['CourseNumber'];
                $this->OwnerID = $rows['OwnerID'];
                $this->status = $rows['status'];
            }
        }else{
            $this->Days = array(0);
            $this->Semester = 0;
            $this->ApprovingDean = 0;
            $this->Disciplines = array();
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
    
    
    static function generateDisciplinesCheckboxes($checkedArray){
        //This function will generate checkboxes for the disciplines in the database.
        //First we must pull all of the disciplines from the database
        $dbconnlocal = new Database();
        $dbconnlocal = $dbconnlocal->getConnection();
        $sql = "SELECT * FROM disciplines";
        $result = $dbconnlocal->query($sql);
        $discipleArray = array();
        while($rows = $result->fetch_assoc()){
            $discipleArray[$rows['id']] = $rows['disciplineName'];
        }
        //Next we generate checkBoxes
        if($checkedArray == 0){
            $checkedArray = array(0);
        }
        //Next we generate the checkboxes
        $output = '';
        for($i=1;$i <= count($discipleArray);$i++){
            $checked = '';
            if(in_array($i, $checkedArray)){
                $checked = 'checked="checked"';
            }
            $output .= '<label class="checkbox-inline">
                <input type="checkbox" name="disc-'.$i.'" '.$checked.'> '.$discipleArray[$i].'
              </label>';
        }
        $dbconnlocal->close();
        return $output;
    }
    
    function readFromDiscipleCheckboxes(){
        //This function is used to read the checkboxes values and put the ID's into an array.
        //First let's get the values we can read
        $sql = "SELECT * FROM disciplines";
        $result = $this->dbconn->query($sql);
        $discipleArray = array();
        while($rows = $result->fetch_assoc()){
            $discipleArray[$rows['id']] = $rows['disciplineName'];
        }
        $checkboxesArray = array();
        //Now let's loop
        foreach($discipleArray as $key=>$value){
            //$key is the Disciple ID
            //$value is the DISCIPLE value
            if(isset($_POST['disc-'.$key])){
                //Value is set now let's see if it's on
                if($_POST['disc-'.$key] == 'on'){
                    //checkbox was checked! add $key to the array using array_push
                    array_push($checkboxesArray, $key);
                }
            }
        }
        return $checkboxesArray;
    }
    
    function readDayfromForm(){
        //This is going to be really simple, we have to return the day array in the form OF 0-4 being MON-FRI
        $dayArray = array();
        for($i=0;$i<=4;$i++){
            if(isset($_POST['day-'.$i])){
                if($_POST['day-'.$i] == 'on'){
                    array_push($dayArray,$i);
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
        
        //To get this value we have to run readFromDiscipleCheckboxes and it returns an array of checkboxes
        $this->Disciplines = $this->readFromDiscipleCheckboxes();
        
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
           //Step 1, figure out the proposal ID for this new proposal
           $proposalIDSql = "SHOW TABLE STATUS LIKE 'proposals_control'";
           $proposalIDResult = $this->dbconn->query($proposalIDSql);
           $proposalIDResult = $proposalIDResult->fetch_assoc();
           $nextProposalID = $proposalIDResult['Auto_increment'];
           //Step 2, create the next proposal ID and set the revision to 1
           $controlSql = "INSERT INTO proposals_control(proposalID,lastRevision) VALUES('".$nextProposalID."','1')";
           $controlQuery = $this->dbconn->query($controlSql);
           //Step 3 insert the new proposal into the database
           //TODO: ADD INSERT CODE FOR NEW PROPOSAL
           $sql = "INSERT INTO proposals(Instructor,InstructorEmail,CoInstructor,CoInstructorEmail,Sponsor,ApprovingDean,Disciplines,Title,Problem,Objective,Approach,Semester,Days,Time,CourseNumber,OwnerID,status) 
                    VALUES('".$this->Instructor."','".$this->InstructorEmail."'
                        ,'".$this->CoInstructor."','".$this->CoInstructorEmail."',
                            '".$this->Sponsor."','".$this->ApprovingDean."',
                                '".serialize($this->Disciplines)."','".$this->Title."','".$this->Problem."'
                                    ,'".$this->Objective."','".$this->Approach."','".$this->Semester."',
                                        '".serialize($this->Days)."','".$this->Time."','".$this->CourseNumber."',
                                            '".$this->OwnerID."','".$this->status."')";
           $query = $this->dbconn->query($sql);
       }else{
           //we have to update the proposal in the database
           $sql = "UPDATE proposals SET Instructor='".$this->Instructor."',
               InstructorEmail='".$this->InstructorEmail."',
               CoInstructor='".$this->CoInstructor."',
               CoInstructorEmail='".$this->CoInstructorEmail."',
               Sponsor='".$this->Sponsor."',
               ApprovingDean='".$this->ApprovingDean."',
               Disciplines='".serialize($this->Disciplines)."',
               Title='".$this->Title."',
               Problem='".$this->Problem."',
               Objective='".$this->Objective."',
               Approach='".$this->Approach."',
               Semester='".$this->Semester."',
               Days='".serialize($this->Days)."',
               Time='".$this->Time."',
               CourseNumber='".$this->CourseNumber."',
               OwnerID='".$this->OwnerID."',
               status='".$this->status."' WHERE ID='".$this->ID."' LIMIT 1";
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
           $output .='<option '.$selected.' value="'.$rows['id'].'">'.$rows['deanName'].' - '.$rows['school'].'</option>';
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
   
   function getDisciplines(){
       return $this->Disciplines;
   }
   
   function setDisciplines($newDisciplines){
       $this->Disciplines = $newDisciplines;
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
               $statusClass = 'label-7';
               break;
           default:
               $statusClass = 'label-0';
               break;
       }
       return $statusClass;
   }
   
   function submitForApproval(){
       //We are going to set this proposal's status to "sent to dean"
       $this->status = 2;
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
   
   function displayTargetedMajors(){
       $sql = "SELECT * FROM disciplines";
       $result = $this->dbconn->query($sql);
       $output ='';
       $disciplinesArray = array();
       while($rows = $result->fetch_assoc()){
           $disciplinesArray[$rows['id']] = $rows['disciplineName'];
       }
       for($i=0;$i<count($this->Disciplines);$i++){
           echo $disciplinesArray[$this->Disciplines[$i]].' ';
       }
       return $output;
   }
   
   function displayDays(){
       $dayArray = array('M','T','W','Th','F');
       $output ='';
       for($i=0;$i<count($this->Days);$i++){
           $output .= $dayArray[$this->Days[$i]].' ';
       }
       return $output;
   }
}
?>
