<?php
session_start();
if((isset($_POST['pseudo']) && isset($_POST['pass']) && trim(htmlspecialchars($_POST['pseudo'])) && trim(htmlspecialchars($_POST['pass'])))
		|| (isset($_SESSION['pseudo']) && isset($_SESSION['pass'])) || (isset($_COOKIE['pseudo']) && isset($_COOKIE['pass']) 
				&& isset($_COOKIE['auto_connect']))) {	
	
	try {
		$bdd = new PDO('mysql:host=localhost;dbname=mybdd;charset=utf8', 'root', 'root',
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	} catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}
		
	$req = $bdd->prepare('SELECT membre_id, pseudo, pass, avatar_url FROM membres WHERE pseudo = ? AND pass = ?');
	if(isset($_POST['pseudo']) && isset($_POST['pass']) && trim(htmlspecialchars($_POST['pseudo'])) && trim(htmlspecialchars($_POST['pass']))) {
		$_POST['pseudo'] = htmlspecialchars($_POST['pseudo']);
		$_POST['pass'] = htmlspecialchars($_POST['pass']);
		if(strlen($_POST['pass']) <= 16)
			$_POST['pass'] = sha1($_POST['pass']);
		$req->execute(array($_POST['pseudo'], $_POST['pass']));
	}
	elseif(isset($_SESSION['pseudo']) && isset($_SESSION['pass']))
		$req->execute(array($_SESSION['pseudo'], $_SESSION['pass']));
	else
		$req->execute(array($_COOKIE['pseudo'], $_COOKIE['pass']));
	
		
	
	if($data = $req->fetch()) {
		session_start();
		if(isset($_POST['pseudo']) && trim($_POST['pseudo']) && isset($_POST['auto_connect']))
			setcookie('pseudo', $_POST['pseudo'], time() + 365 * 24 * 3600, null, null, false, true);
		if(isset($_POST['pass']) && trim($_POST['pass']) && isset($_POST['auto_connect']))
			setcookie('pass', $_POST['pass'], time() + 365*24*3600, null, null, false, true);
		if(isset($_POST['auto_connect'])) {
			$_POST['auto_connect'] = htmlspecialchars($_POST['auto_connect']);
			setcookie('auto_connect', $_POST['auto_connect'], time() + 365*24*3600, null, null, false, true);
			$_SESSION['auto_connect'] = $_POST['auto_connect'];
		} else {
			setcookie('auto_connect', '');
		}
		$_SESSION['id'] = $data['membre_id'];
		$_SESSION['pseudo'] = $data['pseudo'];
		$_SESSION['pass'] = $data['pass'];
		if($data['avatar_url'])
			$_SESSION['avatar'] = $data['avatar_url'];
		header('Location: espace_membre.php?pseudo=' . $_SESSION['pseudo'] . '&pass=' . $_SESSION['pass']);
	}
	else 
		header('Location: accueil.php?error=wrong_id');
	$req->closeCursor();
}
else 
	header('Location: accueil.php?error=no_id');
