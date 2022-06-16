<?php
session_start();
if((isset($_SESSION['pseudo']) && isset($_SESSION['pass'])) && (isset($_POST['adresse_mail']) || isset($_POST['pseudo']) 
		|| isset($_POST['pass_actuel']))) {
	$error = NULL;
	try {
		$bdd = new PDO('mysql:host=localhost;dbname=mybdd;charset=utf8', 'root', 'root',
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	} catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}

	foreach($_POST as $key => $value){
		$value = htmlspecialchars($value);
	}
		if(isset($_POST['pseudo']) && trim($_POST['pseudo']) && sha1(htmlspecialchars($_POST['pass_check'])) == $_SESSION['pass']) {
			$req = $bdd->prepare('SELECT pseudo FROM membres WHERE pseudo = ?');
			$req->execute(array($_POST['pseudo']));
			if($data = $req->fetch()) {
				$error = 'pseudo';
				$req->closeCursor();
			}
			else {
				$req->closeCursor();
				$req = $bdd->prepare('UPDATE membres SET pseudo = :newpseudo WHERE membre_id = :membre_id AND pseudo = :pseudo');
				$res = $req->execute(array('newpseudo' => $_POST['pseudo'], 'membre_id' => $_SESSION['id'], 'pseudo' => $_SESSION['pseudo']));
				$req->closeCursor();
				$_SESSION['pseudo'] = $_POST['pseudo'];
			}
		} elseif(isset($_POST['pass_actuel']) && trim($_POST['pass_actuel'])) {
			if(sha1($_POST['pass_actuel']) == $_SESSION['pass'] && strlen($_POST['pass_new']) >= 8
					&& $_POST['pass_new'] === $_POST['pass_repeat']) {
						$_POST['pass_new'] = sha1($_POST['pass_new']);
						$req = $bdd->prepare('UPDATE membres SET pass = :newpass WHERE membre_id = :membre_id AND pseudo = :pseudo');
						$req->execute(array('newpass' => $_POST['pass_new'], 'membre_id' => $_SESSION['id'], 'pseudo' => $_SESSION['pseudo']));
						$req->closeCursor();
						$_SESSION['pass'] = $_POST['pass_new'];
					} else {
						$error = 'pass';
					}
		} elseif(isset($_POST['adresse_mail']) && (trim($_POST['adresse_mail']) || trim($_POST['travail']) || trim($_POST['passions'])
				|| trim($_POST['ville']) || trim($_POST['date_naissance']) || isset($_POST['delete_all']))) {
					if(isset($_POST['delete_all'])) {
						$req = $bdd->prepare('UPDATE membres SET adresse_mail=NULL, travail=NULL, passions = NULL, ville = NULL, '
								. 'date_naissance = NULL WHERE membre_id = ?');
						$req->execute(array($_SESSION['id']));
						$req->closeCursor();
						$req = $bdd->prepare('DELETE FROM public_data WHERE mem_id = ?');
						$req->execute(array($_SESSION['id']));
						$req->closeCursor();
						$allowed = array('adresse_mail', 'travail', 'passions', 'ville', 'date_naissance');
						foreach($_POST as $key => $value) {
							if(in_array($key, $allowed))
								$_SESSION[$key] = '';
						}
					}
					else {
						$allowed = array('adresse_mail', 'travail', 'passions', 'ville', 'date_naissance');
						foreach($_POST as $key => $value) {
							if(in_array($key, $allowed) && trim($value)) {
								$_SESSION[$key] = $value;
								$req = $bdd->prepare('UPDATE membres SET ' . $key . ' = ? WHERE membre_id = ?');
								$res = $req->execute(array($value, $_SESSION['id']));
								$req->closeCursor();
							}
						}
						$checkAllowed = array('show_adresse_mail', 'show_ville', 'show_date_naissance', 'show_passions', 'show_travail');
						$showed = array();
						foreach($_POST as $key => $value) {
							if(in_array($key, $checkAllowed)) {
								$cle = substr($key, 5);
								array_push($showed, $cle);
								$req = $bdd->prepare('SELECT mem_id FROM public_data WHERE mem_id = ?');
								$req->execute(array($_SESSION['id']));
								if($data = $req->fetch()) {
									$req->closeCursor();
									$req = $bdd->prepare('UPDATE public_data SET ' . $cle . ' = 1 WHERE mem_id = ?');
									$res = $req->execute(array($_SESSION['id']));
									$req->closeCursor();
								}
								else {
									$req->closeCursor();
									$req = $bdd->prepare('INSERT INTO public_data (mem_id, '. $cle . ') VALUES(?, ?)');
									$req->execute(array($_SESSION['id'], 1));
									$req->closeCursor();
								}
							}
						}
						foreach($allowed as $value) {
							if(!in_array($value, $showed)) {
								$req = $bdd->prepare('SELECT mem_id FROM public_data WHERE mem_id = ?');
								$req->execute(array($_SESSION['id']));
								if($data = $req->fetch()) {
									$req->closeCursor();
									$req = $bdd->prepare('UPDATE public_data SET ' . $value . ' = 0 WHERE mem_id = ?');
									$req->execute(array($_SESSION['id']));
								}
								else {
									$req->closeCursor();
									$req = $bdd->prepare('INSERT INTO public_data (mem_id, ' . $value . ') VALUES(?, ?)');
									$req->execute(array($_SESSION['id'], 0));
									$req->closeCursor();
								}
							}
						}
						
					}
					
					
		}
		
		if($error == NULL)
			header('Location: modifier_profil.php');
		else
			header('Location: modifier_profil.php?change_data=' . $error . '&wrong_data=' . $error);
}


