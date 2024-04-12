<?php
require 'config.php';

//Se connecter au serveur de base de données
$link = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);	//var_dump($link);

//Préparer la requête
	//
$query = "SELECT * FROM quizz";
	
//Envoyer la requête et récupérer le résultat
$result = mysqli_query($link, $query);		//var_dump($result);

//Extraire les données du résultat
/*$row = mysqli_fetch_row($result);					var_dump($row);
$row = mysqli_fetch_assoc($result);					var_dump($row);
$row = mysqli_fetch_array($result, MYSQLI_BOTH);	var_dump($row);
*/
/*
while(($row = mysqli_fetch_assoc($result))!=null) {
	$data[] = $row;
}
*/

$data = mysqli_fetch_all($result, MYSQLI_ASSOC);	

	//var_dump($data);

//Libérer la mémoire du résultat
mysqli_free_result($result);

//Se déconnecter du serveur de base de données
mysqli_close($link);


foreach($data as $row) {
	$questions[] = $row['question'];
	$reponses[] = $row['reponse'];
	
	echo "<p>".$row['question']."</p>";
}
?>