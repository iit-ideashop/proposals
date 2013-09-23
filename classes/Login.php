<?php
//LOGIN CLASS
//used for processing logins and authentication related things

class Login{
    private $connection;
    function __construct(){
        //create a new database connection for authentication purposes
       
        $databaseObj = new Database();
        $this->connection = $databaseObj->getConnection();
    }
    
    function authenticate($username, $password){
        //Lets perform some serialization
        $username = Database::sterilizeStr($username);
        $password = Database::sterilizeStr($password);
        //Let's hash the password next and run it through the database
        $password = md5($password);
        $loginSql = "SELECT * FROM users WHERE Username='".$username."' AND Password='".$password."' LIMIT 1";
        $loginResult = $this->connection->query($loginSql);
        if($loginResult->num_rows == 1){
            //login successful
            $user = $loginResult->fetch_assoc();
            $_SESSION['proposal_userID'] = $user['id'];
            $_SESSION['proposal_FName'] = $user['FName'];
            $_SESSION['proposal_LName'] = $user['LName'];
            $_SESSION['proposal_Username'] = $user['Username'];
            $_SESSION['proposal_UserLevel'] = $user['Level'];
            $_SESSION['proposal_LoggedIn'] = true;
            header("Location:dashboard.php");
            exit;
        }else{
            //login failure
            
            FlashBang::addFlashBang("Red", "Login Failed", "Username or Password incorrect. Try again");
        }
    }
    
    static function loginCheck($levelRequired){
        //level required 
        //0 = any user logged in
        //
        //9 = logged in as admin
        
        //Performs a check to see if the user is logged in and if their level is sufficient.
        //Redirects the user to a login page if the credentials are not valid
        
        if($_SESSION['proposal_LoggedIn'] != true){
            //user is not logged in kick them out to the sign in page
            header("location:index.php");
            exit;
        }
        if(($_SESSION['proposal_UserLevel'] < 9)&&($levelRequired == 9)){
            //user is not an admin, kick out to dashboard.php
            header("location:dashboard.php");
            exit;
        }
        
    }
    
    static function logout(){
        $_SESSION['proposal_userID'] = '';
        $_SESSION['proposal_FName'] = '';
        $_SESSION['proposal_LName'] = '';
        $_SESSION['proposal_Username'] = '';
        $_SESSION['proposal_UserLevel'] = '';
        $_SESSION['proposal_LoggedIn'] = '';
        FlashBang::addFlashBang("Green", "Logout Successful", "User has been successfully logged out");
        header("Location:dashboard.php");
        exit;
    }
    function usernameAvailable($username){
        //Checks to see if a username exists. Return true if its not in use
        $sql = "SELECT id FROM users WHERE Username='".mysqli_real_escape_string($this->connection,$username)."' LIMIT 1";
        $query = $this->connection->query($sql);
        if($query->num_rows != 1){
            return true;
        }else{
            return false;
        }
    }
    
    
    function createNewUser($fname,$lname,$username,$password,$email){
        //We are going to do some error checking 
        if($username == ''){
            //Blank username
            FlashBang::addFlashBang("Red", "Form Error", "Username cannot be blank");
            return false;
        }
        if($password==''){
            //Blank Password
            FlashBang::addFlashBang("Red", "Form Error", "Password cannot be blank");
            return false;
        }
        if($fname==''){
            //Blank first name
            FlashBang::addFlashBang("Red", "Form Error", "First Name cannot be blank");
            return false;
        }
        if($lname==''){
            //Blank last name
            FlashBang::addFlashBang("Red", "Form Error", "Last Name cannot be blank");
            return false;
        }
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            //Email failed validation
            FlashBang::addFlashBang("Red", "Form Error", "Your email failed validation. Please fix your email address");
            return false;
        }
        //Next we check if the username is available. if it is we make the account
        if($this->usernameAvailable($username)){
            $fname = mysqli_real_escape_string($this->connection,$fname);
            $lname = mysqli_real_escape_string($this->connection,$lname);
            $username = mysqli_real_escape_string($this->connection,$username);
            $email = mysqli_real_escape_string($this->connection,$email);
            $sql = "INSERT INTO users(FName,LName,Username,Password,Email,Level) 
                VALUES('".$fname."',
                    '".$lname."',
                    '".$username."',
                    '".md5($password)."',
                    '".$email."',
                    '1')";
            $query = $this->connection->query($sql);
            return true;
        }else{
            FlashBang::addFlashBang("Red", "Form Error", "Username is already in use.");
            return false;
        }
    }
}


?>
