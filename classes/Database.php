<?php
//database object, creates a database connection
class Database{
    private $database_conn;
    function __construct(){
        //create the connection
        include('config/database_config.php');
        $this->database_conn = new mysqli($database_location,$database_username,$database_password,$database_name);
    }
    
    public function getConnection(){
        return $this->database_conn;
    }
    
    public static function sterilizeInt($int){
	$input = intval($int);
	return $input;
    }
    
    public static function sterilizeStr($str){
	//Clean the input and set it as the output.
	$output = htmlspecialchars($str);
	return $output;

    }
}
?>
