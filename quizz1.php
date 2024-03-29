<?php
//Sécurisation (Authorization)

//Déclaration des variables, constantes et fonctions
$question = "Quel est la couleur du cheval blanc de Napoléon?";
$reponse = "blanc";
$resultat = "";

//Traitement des commandes
if(isset($_GET['btSend'])) {
	//Valider les données
	if(!empty($_GET['reponse'])) {	//var_dump('OK');
		//Réponse correcte ?
		if(strtolower(trim($_GET['reponse']))==$reponse) {
			$resultat = "Bravo!";
		} else {
			$resultat = "Dommage...";
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
<p><?= $question ?></p>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
	<fieldset>
		<label for="reponse">Réponse: </label>
		<input type="text" name="reponse" id="reponse" required>
	</fieldset>
	<button name="btSend">Envoyer</button>
</form>

<div id="resultat">
	<p><?= $resultat ?></p>
</div>
</body>
</html>