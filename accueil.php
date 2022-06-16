<?php
session_start();
//header('Content-type: text/html; charset=utf-8');
if(isset($_SESSION['pseudo']) && isset($_SESSION['pass']))
	header('Location: connecter.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<title>Accueil du site</title>
</head>
<body>
<h2>Super site</h2>
<?php 
if(isset($_GET['error'])) {
	$_GET['error'] = htmlspecialchars($_GET['error']);
	if($_GET['error'] == 'wrong_id')
		echo '<div>Vous avez entré un pseudo et/ou un mot de passe invalide. Veuillez recommencer votre saisie</div>';
	elseif($_GET['error'] == 'no_id')
		echo '<div>Données manquants (pseudo et/ou mot de passe)</div>';
}?>
<form method="post" action="connecter.php">
<label for="pseudo">Pseudo</label> : <input type="text" name="pseudo" id="pseudo" value="<?php

if(isset($_COOKIE['pseudo']) && isset($_COOKIE['auto_connect']))
	echo htmlspecialchars($_COOKIE['pseudo']); ?>" /><br />
<label for="pass">Mot de passe</label> : <input type="password" name="pass" id="pass" value="<?php 

if(isset($_COOKIE['pass']) && isset($_COOKIE['auto_connect']))
	echo htmlspecialchars($_COOKIE['pass']); ?>" /><br />
<input type="checkbox" name="auto_connect" id="auto_connect" <?php 

if(isset($_COOKIE['auto_connect']))
	echo 'checked'; ?> /> <label for="auto_connect">Se souvenir de moi</label><br />
<input type="submit" value="Se connecter" />
</form>
<div><a href="inscription.php">Pas encore inscrit ? C'est par ici !</a></div>
</body>
</html>