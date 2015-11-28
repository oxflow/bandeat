<div class="container">
	<div class="jumbotron">
		<?php
			var_dump($_SESSION);
		?>
	</div>
</div>
<?php
if (isset($articles))
{
	$users = array();
	foreach ($articles as $article)
	{
		$id = $article->author();
		$content = $article->content();
		$url = $link->getUrl('Blog', 'read', array('id' => $article->id()));
		if (strlen($content) > 500)
		{
			$content = trim(substr($content, 0, 500));
			$content .= '...';
		}
		if (!isset($users[$id]))
			$users[$id] = $userManager->getUser($id);
		?>
		<div class="container">
			<div class="jumbotron">
				<h2><a href="<?php echo $url; ?>"><?php echo $article->title(); ?></a></h2>
				<h4>
					<em>Posted on <?php echo $article->timestamp(); ?> by <?php echo $users[$id]->login(); ?></em>
				</h4>
				<p>
					<?php echo $content; ?>
				</p>
			</div>
		</div>
		<?php
	}
}
?>