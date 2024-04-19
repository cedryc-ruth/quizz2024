<?php
require 'config.php';

//Déclaration des variables, constantes et fonctions
$title = 'Quizz :: Récupération de mot de passe (1/2)';
$message = "";

function checkEmail($dirtyEmail) {
	//Se connecter au serveur de DB
	$link = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);	//var_dump($link);
	
	//Nettoyer les données entrantes
	$email = mysqli_real_escape_string($link,$dirtyEmail);
	
	//Préparer la requête
	$query = "SELECT email FROM `users` WHERE `email`='$email'";	//var_dump($query);
		
	//Envoyer la requête et récupérer le résultat
	$result = mysqli_query($link,$query);
	
	//Analyser le résultat
	$numRows = mysqli_num_rows($result);
	
	//Se déconnecter du serveur de DB
	mysqli_close($link);
	
	return $numRows>0;
}

if(isset($_GET['btNext'])){
	if(!empty($_GET['email'])){
		//Si le mail est présent dans la DB, envoyer le lien de modification
		//sinon refuser
		if(checkEmail($_GET['email'])) {
			//Envoi du mail de modification
			$to = $_GET['email'];
			$subject = 'Modification du mot de passe';
			$message = '<html><head><title>Modificationn mot de passe</title></head>
				<body>
				Vous pouvez modifier votre mot de passe en utilisant le 
				<a href="http://localhost/quizz/resetPassword.php?email='.password_hash($_GET['email'],PASSWORD_BCRYPT).'">lien suivant</a>.
				</body>
			</html>';
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[] = 'From: contact@quizz.be';
			
			mail($to, $subject, $message, implode("\r\n", $headers));
		} else {
			$message = "Cet email n'existe pas!";
		}
	} else {
		$message = "Veuillez entrer votre email!";
	}
}



include 'inc/header.php';
?>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
	<div>
		<label for="email">Votre email:</label>
		<input type="email" name="email" id="email">
	</div>
	<button name="btNext">Suivant</button>
</form>
<div><?= $message ?></div>
<?php
include 'inc/footer.php';
?>