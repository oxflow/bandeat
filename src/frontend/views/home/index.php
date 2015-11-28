<div class="container">
	<div class="jumbotron">
		<h2>Passer une commande</h2>
		<form method="post" action="/commander">
			<div class="form-group">
				<label for="nom">Nom : </label><input type="text" class="form-control" id="nom" name="nom">
			</div>
			<div class="form-group">
				<label for="restaurant">Nom du restaurant : </label><input type="text" class="form-control" id="restaurant" name="restaurant">
			</div>
			<div class="form-group">
				<label for="adresse">Adresse : </label><input type="text" id="adresse" class="form-control" name="adresse">
			</div>
			<div class="form-group">
				<label for="email">E-mail : </label><input type="email" id="email" class="form-control" name="email">
			</div>
			<div class="form-group">
				<label for="tel">T&eacute;l&eacute;phone : </label><input type="text" class="form-control" id="tel" name="tel">
			</div>
			<button type="submit" class="btn btn-default">Passer &agrave; l'&eacute;tape suivante</button>
		</form>
	</div>
</div>
