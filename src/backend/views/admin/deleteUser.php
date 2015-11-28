<div class="container">
	<div class="jumbotron">
		<?php
		if (isset($error))
			echo '<h2>An error occured : ' . $error . '</h2>';
		else if (isset($success))
			echo '<h2>' . $success . '</h2>';
		else
		{
			?>
			<form method="post" action="<?php echo $action; ?>">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<p>Are you sure you want to delete user #<?php echo $id; ?> ?</p>
				<button type="submit" class="btn btn-danger">Delete this user</button>
			</form>
			<?php
		}
		?>
	</div>
</div>