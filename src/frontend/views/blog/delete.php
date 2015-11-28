<div class="container">
	<div class="jumbotron">
		<?php
		if (isset($error))
			echo '<h2>An error occurred : ' . $error . '</h2>';
		else if (isset($success))
			echo '<h2>' . $success . '</h2>';
		else
		{
			?>
			<form method="post" action="<?php echo $action; ?>" class="form-horizontal">
				<h2>Are you sure you want to delete this article ?</h2>
				<div class="form-group">
					<label for="delete" class="sr-only">Delete article</label>
					<button type="submit" id="delete" class="btn btn-danger">Yes, I'm really sure that I want to delete article</button>
				</div>
			</form>
			<?php
		}
		?>
	</div>
</div>