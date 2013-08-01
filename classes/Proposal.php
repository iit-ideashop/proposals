<?php
//Proposal Class
//Used for loading proposals 
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
        //This class is used to load and store proposal objects to and from the database
        $this->dbconn = new Database();
        $this->dbconn = $this->dbconn->getConnection();
        
        
    }
    
    static function generateDisciplinesCheckboxes($checkedArray){
        //This function will generate checkboxes for the disciplines in the database.
        //First we must pull all of the disciplines from the database
        $sql = "SELECT * FROM disciplines";
        $result = $this->dbconn->query($sql);
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
        for($i=1;$i < count($discipleArray);$i++){
            if(in_array($i, $checkedArray)){
                $checked = 'checked="checked"';
            }
            $output .= '<input type="checkbox" '.$checked.' name="disc-'.$i.'">';
        }
        return $output;
    }
    
    function readProposalfromForm(){
        //Here we are going to read posted data from the form and set all of the local variables
        $this->Instructor = $_POST[''];
        $this->InstructorEmail = $_POST[''];
        $this->CoInstructor = $_POST[''];
        $this->CoInstructorEmail = $_POST[''];
        $this->Sponsor = $_POST[''];
        $this->Disciplines = $_POST['']; // This needs use another function to get the checked array
        $this->ApprovingDean = $_POST[''];
        $this->Title = $_POST[''];
        $this->Problem = $_POST[''];
        $this->Objective = $_POST[''];
        $this->Approach = $_POST[''];
        $this->Days = $_POST[''];//
        $this->Time = $_POST[''];
        $this->Semester = $_POST[''];
   }
   
   function saveToDatabase(){
       if($this->ID == 0){
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
                                '".$this->Disciplines."','".$this->Title."','".$this->Problem."'
                                    ,'".$this->Objective."','".$this->Approach."','".$this->Semester."',
                                        '".$this->Days."','".$this->Time."','".$this->CourseNumber."',
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
               Disciplines='".$this->Disciplines."',
               Title='".$this->Title."',
               Problem='".$this->Problem."',
               Objective='".$this->Objective."',
               Approach='".$this->Approach."',
               Semester='".$this->Semester."',
               Days='".$this->Days."',
               Time='".$this->Time."',
               CourseNumber='".$this->CourseNumber."',
               OwnerID='".$this->OwnerID."',
               status='".$this->status."',
";
       }
   }
   
   
   function generateDeanDropdown(){
       //This function generates a dropdown list of the deans + colleges and the value field is a integer for ID
       $sql = "SELECT * FROM deans";
       $result = $this->dbconn->query($sql);
       $output = '<select name="approvingDean">';
       while($rows = $result->fetch_assoc()){
           $output .='<option value="'.$rows['id'].'">'.$rows['deanName'].' '.$rows['school'].'</option>';
       }
       $output .= '</select>';
       return $output;
   }
   
   
   
   //getters and setters
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
}
?>
