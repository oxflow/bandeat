<div class="container">
	<div class="jumbotron">
	<?php
	if (isset($error))
		echo '<h2>An error occurred : ' . $error . '</h2>';
	else if (isset($success))
		echo '<h2>' . $success . '</h2>';
	else if (isset($edit_url) AND isset($delete_url))
	{
		$content = str_replace("<br>", "", $comment->content());
		?>
		<form method="post" action="<?php echo $edit_url; ?>">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="content" class="col-sm-2">Coment :</label>
					<div class="col-sm-10">
						<textarea name="content" rows="15" id="content" class="form-control"><?php echo $content; ?></textarea>
					</div>
				</div>
			</div>
			<div class="form-inline">
				<div class="form-group">
					<label for="edit" class="sr-only">Edit comment</label>
					<button type="submit" id="edit" class="btn btn-primary">Edit comment</button>
				</div>
				<div class="form-group">
					<label for="delete" class="sr-only">Delete comment</label>
					<a href="<?php echo $delete_url; ?>"><button type="button" id="delete" class="btn btn-danger">Delete comment</button></a>
				</div>
			</div>
		</form>
		<?php
	}
	else
	{
	}
	?>
	</div>
</div>