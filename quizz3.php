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

//Déclaration des variables, constantes et fonctions
$questions = [
	"Quel est la couleur du cheval blanc de Napoléon?",
	"Quel est votre jour préféré?",
	"Quel est votre cusine préférée?",
];
$reponses = [
	"blanc",
	"vendredi",
	"thaï",
];
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
</head>
<body>
<h1>Quizz</h1>

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