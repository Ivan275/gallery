
<?php require_once('../includes/initialize.php'); ?>
<?php
	// must have an ID
  if(empty($_GET['id'])) {
  	$session->message("No photograph ID was provided.");
    redirect_to('index.php');
  }

  $photo = Photograph::find_by_id($_GET['id']);
  if($photo && $photo->destroy()) {
    $session->message("The photo {$photo->filename} was deleted.");
    redirect_to('index.php');
  } else {
    $session->message("The photo could not be deleted.");
    redirect_to('index.php');
  }
  
?>