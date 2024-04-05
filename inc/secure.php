<?php
if(empty($_SESSION['login'])) {	//Si pas connecté
	header('Status: 302 Temporary');
	header('location: login.php');
	exit;
}
?>