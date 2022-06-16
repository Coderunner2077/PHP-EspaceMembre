<?php
session_start();
if(isset($_SESSION['pseudo']) && isset($_SESSION['pass'])  && isset($_SESSION['id']) && isset($_FILES['avatar']) 
		&& $_FILES['avatar']['error'] == 0) {
	$no_error = true;
	foreach($_SESSION as $key => $value)
		$value = htmlspecialchars($value);
	if($_FILES['avatar']['size'] > 10000000) {
		$no_error = false;
		header('Location: change_avatar.php?error=size');
	} else {
		$_FILES['avatar']['name'] = htmlspecialchars($_FILES['avatar']['name']);
		$infofichier = pathinfo($_FILES['avatar']['name']);
		$extension = strtolower($infofichier['extension']);
		
		if($extension == 'jpg' || $extension == 'jpeg')
			$image = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
		elseif($extension == 'png')
			$image = imagecreatefrompng($_FILES['avatar']['tmp_name']);
		elseif($extension == 'gif')
			$image = imagecreatefromgif($_FILES['avatar']['tmp_name']);
		else {
			$no_error = false;
			echo 'Format non supporté';
			header('Location: change_avatar.php?error=format');
		}
			
		$newImage = imagecreatetruecolor(100, 140);
			
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, 100, 140, imagesx($image), imagesy($image));
		if(isset($_SESSION['avatar'])) {
			$exten = pathinfo($_SESSION['avatar']);
			$exten = strtolower($exten['extension']);
			if($exten != $extension) 
				unlink('avatars/' . $_SESSION['avatar']);
			setcookie('avatar', '', time() + 1, null, null, false, true);
		}
		if($no_error && isset($_SESSION['id'])) {
			if($extension == 'jpg' || $extension == 'jpeg')
				imagejpeg($newImage, 'avatars/' . $_SESSION['id'] . '.' . $extension);
			elseif($extension == 'png')
			imagepng($newImage, 'avatars/' . $_SESSION['id'] . '.' . $extension);
			elseif($extension == 'gif')
			imagegif($newImage, 'avatars/' . $_SESSION['id'] . '.' . $extension);
			else {
				echo 'Erreur de création de la miniature';
				$no_error = false;
				header('Location: change_avatar.php?error=saving');
			}
		}	
		if($no_error) {
			$_SESSION['avatar'] = $_SESSION['id'] . '.' . $extension;
			
			try {
				$bdd = new PDO('mysql:host=localhost;dbname=mybdd;charset=utf8', 'root', 'root', 
						array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			} catch (Exception $e) {
				die('Erreur : ' . $e->getMessage());
			}
			$req = $bdd->prepare('UPDATE membres SET avatar_url = ? WHERE membre_id = ?');
			$req->execute(array($_SESSION['avatar'], $_SESSION['id']));
			$req->closeCursor();
			header('Location: espace_membre.php');
		}
			
	}
} else 
	header('Location: change_avatar.php?error=nofile');