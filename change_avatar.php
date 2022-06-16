<?php
session_start();
if(isset($_SESSION['pseudo']) && isset($_SESSION['pass'])) {
	?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Changer d'avatar</title>
</head>
<body>
<h2>Changer d'avatar</h2>
<form method="post" action="avatar.php" id="myForm" enctype="multipart/form-data">
<input type="file" name="avatar" id="avatar" /><br />
<input type="submit" value="Valider" />
</form>
<div id="showAvatar"></div>
<div>
<?php if(isset($_GET['error'])) {
	$_GET['error'] = htmlspecialchars($_GET['error']);
	switch($_GET['error']) {
		case 'nofile':
			echo 'Vous n\'avez chargé aucun fichier';
			break;
		case 'size':
			echo 'Veuillez choisir un fichier dont la taille est inférieure à 10 Mo';
			break;
		case 'format':
			echo 'Veuillez choisir un JPEG, PNG ou GIF';
			break;
		case 'saving':
			echo 'Erreur d\'enregistrement du fichier';
			break;
		default:
			echo 'Faut pas jouer avec l\'URL';
	}
	
}
?>
</div>

<script>
	(function(){
		function showPicture(file) {
			var reader = new FileReader();
			reader.addEventListener('load', function() {
				document.querySelector('#showAvatar').innerHTML = '';
				var image = new Image();
				image.width = 100;
				image.height = 140;
				image.src = reader.result;
				document.querySelector('#showAvatar').appendChild(image);
			});

			reader.readAsDataURL(file);
		}

		var avatar = document.querySelector('#avatar'),
			allowedTypes = ['jpg', 'png', 'jpeg'];
		
		avatar.addEventListener('change', function() {
			
			extension = avatar.files[0].name.split('.');
			extension = extension[extension.length - 1].toLowerCase();
			if(~allowedTypes.indexOf(extension))
				showPicture(avatar.files[0]);

				
		});
		
		var myForm = document.querySelector('#myForm');
		myForm.addEventListener('submit', function(e) {
			if(!document.querySelector('#showAvatar').hasChildNodes()) {
				e.preventDefault();
				alert('Veuillez choisir un fichier image de format JPEG ou PNG');
			}
		});
	})();
</script>
</body>
</html>

<?php 
} else 
	header('Location: accueil.php');
?>
