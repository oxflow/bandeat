<?php
use Lib\Session;
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="<?php echo $layout_bootstrap_css; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $layout_style; ?>">
		<script src="<?php echo $layout_jquery; ?>"></script>
		<script src="<?php echo $layout_bootstrap_js; ?>"></script>
		<?php
		if (isset($layout_title))
			echo '<title>Band\'eat - ' . $layout_title . '</title>' . PHP_EOL;
		else
			echo '<title>Band\'eat</title>' . PHP_EOL;
		?>
	</head>
	<body>
		<nav id="myNavbar" class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="#">Blog</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="<?php echo $layout_home; ?>">Home</a></li>
						<?php
						if (Session::get('auth') === true)
						{
							?>
							<li class="dropdown">
								<a href="#" data-toggle="dropdown" class="dropdown-toggle">Account <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<?php
									if (Session::get('admin') > 0)
										echo '<li><a href="' . $layout_cr_article . '">Create new article</a></li>';
									?>
									<li><a href="<?php echo $layout_settings; ?>">Settings</a></li>
									<li><a href="<?php echo $layout_logout; ?>">Log out</a></li>
								</ul>
							</li>
							<?php
						}
						else
							echo '<li><a href="' . $layout_login . '">Sign in</a></li>' . PHP_EOL;
						?>
					</ul>
					<?php
					if (Session::get('login') !== false)
					{
						echo '<ul class="nav navbar-nav navbar-right">' . PHP_EOL;
						echo '<li><a href="#">You are logged as <b>' . Session::get('login') . '</b></a></li>' . PHP_EOL;
						if (Session::get('admin') > 2)
						{
							?>
							<li class="dropdown">
								<a href="#" data-toggle="dropdown" class="dropdown-toggle">Admin panel <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo $layout_m_users; ?>">Manage users</a></li>
								</ul>
							</li>
							<?php
						}
						echo '</ul>' . PHP_EOL;
					}
					?>
				</div>
			</div>
		</nav>
		<?php echo $content_layout; ?>
		<div class="foot">
			<p>Copyright &copy; 2015 <a href="#">www.bandeat.com</a></p>
		</div>
	</body>
</html>
