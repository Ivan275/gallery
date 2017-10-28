<?php

function get_template($temp="") {
	include("$temp");
}

function redirect_to($location="") {
	if($location != NULL ){
		header("Location: {$location}");
		exit;
	}
}
function output_message($message="") {
  if (!empty($message)) { 
    return "<p class=\"message\">{$message}</p>";
  } else {
    return "";
  }
}

?>