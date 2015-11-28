<?php
if (isset($error) OR isset($success))
{
	?>
	<div class="container">
		<div class="jumbotron">
			<?php
			if (isset($error))
				echo '<h2>An error occured : ' . $error . '</h2>' . PHP_EOL;
			else
				echo '<h2>' . $success . '</h2>' . PHP_EOL;
			?>
		</div>
	</div>
	<?php
}
?>
<div class="container">
	<div class="jumbotron">
		<form method="post" action="<?php echo $action; ?>" class="form-horizontal">
			<div class="form-group">
				<label for="inputTitle" class="col-sm-2 control-label">Title</label>
				<div class="col-sm-10">
					<input type="text" name="title" class="form-control" id="inputTitle" placeholder="Title">
				</div>
			</div>
			<div class="form-group">
				<label for="inputContent" class="col-sm-2 control-label">Content</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="15" name="content" id="inputContent" placeholder="Content"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Create article</button>
				</div>
			</div>
		</form>
	</div>
</div>