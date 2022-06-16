<?php
session_start();
$error = NULL;

if(isset($_SESSION['pseudo']) && isset($_SESSION['pass'])) {
	if(!isset($_GET['change_data'])) {
	?>
<p><a href="modifier_profil.php?change_data=private" title="Modifier les données personnelles">Ajouter ou Modifier les infos personnelles</a></p>
<p><a href="modifier_profil.php?change_data=pseudo">Modifier le pseudo</a></p>
<p><a href="modifier_profil.php?change_data=pass">Modifier le mot de passe</a></p>
<p><a href="espace_membre.php">Retourner dans l'espace perso</a></p>
	<?php 
	} elseif(isset($_GET['change_data']) && htmlspecialchars($_GET['change_data']) == 'private') {
		?>
		<form method="post" action="modify_model.php">
		<table><thead><td>Champ</td><td>Rendre public</td><td>Valeur</td></thead></table>
		<label for="adresse_mail">Adresse e-mail</label> : <input type="checkbox" name="show_adresse_mail" /><input type="email" name="adresse_mail" id="adresse_mail" /><br />
		<label for="date_naissance">Date de naissance</label> : <input type="checkbox" name="show_date_naissance" /><input type="date" name="date_naissance" id="date_naissance" /><br />
		<label for="ville">Ville</label> : <input type="checkbox" name="show_ville" /><input type="text" name="ville" id="ville" /><br />
		<label for="travail">Situation professionnelle</label> : <input type="checkbox" name="show_travail" /><input type="text" name="travail" id="travail" /><br />
		<label for="passions">Passions et hobbies</label> : <input type="checkbox" name="show_passions" /><textarea name="passions" id="passions"></textarea><br />
		<input type="checkbox" name="delete_all" id="delete_all" /><label for="delete_all">Tout supprimer</label> 
		<input type="submit" value="Appliquer" /><input type="button" name="annuler" value="Annuler" />
		</form>
		<?php 
	} elseif(isset($_GET['change_data']) && htmlspecialchars($_GET['change_data']) == 'pseudo') {
		?>
		<form method="post" action="modify_model.php">
		<label for="pseudo">Nouveau pseudo</label> : <input type="text" name="pseudo" /><br />
		<label for="pass_check">Mot de passe (pour vérifier)</label> : <input type="password" name="pass_check" id="pass_check" /><br />
		<input type="submit" value="Appliquer"><input type="button" name="annuler" value="Annuler" />
		</form>
		<?php 
		if(isset($_GET['wrong_data']) && htmlspecialchars($_GET['wrong_data']) == 'pseudo')
			echo '<div>Ce pseudo existe déjà, veuillez en choisir un autre</div>';
	} elseif(isset($_GET['change_data']) && htmlspecialchars($_GET['change_data']) == 'pass') {
		?>
		<form method="post" action="modify_model.php">
		<label>Mot de passe actuel</label> : <input type="password" name="pass_actuel" /><br />
		<label>Nouveau mot de passe</label> : <input type="password" name="pass_new" /><br />
		<label>Répeter la saisie</label> : <input type="password" name="pass_repeat" /><br /><br />
		<input type="submit" value="Appliquer" /><input type="button" name="annuler" value = "Annuler" />
		</form>
		<?php 
		if(isset($_GET['wrong_data']) && htmlspecialchars($_GET['wrong_data']) == 'pass')
			echo '<div>Saisie incorrecte</div>';
	} 
	
}