<?php
session_start();

//Sécurisation (Authorization)
if(!empty($_SESSION['login'])) {	//Si déjà connecté!
	if(isset($_POST['btLogout'])) {
		session_unset();
		session_destroy();
	} else {	
		header('Status: 302 Temporary');
		header('location: quizz3.php');
		exit;
	}
}

$message = '';

function verify($login, $pwd) {
	$loginSecret = 'bob';
	$pwdSecret = 'epfc';

	return $login==$loginSecret && $pwd==$pwdSecret;
}

if(isset($_POST['btLogin'])) {
	//Vérification des champs obligatoires
	if(!empty($_POST['login']) && !empty($_POST['password'])) {
		//Traitement des données
		if(verify($_POST['login'],$_POST['password'])) {
			$_SESSION['login'] = $_POST['login'];
			
			//Redirection au quiz
			header('Status: 302 Temporary');
			header('location: quizz3.php');
			exit;
		} else {
			$message = 'Les identifiants ne sont pas corrects!';
		}
	} else {
		$message = 'Veuillez remplir tous les champs!';
	}
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Quizz - Connexion</title>
</head>
<body>
<h1>Quizz</h1>
<h2>Formulaire de connexion</h2>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset>
		<div>
			<label for="login">Login:</label>
			<input type="text" name="login" id="login">
		</div>
		
		<div>
			<label for="password">Password:</label>
			<input type="password" name="password" id="password">
		</div>
	</fieldset>
	<button name="btLogin">Se connecter</button>
</form>
<div><?= $message ?></div>
</body>
</html>