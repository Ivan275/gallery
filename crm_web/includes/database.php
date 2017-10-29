<?php
//user class

require_once('config.php');

Class Mydatabase{

	private $connection;

	// function __connection(){
	// 	$this->open_connection();
	// }
	function __construct() {
		$this->open_connection();
	}

	public function open_connection() {
		//set up connection
		$this->connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
		if(mysqli_connect_errno()){
			die(" Database connection failed.");
		}
	}
	public function open_connection2() {
		//set up connection
		$this->connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
		if(mysqli_connect_errno()){
			die(" Database connection failed.");
		}
		//echo "connected!";
		return $this->connection;
	}


	public function close_connection() {
		//close connection
		if(isset($this->connection)){
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}

	public function query($sql) {
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;

	}

	private function confirm_query($result){
		if(!$result){
			die("Database query failed.");
		}
	}

	public function escape_value($string){
		$escaped_string = mysqli_real_escape_string($this->connection,$string);
		return $escaped_string;
	}
	

	public function fetch_array($result_set) {
		return mysqli_fetch_array($result_set);
	}
	public function num_rows($result_set) {
		return mysqli_num_rows($result_set);
	}
	public function insert_id(){
		//get last id inserted into current db connection
		return mysqli_insert_id($this->connection);
	}
	public function affected_rows(){
		return mysqli_affected_rows($this->connection);
	}
	public function display_data($sql){	
		//display sql table
		$result = mysqli_query($this->connection, $sql);
		if (mysqli_num_rows($result) > 0) {
			return true;
		    // while($row = mysqli_fetch_assoc($result)) {
		    //     echo "id : ".$row["id"] ."username: " . $row["username"]. " - Name: " . $row["first_name"]. " " . $row["last_name"]. "<br>";
		    // }
		} else {
		    //echo "0 results";
		    return false;
		}
	}
	
}

$database = new Mydatabase();


?>