<div class="container">
	<div class="jumbotron">
		<?php
			if ($request === "POST" AND isset($error))
			{
				echo '<h3>An error occured. Please try again</h3>' . PHP_EOL;
				echo '<p>' . $error . '</p>' . PHP_EOL;
			}
			else if ($request === "POST")
			{
				echo '<h3>You have signed in !</h3>' . PHP_EOL;
			}
			else
			{
				?>
				<div class="bs-example">
					<form action="<?php echo $action; ?>" method="post">
						<p><a href="<?php echo $u_signup; ?>">Don't have an account yet ? Sign up.</a></p>
						<div class="form-group">
							<label for="inputLogin">Login</label>
							<input type="text" name="login" class="form-control" id="inputLogin" placeholder="Login or e-mail adress">
						</div>
						<div class="form-group">
							<label for="inputPassword">Password</label>
							<input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
						</div>
						<div class="checkbox">
							<label><input type="checkbox" name="remember" checked> Keep me logged in</label>
						</div>
						<button type="submit" class="btn btn-primary">Sign in</button>
					</form>
				</div>
				<?php
			}
		?>
	</div>
</div>