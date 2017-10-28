<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once('database.php');

class DatabaseObject {



	public static function find_all(){
		/*global $database;
		$result_set = $database->query("select * from users");
		return $result_set;*/
		return static :: find_by_sql("select * from ".static::$table);
	} 	

	public static function find_by_id($id=0){
		global $database;
		$result_array = static::find_by_sql("select * from " .static::$table."  where id = {$id} limit 1");
		//only one element in the array, array_shift will get first element of array
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		//sign each row as an object and add into array
	    while ($row = $database->fetch_array($result_set)) {
	      $object_array[] = static::instantiate($row);
	    }
		return $object_array;
	}
		public static function instantiate($record){
		$object = new static;
		/*$object->id = $record['id'];
		$object->username = $record['username'];
		$object->password = $record['password'];
		$object->first_name =$record['first_name'];
		$object->last_name =$record['last_name'];*/

		foreach($record as $attribute=>$value){
		  if($object->has_attribute($attribute)) {
		    $object->$attribute = $value;
		  }
		}
		return $object;
	}

	private function has_attribute($attribute) {
	  // get_object_vars returns an associative array with all attributes 
	  // (incl. private ones!) as the keys and their current values as the value
		// get_object_vars is php function
	  $object_vars = $this->attributes();
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  // array_key_exists is php function
	  return array_key_exists($attribute, $object_vars);
	}

	protected function sanitized_attributes() {
	  global $database;
	  $clean_attributes = array();
	  // sanitize the values before submitting
	  // Note: does not alter the actual value of each attribute
	  foreach($this->attributes() as $key => $value){
	    $clean_attributes[$key] = $database->escape_value($value);
	  }
	  return $clean_attributes;
	}

	protected function attributes(){
		$attributes = array();
		foreach(static::$db_fields as $field) {
		    if(property_exists($this, $field)) {
		      $attributes[$field] = $this->$field;
		    }
		 }
		 return $attributes;
	}


	
}