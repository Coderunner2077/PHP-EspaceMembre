<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<title>Inscription</title>
</head>
<body>
<h2>Inscription sur le site</h2>
<h4>Veuillez remplir les champs suivants</h4>
<form method="post" action="registrate_member.php">
<label for="pseudo">Pseudo</label> : <input type="text" name="pseudo" id="pseudo" /><br />
<label for="pass">Mot de passe</label> : <input type="password" name="pass" id="pass" /><br />
<label for="pass_repeat">Resaisir mot de passe</label> : <input type="password" name="pass_repeat" id="pass_repeat" /><br />
<label for="email">Adresse e-mail</label> : <input type="email" name="email" id="email" /> <br />
<input type="submit" value="S'inscrire" />
</form>
<div>
<?php 
if(isset($_GET['missing_data'])) {
	$_GET['missing_data'] = htmlspecialchars($_GET['missing_data']);
	echo 'Donnée manquante : ' . $_GET['missing_data'];
}
elseif(isset($_GET['wrong_data'])) {
	$_GET['wrong_data'] = htmlspecialchars($_GET['wrong_data']);
	switch($_GET['wrong_data']) {
		case 'different_psw':
			echo 'Les mots de passe ne sont pas identiques';
			break;
		case 'email': 
			echo 'L\'adresse e-mail n\'est pas correcte';
			break;
		case 'psw':
			echo 'Le mot de passe doit comprendre entre 8 et 16 caractères';
			break;
		case 'pseudo':
			echo 'Pseudo déjà occupé. En choisir un autre.';
			break;
		default:
			echo 'Erreur inconnue';
	}
}
?>
</div>
</body>
</html>