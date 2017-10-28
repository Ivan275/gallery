<?php
//user class
require_once('config.php');
require_once('database.php');
require_once('database_object.php');

Class User extends DatabaseObject {

//set up database
	protected static $table = "users";
	protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name');
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;


	public function full_name(){
		if(isset($this->first_name) && isset($this->last_name)) {
			return $this->first_name. " ". $this->last_name;
		} else {
			return "";
		}
	}

	public static function authenticate($username="",$password=""){
		global $database;

		$sql = "SELECT * FROM users ";
		$sql .= "where username = '{$username}' and ";
		$sql .= "password = '{$password}' ";
		$sql .= "limit 1";
		
		$result_array = self::find_by_sql($sql);
		//only one element in the array, array_shift will get first element of array
		return !empty($result_array) ? array_shift($result_array) : false;

		//$database->close_connection();
	}

	public function save() {
	  // A new record won't have an id yet.
	  return isset($this->id) ? $this->update() : $this->create();
	}

	public function create() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection

		$attributes = self::sanitized_attributes();



	  	$sql = "INSERT INTO ".self::$table." (";
	  	$sql .= join(", ", array_keys($attributes));
	  	//$sql .= "username, password, first_name, last_name";
	  	$sql .= ") VALUES ('";
	  	$sql .= join("', '", array_values($attributes));
	  	$sql .= "')";
		/*$sql .= $database->escape_value($this->username) ."', '";
		$sql .= $database->escape_value($this->password) ."', '";
		$sql .= $database->escape_value($this->first_name) ."', '";
		$sql .= $database->escape_value($this->last_name) ."')";*/
		  if($database->query($sql)) {
		    $this->id = $database->insert_id();
		    return true;
		  } else {
		    return false;
		  }
	}

	public function update() {
	  	global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection

		$attributes = self::sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
		  $attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$table." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=". $database->escape_value($this->id);
		/*$sql = "UPDATE users SET ";
		$sql .= "username='". $database->escape_value($this->username) ."', ";
		$sql .= "password='". $database->escape_value($this->password) ."', ";
		$sql .= "first_name='". $database->escape_value($this->first_name) ."', ";
		$sql .= "last_name='". $database->escape_value($this->last_name) ."' ";
		$sql .= "WHERE id=". $database->escape_value($this->id);*/
	  $database->query($sql);
	  return ($database->affected_rows() == 1) ? true : false;
	}

	public function delete() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		// - use LIMIT 1
	  $sql = "DELETE FROM users ";
	  $sql .= "WHERE id=". $database->escape_value($this->id);
	  $sql .= " LIMIT 1";
	  $database->query($sql);
	  return ($database->affected_rows() == 1) ? true : false;
	}
}

$user = new User();

?>