
<?php require_once('../includes/initialize.php'); ?>
<?php if (!$session->is_logged_in()) { redirect_to("../login.php"); } ?>

<?php
  // Find all the photos
  $photos = Photograph::find_all();
?>
<?php get_template("../admin/admin_header.php"); ?>


<ul>
	<li>
		<a href="upload_file.php">upload</a>
	</li>

</ul>


<table class="bordered">
  <tr>
    <th>Image</th>
    <th>Filename</th>
    <th>Caption</th>
    <th>Size</th>
    <th>Type</th>
		<th>&nbsp;</th>
  </tr>
<?php foreach($photos as $photo): ?>
  <tr>
    <td><img src="<?php echo $photo->image_path(); ?>" width="100" /></td>
    <td><?php echo $photo->filename; ?></td>
    <td><?php echo $photo->caption; ?></td>
    <td><?php echo $photo->size_as_text(); ?></td>
    <td><?php echo $photo->type; ?></td>
		<td><a href="delete_photo.php?id=<?php echo $photo->id; ?>">Delete</a></td>
  </tr>
<?php endforeach; ?>
</table>
<?php
echo $user->full_name();
?>
<?php get_template("../admin/admin_footer.php"); ?>