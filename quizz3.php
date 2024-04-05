<?php
if(!empty($_COOKIE['PHPSESSID'])) {
	$id = $_COOKIE['PHPSESSID'];
} elseif(!empty($_GET['PHPSESSID'])) {
	$id = $_GET['PHPSESSID'];
} elseif(!empty($_POST['PHPSESSID'])) {
	$id = $_POST['PHPSESSID'];
} else {
	$id = null;
}

session_id($id);

session_start();

//Sécurisation (Authorization)
if(empty($_SESSION['login'])) {	//Si pas connecté
	header('Status: 302 Temporary');
	header('location: login.php');
	exit;
}

//Déclaration des variables, constantes et fonctions
$questions = file('data/questions.txt',FILE_IGNORE_NEW_LINES);
$reponses = file('data/reponses.txt',FILE_IGNORE_NEW_LINES);

$resultat = "";

if(isset($_SESSION['score'])) {
	$score = $_SESSION['score'];
} else {
	$score = 0;
}

if(isset($_SESSION['nroQuestion'])) {
	$nroQuestion = $_SESSION['nroQuestion'];
} else {
	$nroQuestion = 0;
}
//var_dump($nroQuestion);

$success = 'started';

//Traitement des commandes
if(isset($_POST['btSend'])) {
	//Valider les données
	if(!empty($_POST['reponse'])) {	//var_dump('OK');
		//Réponse correcte ?
		//var_dump($reponses[$nroQuestion]);
		if(strtolower(trim($_POST['reponse']))==$reponses[$nroQuestion]) {
			$nroQuestion++;
			$score += 2;
			
			if($nroQuestion<sizeof($questions)) {
				$resultat = "Bravo!";
				$success = 'good';
			} else {
				$resultat = "Félicitations!";
				$success = 'finished';
				
				//Sauver mon score dans le palmarès
				$data = [
					$_SESSION['login'],
					$score,
					time()		//date('d/m/Y G:i')
				];
				$data = implode(';',$data)."\n";
				
				file_put_contents('data/palmares.csv',$data,FILE_APPEND);
			}
		} else {
			$score--;
			$success = 'wrong';
			
			$resultat = "Dommage...";
		}
		
		//Sauver le score
		if($success != 'finished') {
			$_SESSION['score'] = $score;
			$_SESSION['nroQuestion'] = $nroQuestion;
		} else {
			$_SESSION['score'] = 0;
			$_SESSION['nroQuestion'] = 0;
		}
	} else {	//var_dump('PAS OK');
		$resultat = "Veuillez fournir une réponse.";
	}
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Quizz</title>
<style>
.userDetails {
	position:absolute;
	top:10px;
	right:10px;
	border:1px solid gray;
	background-color: silver;
	padding: 10px;
	border-radius: 10px;
}
.userDetails p { margin:0; font-style: italic; }
</style>
</head>
<body>
<h1>Quizz</h1>

<div class="userDetails">
	<p><?= $_SESSION['login'] ?></p>
	<form action="login.php" method="post">
		<button name="btLogout">Se déconnecter</button>
	</form>
</div>

<?php if(!in_array($success,['good','finished'])) { ?>
	<p><?= $questions[$nroQuestion] ?></p>

	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
		<fieldset>
			<label for="reponse">Réponse: </label>
			<input type="text" name="reponse" id="reponse" required>
			<input type="hidden" name="PHPSESSID" id="phpsessid" value="<?= session_id() ?>">
		</fieldset>
		<button name="btSend">Envoyer</button>
	</form>
<?php } ?>

<div id="resultat">
	<p>
		<?= $resultat ?>
	
	<?php if($success=='good') { ?>
		<a href="<?= $_SERVER['PHP_SELF'] ?>?PHPSESSID=<?= session_id() ?>">Question suivante</a>
	<?php } ?>
	</p>

<p>Votre score est de <?= $score ?> points.</p>

<?php if($success == 'finished') { ?>	
	<p><a href="<?= $_SERVER['PHP_SELF'] ?>">Recommencer le quizz</a></p>
<?php } ?>
</div>
</body>
</html>