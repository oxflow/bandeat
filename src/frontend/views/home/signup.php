<div class="container">
	<div class="jumbotron">
		<?php
			if ($request === "POST" AND isset($success))
			{
				echo '<h3>You have signed up !</h3>' . PHP_EOL;
				echo '<p><b>Your password is : ' . $pass . '</b><br>' . PHP_EOL;
				echo $success . '</p>' . PHP_EOL;
			}
			else if ($request === "POST" AND isset($error))
			{
				echo '<h3>An error occured : ' . $error . '</h3>' . PHP_EOL;
			}
			else
			{
				?>
				<div class="bs-example">
					<form action="<?php echo $action; ?>" method="post">
						<div class="form-group">
							<label for="inputLogin">Login</label>
							<input type="text" name="login" class="form-control" id="inputLogin" placeholder="Login">
						</div>
						<div class="form-group">
							<label for="inputEmail">E-mail</label>
							<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email">
						</div>
						<div class="form-group">
							<label for="captcha">Combien font six fois sept ?</label>
							<input type="text" name="captcha" class="form-control" id="captcha">
						</div>
						<button type="submit" class="btn btn-primary">Sign up</button>
					</form>
				</div>
				<?php
			}
		?>
	</div>
</div>