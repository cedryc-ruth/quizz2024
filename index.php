<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Quizz</title>
</head>
<body>
<h1>Quizz</h1>
<p>QUESTION</p>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
	<fieldset>
		<label for="reponse">Réponse: </label>
		<input type="text" name="reponse" id="reponse" required>
	</fieldset>
	<button>Envoyer</button>
</form>

<div id="resultat">
	<p>RESULAT</p>
</div>
</body>
</html>