<?php
require 'config.php';

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

$title = 'Connexion';
$message = '';

function verify($login, $pwd) {
	//Se connecter à la DB
	$link = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
	
	//Récupérer le password du user auquel correspond le login
		//Préparer la requête
			//Nettoyer les données entrantes (risque d'injection SQL)
	$login = mysqli_real_escape_string($link, $login);
		
	$query = "SELECT password FROM users WHERE login='$login'";
		
		//Envoyer et Récupérer le résultat
	$result = mysqli_query($link, $query);	//var_dump($result); die;
		
		//Extraire les données
	$data = mysqli_fetch_assoc($result);	//var_dump($data); die;
		
		//Libérer le résultat
	mysqli_free_result($result);
		
		//Se déconnecter
	mysqli_close($link);

	if(empty($data)) {	//L'utilisateur n'est pas trouvé dans la DB
		return false;
	}
	
	//Vérifier le hashage du password avec le mot de passe donnée par l'utilisateur
	return password_verify($pwd, $data['password']);
}

if(isset($_POST['btLogin'])) {
	//Vérification des champs obligatoires
	if(!empty($_POST['login']) && !empty($_POST['password'])) {
		//Traitement des données
		if(verify($_POST['login'],$_POST['password'])) {
			$_SESSION['login'] = $_POST['login'];
			
			//Redirection au quiz
			header('Status: 302 Temporary');
			header('location: quizz4.php');
			exit;
		} else {
			$message = 'Les identifiants ne sont pas corrects!';
		}
	} else {
		$message = 'Veuillez remplir tous les champs!';
	}
}
?>
<?php include 'inc/header.php'; ?>
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
<p>Pas encore membre ? Cliquez ici pour vous <a href="signin.php">inscrire</a>.</p>
<div><?= $message ?></div>

<?php include 'inc/footer.php'; ?>