<?php 
session_start();
if(!isset($_SESSION['pseudo']) || !isset($_SESSION['pass']))
	header('Location: accueil.php');
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Espace membre</title>
</head>
<body>
<h2>Espace membre</h2>
<?php if(isset($_SESSION['avatar'])) {
	echo '<p><img src="avatars/' . $_SESSION['avatar'] . '" alt="Avatar" /></p>';
	echo '<p><a href="change_avatar.php" title="Changer d\'avatar">Changer d\'avatar</a></p>';
}
else 
	echo '<p><a href="change_avatar.php" title="Choisir un avatar">Choisir un avatar</a></p>';
?>
<h3>Hello <?php echo $_SESSION['pseudo']; ?></h3>
<p>Informations personnelles</p>
<?php
if(isset($_SESSION['data_naissance']))
	echo '<p>Date de naissance : ' . $_SESSION['date_naissance'] . '</p>';
if(isset($_SESSION['adresse_mail']))
	echo '<p>Adresse e-mail : ' . $_SESSION['adresse_mail'] . '</p>';
if(isset($_SESSION['ville'])) 
	echo '<p>Ville : ' . $_SESSION['ville'] . '</p>';
if(isset($_SESSION['travail']))
	echo '<p>Situation professionnelle : ' . $_SESSION['travail'] . '</p>';
if(isset($_SESSION['passions']))
	echo '<p>Passions : ' . $_SESSION['passions'] . '</p>';
?>
<p><a href="modifier_profil.php" title="Modifier les données privées">Modifier des informations</a>
<p><a href="deconnecter.php" title="Déconnexion">Se déconnecter</a></p>
</body>
</html>