<?php
$no_error = true;
if(isset($_POST['pseudo']) && isset($_POST['pass']) && isset($_POST['pass_repeat']) && isset($_POST['email'])) {
	foreach($_POST as $index => $value) {
		if($index == 'pseudo' || $index == 'pass' || $index == 'pass_repeat' || $index == 'email') {
			$value = htmlspecialchars($value);
			if($no_error && !trim($value)) {
				$no_error = false;
				header('Location: inscription.php?missing_data=' . $index);				
			}
				
		}
	}
	
	if($no_error && $_POST['pass'] != $_POST['pass_repeat']) {
		$no_error = false;
		header('Location: inscription.php?wrong_data=different_psw');
	}
		
	
	if($no_error && !preg_match('#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-z]{2,4}$#i', $_POST['email'])) {
		$no_error = false;
		header('Location: inscription.php?wrong_data=email');
	}
		
	
	if($no_error && !preg_match('#^[a-zA-Z0-9_.&-]{8,16}$#', $_POST['pass'])) {
		$no_error = false;
		header('Location: inscription.php?wrong_data=psw');
	}
		
	if($no_error) {
		try {
			$bdd = new PDO('mysql:host=localhost;dbname=mybdd;charset=utf8', 'root', 'root',
					array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		} catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
		}
		
		$_POST['pass'] = sha1($_POST['pass']);
		$req = $bdd->prepare('SELECT pseudo from membres WHERE pseudo = ?');
		$req->execute(array($_POST['pseudo']));
		
		if($result = $req->fetch()) {
			$no_error = false;
			header('Location: inscription.php?wrong_data=pseudo');
		}
			
		if($no_error) {
			$req = $bdd->prepare('INSERT INTO membres(pseudo, pass, adresse_mail, date_inscription) VALUES(:pseudo, :pass, :adresse_mail,'
					. ' CURDATE())');
			$result = $req->execute(array('pseudo' => $_POST['pseudo'], 'pass' => $_POST['pass'], 'adresse_mail' => $_POST['email']));
			
			if($result) {
				session_start();
				foreach($_POST as $key => $value)
					if($key != 'pass_repeat')
						$_SESSION[$key] = $value;
					
				header('Location: connecter.php');
			}
			else
				echo 'Erreur technique';
		}
	}
	
}