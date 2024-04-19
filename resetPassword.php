<?php
require 'config.php';

//Déclaration des variables, constantes et fonctions
$title = 'Quizz :: Récupération de mot de passe (2/2)';
$message = "";

function updateUser($dirtyEmail, $password) {
	//Se connecter au serveur de DB
	$link = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);	//var_dump($link);
										//var_dump($password);die;
	//Nettoyer les données entrantes
	$email = mysqli_real_escape_string($link,$dirtyEmail);
	$password = password_hash($password, PASSWORD_BCRYPT);
	
	//Préparer la requête
	$query = "UPDATE `users` SET `password` = '$password' WHERE `users`.`email` = '$email'";	//var_dump($query);die;
		
	//Envoyer la requête et récupérer le résultat
	$result = mysqli_query($link,$query);
	
	//Analyser le résultat
	if($result && mysqli_affected_rows($link)>0) {
		//Envoi du mail de confirmation
		$to = $email;
		$subject = 'Modification du mot de passe';
		$message = 'Votre mot de passe a bien été réinitialisé.';
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = 'From: contact@quizz.be';
		
		mail($to, $subject, $message, implode("\r\n", $headers));
	} else {
		$message = "Une erreur s'est produite lors de l'insertion...";
	}
	
	//Se déconnecter du serveur de DB
	mysqli_close($link);
}


if(isset($_POST['btUpdate'])){
	if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confPassword'])) {
		//L'email donné (formulaire) correspond au hashage de l'email dans l'URL
		if(password_verify($_POST['email'], $_POST['email_token'])) {
			if($_POST['password']==$_POST['confPassword']) {
				//Modifier le mot de passe de l'utilisateur dans la base de données
				updateUser($_POST['email'],$_POST['password']);
			} else {
				$message = "Les mots de passe ne correspondent pas!";
			}
		} else {
			$message = "Accès interdit! (Token invalide)";
		}
	} else {
		$message = "Veuillez remplir tous les champs obligatoires!";
	}
}


include 'inc/header.php';
?>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
	<div>
		<label for="email">Votre email:</label>
		<input type="email" name="email" id="email">
	</div>
	
	<div>
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" required>
		</div>
		
		<div>
			<label for="confPassword">Confirm Password:</label>
			<input type="password" name="confPassword" id="confPassword" required>
		</div>
		<input type="hidden" name="email_token" id="email_token" value="<?= $_GET['email'] ?? '' ?>">
	<button name="btUpdate">Modifier</button>
</form>
<div><?= $message ?></div>
<?php
include 'inc/footer.php';
?>