<?php
$admin_txt = array('Member', 'Blogger', 'Moderator', 'Administrator', 'Founder');
if (isset($error) OR isset($success))
{
	?>
	<div class="container">
		<div class="jumbotron">
			<?php
				if (isset($error))
					echo '<h2>An error occurred : ' . $error . '</h2>';
				else if (isset($success))
					echo '<h2>' . $success . '</h2>';
			?>
		</div>
	</div>
	<?php
}
else if (isset($id))
{
	?>
	<div class="container">
		<div class="jumbotron">
			<div class="alert alert-danger" role="alert">
				<b>Warning !</b> If you update a user without changing his password, it will be set to null.
			</div>
			<h2>User #<?php echo $edit->id(); ?></h2>
			<p>Registered since <?php echo $edit->timestamp(); ?></p>
			<div class="bs-example">
				<form action="<?php echo $action; ?>" method="post">
					<input type="hidden" name="id" value="<?php echo $edit->id(); ?>">
					<div class="form-group">
						<label for="inputLogin">Login</label>
						<input type="text" name="login" class="form-control" id="inputLogin" value="<?php echo $edit->login(); ?>">
					</div>
					<div class="form-group">
						<label for="inputEmail">E-mail</label>
						<input type="email" name="email" class="form-control" id="inputEmail" value="<?php echo $edit->email(); ?>">
					</div>
					<div class="form-group">
						<label for="inputPassword">Change password</label>
						<input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
					</div>
					<div class="form-group">
						<label for="inputAdmin">Admin level</label>
						<select name="admin" id="inputAdmin" class="form-control">
							<?php
							for ($i = 0; $i <= $user_admin; $i++)
								echo '<option' . $admin[$i] . ' value="' . $i . '">' . $admin_txt[$i] . '</option>' . PHP_EOL;
							?>
						</select>
					</div>
					<button type="submit" class="btn btn-primary">Edit user</button>
				</form>
			</div>
		</div>
	</div>
	<?php
}
?>
<div class="container">
	<div class="jumbotron">
		<table class="table table-striped">
			<thead>
				<tr>
					<td>#</td><td>Login</td><td>E-mail</td><td>Register</td><td>Admin</td><td>Delete</td>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($users as $k => $user)
				{
					$delete = $link->getUrl('Admin', 'deleteUser', array('id' => $user->id()));
					echo '<tr>' . PHP_EOL;
					if ($user_admin == 4 OR $user_admin > $user->admin())
					{
						$url = $link->getUrl('Admin', 'users', array('id' => $user->id()));
						echo '<td><a href="' . $url . '">' . $user->id() . '</a></td><td>' . $user->login() . '</td><td>' . $user->email() . '</td>';
					}
					else
						echo '<td>' . $user->id() . '</a></td><td>' . $user->login() . '</td><td>' . $user->email() . '</td>';
					echo '<td>' . $user->timestamp() . '</td><td>' . $admin_txt[$user->admin()] . '</td>';
					if ($user_admin == 4 OR $user_admin > $user->admin())
					{
						echo '<td><form method="post" action="' . $delete . '"><button type="submit" class="btn btn-default btn-xs">';
						echo '<span class="glyphicon glyphicon-remove"></span></button></form></td>' . PHP_EOL;
					}
					else
						echo '<td></td>' . PHP_EOL;
					echo '</tr>' . PHP_EOL;
				}
				?>
			</tbody>
		</table>
	</div>
</div>