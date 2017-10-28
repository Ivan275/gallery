<?php require_once('includes/initialize.php'); 
	  if($session->is_logged_in()){
	  	redirect_to("admin/index.php");
	  }
?>



<?php
/*if(isset($database)){ echo "true";} else { echo "false";}*/

/*echo $database -> mysql_prep("this's my");
$sql = "select * from users where id = 1";
$result= $database->query($sql);
$found = $database->fetch_array($result);
echo $found["username"];*/

/*$user  = User::find_by_id(1);


echo $user->full_name();*/
//create
/*$user = new User();
$user->username= "admin";
$user->password = "password";
$user->first_name="admin";
$user->last_name = "He";
$user->create();*/

/*$user = User::find_by_id(1);
$user->password = "123";
$user->update()*/

/*$user = User::find_by_id(3);
$user->delete();
echo $user->username. " is delete";
*/
?>
<?php

if(isset($_POST['submit'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	$found_user = User::authenticate($username,$password);

	if($found_user){
		$session->login($found_user);
		redirect_to("admin/index.php");

	}else{

		echo "user is not exist;";

	}


}else{// if form has not been submitted, initialize all values
	$username = "";
	$password = "";
}
?>

<?php get_template("header.php"); ?>

<h2> User login </h2>
<form action="login.php" method="post">
	<table>
		<tr>
			<th>User Name:</th>
			<td><input type="text" name="username" value="<?php echo htmlentities($username)?>"/></td>
		</tr>	
		<tr>
			<th>Password:</th>
			<td><input type="password" name="password"value="<?php echo htmlentities($password)?>"/></td>
		</tr>
		<tr>
			<td colspan="12">
			<input type="submit" name="submit" value="Login" />
			</td>
		</tr>
	</table>
</form>


<?php get_template("footer.php"); ?>
<?php if(isset($database)) { $database->close_connection();} ?>