<?php
require 'config.php';

//Déclaration des variables, constantes et fonctions
$title = 'Quizz :: Inscription';
$message = "";

function loginExists($dirtyLogin) {
	//Se connecter au serveur de DB
	$link = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);	//var_dump($link);
	
	//Nettoyer les données entrantes
	$login = mysqli_real_escape_string($link,$dirtyLogin);
	
	//Préparer la requête
	$query = "SELECT login FROM `users` WHERE `login`='$login'";	//var_dump($query);
		
	//Envoyer la requête et récupérer le résultat
	$result = mysqli_query($link,$query);
	
	//Analyser le résultat
	$numRows = mysqli_num_rows($result);
	
	//Se déconnecter du serveur de DB
	mysqli_close($link);
	
	return $numRows>0;
}

function registerUser($dirtyEmail, $dirtyLogin, $password) {
	//Se connecter au serveur de DB
	$link = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);	//var_dump($link);
	
	//Nettoyer les données entrantes
	$email = mysqli_real_escape_string($link,$dirtyEmail);
	$login = mysqli_real_escape_string($link,$dirtyLogin);
	$password = password_hash($password, PASSWORD_BCRYPT);
	
	//Préparer la requête
	$query = "INSERT INTO `users` (`id`, `email`, `login`, `password`, `created_at`) 
		VALUES (NULL, '$email', '$login', '$password', '".date('Y-m-d H:i:s')."')";	//var_dump($query);
		
	//Envoyer la requête et récupérer le résultat
	$result = mysqli_query($link,$query);
	
	//Analyser le résultat
	if($result && mysqli_affected_rows($link)>0) {
		//Envoi du mail de confirmation
		$to = $email;
		$subject = 'Inscription au site du Quizz';
		$message = 'Votre inscription a bien été acceptée.';
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = 'From: contact@quizz.be';
		
		mail($to, $subject, $message, implode("\r\n", $headers));
		
		//Solution 1: notifier et laisser l'utilisateur se connecter lui-même
		//$message = "Inscription réussie. Bienvenue.";
		
		//Solution 2: rediriger l'utilisateur ver la page de connexion
		header('Status: 302 Temporary');
		header('Location: login.php');
		exit;
		
		//Solution 3: connecter l'utilisateur automatiquement
		/*session_start();
		$_SESSION['login'] = $login;
		
			//Redirection au quiz
		header('Status: 302 Temporary');
		header('location: quizz4.php');
		exit;*/
	} else {
		$message = "Une erreur s'est produite lors de l'insertion...";
	}
	
	//Se déconnecter du serveur de DB
	mysqli_close($link);
}

//Traitement des commandes
if(isset($_POST['btSignin'])) {
	//Validation 0: champs obligatoires
	if(!empty($_POST['email']) && !empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['confPassword'])) {
		//Validation 1: valeur des champs
		if(!loginExists($_POST['login'])) {
			if($_POST['password']==$_POST['confPassword']) {
			//Inscrire l'utilisateur dans la base de données
				registerUser($_POST['email'],$_POST['login'],$_POST['password']);
			} else {
				$message = "Les mots de passe ne correspondent pas!";
			}
		} else {
			$message = "Ce login est déjà utlisé!";
		}
	} else {
		$message = "Veuillez remplir tous les champs obligatoires!";
	}
}

include 'inc/header.php';
?>

<p>Déjà membre ? Cliquez ici pour vous <a href="login.php">connecter</a>.</p>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" novalidate>
	<fieldset>
		<div>
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" required>
		</div>
		<div>
			<label for="login">Login:</label>
			<input type="text" name="login" id="login" required>
		</div>
		
		<div>
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" required>
		</div>
		
		<div>
			<label for="confPassword">Confirm Password:</label>
			<input type="password" name="confPassword" id="confPassword" required>
		</div>
	</fieldset>
	<button name="btSignin">S'Inscrire</button>
</form>
<div><?= $message ?></div>
<?php
include 'inc/footer.php';
?>