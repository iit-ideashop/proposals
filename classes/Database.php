<?php
//database object, creates a database connection
class Database{
    private static $database_conn;
    private static $databaseConnected = false;
    function __construct(){
        if(!self::$databaseConnected){
            //create the connection
            include('config/database_config.php');
            self::$database_conn = new mysqli($database_location,$database_username,$database_password,$database_name);
            self::$databaseConnected = true;
            }
    }
    
    public function getConnection(){
        return self::$database_conn;
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
