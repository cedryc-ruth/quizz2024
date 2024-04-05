<?php
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

if(!empty($_COOKIE['score'])) {
	$score = $_COOKIE['score'];
} else {
	$score = 0;
}

if(!empty($_GET['nroQuestion'])) {
	$nroQuestion = $_GET['nroQuestion'];
} elseif(!empty($_POST['nroQuestion'])) {
	$nroQuestion = $_POST['nroQuestion'];
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
		setcookie('score', $score, time()+84600);
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
			<input type="hidden" name="nroQuestion" id="nroQuestion" value="<?= $nroQuestion ?>">
		</fieldset>
		<button name="btSend">Envoyer</button>
	</form>
<?php } ?>

<div id="resultat">
	<p>
		<?= $resultat ?>
	
	<?php if($success=='good') { ?>
		<a href="<?= $_SERVER['PHP_SELF'] ?>?nroQuestion=<?= $nroQuestion ?>">Question suivante</a>
	<?php } ?>
	</p>

<?php //if($success == 'finished') { ?>	
	<p>Votre score est de <?= $score ?> points.</p>
<?php //} ?>
</div>
</body>
</html>