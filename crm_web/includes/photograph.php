<?php
require_once('config.php');
require_once('database.php');
require_once('database_object.php');

class Photograph extends DatabaseObject {

	protected static $table="photograph";
	protected static $db_fields=array('id', 'filename', 'type', 'size', 'caption');
	public $id;
	public $filename;
	public $type;
	public $size;
	public $caption;

	private $temp_path;
	protected $upload_dir = "upload";
	public $errors = array();

	protected $upload_errors = array(
		// http://www.php.net/manual/en/features.file-upload.errors.php
	  UPLOAD_ERR_OK 		=> "No errors.",
	  UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
	  UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
	  UPLOAD_ERR_PARTIAL 	=> "Partial upload.",
	  UPLOAD_ERR_NO_FILE 	=> "No file.",
	  UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
	  UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
	  UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
	);

	public function destroy() {
		// First remove the database entry
		if($this->delete()) {
			// then remove the file
		  // Note that even though the database entry is gone, this object 
			// is still around (which lets us use $this->image_path()).
			$target_path = $this->image_path();
			return unlink($target_path) ? true : false;
		} else {
			// database delete failed
			return false;
		}
	}

	public function attach_file($file){
		if(!$file || empty($file) || !is_array($file)) {

			$this->errors[] = "no file upload";
			return false;
		} else if($file['error'] != 0) {
			$this->errors[] = $this->upload_errors[$file['error']];
			return false;
		} else {
			$this->temp_path = $file['tmp_name'];
			$this->filename = basename($file['name']);
			$this->type = $file['type'];
			$this->size = $file['size'];
			return true;
		}
	}

	public function save_file() {

		if(isset($this->id)) {
			$this->update();

		} else {
			if(!empty($this->errors)) {
				return false;
			}
			if(strlen($this->caption) > 225) {
				$this->errors[] = "caption should be less 225 words.";
				return false;
			}
			if(empty($this->filename) || empty($this->temp_path)) {
				$this->errors[] = " file upload failed.";
				return false;
			}
			$target_dir = "../admin/upload/".$this->filename;
			
			if(file_exists($target_dir)){
				$this->errors[] = "the {$this->filename} already exist.";
				return false;
			}

			if(move_uploaded_file($this->temp_path, $target_dir)){

				if($this->create()) {
					unset($this->temp_path);
					return true;
				}
				
			}else{
				$this->errors[] = " upload file failed.";
				return false;
			}
			
		}
	}

	public function image_path() {
	  return $this->upload_dir."/".$this->filename;
	}
	
	public function size_as_text() {
		if($this->size < 1024) {
			return "{$this->size} bytes";
		} elseif($this->size < 1048576) {
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
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
	  $sql = "DELETE FROM ".self::$table;
	  $sql .= " WHERE id=". $database->escape_value($this->id);
	  $sql .= " LIMIT 1";
	  $database->query($sql);
	  return ($database->affected_rows() == 1) ? true : false;
	}
	
}
?>