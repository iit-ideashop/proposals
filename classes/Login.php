<?php
//LOGIN CLASS
//used for processing logins and authentication related things
require_once("include/password_compat.php");
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
		//Let's hash the password next and run it through the database
		$loginSql = "SELECT * FROM users WHERE Username='".$username."' LIMIT 1";
		$loginResult = $this->connection->query($loginSql);
		if($loginResult->num_rows == 1){
			//user found
			$user = $loginResult->fetch_assoc();
			if(password_verify($password, $user['Password'])){
			//success with new hash algo
				$_SESSION['proposal_userID'] = $user['id'];
				$_SESSION['proposal_FName'] = $user['FName'];
				$_SESSION['proposal_LName'] = $user['LName'];
				$_SESSION['proposal_Username'] = $user['Username'];
				$_SESSION['proposal_UserLevel'] = $user['Level'];
				$_SESSION['proposal_LoggedIn'] = true;
				header("Location:dashboard.php");
				exit;
			}
			//TEMPORARY check md5
			if(md5($password) === $user['Password']){
				//login successful
				$_SESSION['proposal_userID'] = $user['id'];
				$_SESSION['proposal_FName'] = $user['FName'];
				$_SESSION['proposal_LName'] = $user['LName'];
				$_SESSION['proposal_Username'] = $user['Username'];
				$_SESSION['proposal_UserLevel'] = $user['Level'];
				$_SESSION['proposal_LoggedIn'] = true;
				//update password with new algo
				$this->changePassword($password);
				header("Location:dashboard.php");
				exit;
			}
		}	
			//Login failed
			FlashBang::addFlashBang("Red", "Login Failed", "Username or Password incorrect. Try again");
			
	}
	static function csrfToken(){
		if(!isset($_SESSION['proposal_csrf'])){
			$_SESSION['proposal_csrf'] = bin2hex(openssl_random_pseudo_bytes(16));
		}
		return $_SESSION['proposal_csrf'];
	}
	static function csrfTest(){
		if(isset($_SESSION['proposal_csrf']) && ($_SESSION['proposal_csrf'] === $_POST['csrf'])){
			return;
		} else {
			die("invalid form token");
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
	function resetPassword($email){
		//Reset a user's password to a random value and email the new password to them
		$plaintext = bin2hex(openssl_random_pseudo_bytes(16));
		$password = password_hash($plaintext,PASSWORD_DEFAULT);
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
			//Email failed validation
			FlashBang::addFlashBang("Red", "Form Error", "Your email failed validation. Please fix your email address");
			return false;
		}
		$stmt = $this->connection->prepare("UPDATE users SET Password=? WHERE email=? LIMIT 1");
		$stmt->bind_param("ss",$password,$email);
		if(!$stmt->execute()){
			error_log("Password change failed for user ".$email);
			$stmt->close();
			return false;
		}
		if($stmt->affected_rows == 1){
			$emailobj = new Email();
			$emailobj->sendMessage($email, '[IPRO Proposals] Password Reset', 'A password reset has been requested for an account under your email address. Your temporary password has been set to '.$plaintext.' and you should change this password at your earliest convenience.'); 
			return true;
		}
		return false;
	}	
	function changePassword($newpw){
		//Reset the logged in user's password to the provided value
		if(!(isset($_SESSION['proposal_userID']) && isset($_SESSION['proposal_LoggedIn']) && $_SESSION['proposal_LoggedIn'] == true)){
			error_log("Change password called but no user is logged in!");
			return false;
		}
		$plaintext = $newpw;
		$password = password_hash($plaintext,PASSWORD_DEFAULT);
		$stmt = $this->connection->prepare("UPDATE users SET Password=? WHERE id=?");
		$stmt->bind_param("ss",$password,$_SESSION['proposal_userID']);
		if(!$stmt->execute()){
			error_log("Password change failed for user ".$_SESSION['proposal_userID']);
			$stmt->close();
			return false;
		}
		if($stmt->affected_rows == 1){
			$stmt->close();
			return true;
		}
		$stmt->close();
		return false;
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
						'".password_hash($password)."',
						'".$email."',
						'1')";
			$query = $this->connection->query($sql);
			$emailobj = new Email();
			$emailobj->sendMessage($email, '[IPRO Proposals] Account Created', 'Hello '.$fname.' '.$lname.', Your account with username '.$username.' has been created. You can now use this account to sign into the IPRO Proposal system.');
			return true;
		}else{
			FlashBang::addFlashBang("Red", "Form Error", "Username is already in use.");
			return false;
		}
	}
}


?>
