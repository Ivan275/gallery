<?php require_once('../includes/initialize.php'); ?>
<?php get_template("admin_header.php"); ?>
<?php if(!$session->is_logged_in()){ redirect_to("login.php"); } ?>
<?php 
	$max_file = 1000000; 
	if(isset($_POST['submit'])){
		$photo = new photograph();
		$photo->caption = $_POST['caption'];
		$photo->attach_file($_FILES['file_upload']);
		if($photo->save_file()){
			$session->message("successfully upload.");
			redirect_to("index.php");
		}else {
			$message= join("<br />", $photo->errors);
		}

	}

?>
	<?php if(!empty($message)) { echo "<p>{$message}</p>"; } ?>
	<form action="upload_file.php" enctype="multipart/form-data" method="POST">
		  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file; ?>" />
		  <p><input type="file" name="file_upload" /></p>
		   <p>Caption: <input type="text" name="caption" value=""/></p>
		  <input type="submit" name="submit" value="Upload" />
	</form>

<?php get_template("admin_footer.php"); ?>