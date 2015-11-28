<div class="container">
	<div class="jumbotron">
		<h2><a href="#"><?php echo $article->title(); ?></a></h2>
		<h4>
			<em>Posted on <?php echo $article->timestamp(); ?> by <?php echo $author; ?></em>
		</h4>
		<p>
			<?php echo $article->content(); ?>
		</p>
		<?php
		if (isset($edit))
		{
			if ($edit > 1)
				echo '<h4><em>Edited ' . $edit . ' times, last time at ' . $edit_time . '</em></h4>';
			else
				echo '<h4><em>Edited ' . $edit . ' time, last time at ' . $edit_time . '</em></h4>';
		}
		if ($edit_access === true)
		{
			?>
			<form method="post" action="<?php echo $action; ?>" class="form-inline">
				<div class="form-group">
					<label for="edit" class="sr-only">Edit</label>
					<button type="submit" name="goto" id="edit" value="edit" class="btn btn-primary">Edit article</button>
				</div>
				<div class="form-group">
					<label for="delete" class="sr-only">Delete</label>
					<button type="submit" name="goto" id="delete" value="delete" class="btn btn-danger">Delete article</button>
				</div>
			</form>
			<?php
		}
		echo '<h2><a href="#">Comments</a></h2>';
		if (!empty($comments))
		{
			foreach ($comments as $comment)
			{
				?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<b>Login :</b> <?php echo $comment['user']; ?><br>
						<b>Date :</b> <?php echo $comment['date']; ?>
					</div>
					<div class="panel-body">
						<?php echo $comment['text']; ?>
					</div>
				</div>
				<?php
			}
		}
		else
			echo '<p>There are no comments yet. You can leave a comment here !</p>';
		?>
		<div class="form-horizontal" style="margin-top:20px">
			<form method="post" action="<?php echo $action; ?>">
				<div class="form-group">
					<label for="comment" class="control-label col-sm-2">Leave a comment :</label>
					<div class="col-sm-10">
						<textarea class="form-control" rows="15" name="comment" id="comment"></textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary">Create comment</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>