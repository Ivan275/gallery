<?php require_once('../includes/initialize.php'); ?>
<?php

if(isset($_POST['submit'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	$found_user = $user->authenticate($username,$password);

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
			<td><input type="text" name="password"value="<?php echo htmlentities($password)?>"/></td>
		</tr>
		<tr>
			<td colspan="12">
			<input type="submit" name="submit" value="Login" />
			</td>
		</tr>
	</table>
</form>


<?php get_template("footer.php"); ?>