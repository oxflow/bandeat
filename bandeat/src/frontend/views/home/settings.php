<?php
if (isset($success) OR isset($error))
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
else
{
	?>
	<div class="container">
		<div class="jumbotron">
			<form method="post" action="<?php echo $action; ?>" class="form-horizontal">
				<div class="form-group">
					<label for="inputPassword" class="col-sm-3 control-label">Type your password first :</label>
					<div class="col-sm-9">
						<input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail" class="col-sm-3 control-label">E-mail :</label>
					<div class="col-sm-9">
						<input type="text" name="email" class="form-control" id="inputEmail" value="<?php echo $email; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="inputLogin" class="col-sm-3 control-label">Login :</label>
					<div class="col-sm-9">
						<input type="text" name="login" class="form-control" id="inputLogin" value="<?php echo $login; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="inputChangePassword" class="col-sm-3 control-label">Change your password :</label>
					<div class="col-sm-9">
						<input type="password" name="change_password" class="form-control" id="inputChangePassword" placeholder="Change Password">
					</div>
				</div>
				<div class="form-group">
					<label for="inputConfirmPassword" class="col-sm-3 control-label">Confirmation password :</label>
					<div class="col-sm-9">
						<input type="password" name="confirm_password" class="form-control" id="inputConfirmPassword" placeholder="Confirmation Password">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="submit" class="btn btn-primary">Update settings</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php
}