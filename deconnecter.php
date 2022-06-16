<?php
session_start();

if($_SESSION['pseudo'] == $_COOKIE['pseudo'] && !isset($_COOKIE['auto_connect'])) {
	setcookie('pseudo', '', time() + 1, null, null, false, true);
	setcookie('pass', '', time() + 1, null, null, false, true);
}
$_SESSION = array();
session_destroy();

	
header('Location: accueil.php');