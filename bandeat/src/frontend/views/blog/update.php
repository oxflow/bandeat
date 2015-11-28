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
				<div class="form-group">
					<label for="inputTitle" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
						<input type="text" name="title" class="form-control" id="inputTitle" value="<?php echo $title; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="inputContent" class="col-sm-2 control-label">Content</label>
					<div class="col-sm-10">
						<textarea class="form-control" rows="15" name="content" id="inputContent"><?php echo $content; ?></textarea>
					</div>
				</div>
				<?php
				if (isset($edited))
				{
					?>
					<div class="form-group">
						<label for="inputEdited" class="col-sm-2 control-label">Edited times</label>
						<div class="col-sm-10">
							<input type="number" name="edited" class="form-control" id="inputEdited" min="0" value="<?php echo $edited; ?>">
						</div>
					</div>
					<?php
				}
				?>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary">Edit article</button>
					</div>
				</div>
			</form>
			<?php
		}
		?>
	</div>
</div>