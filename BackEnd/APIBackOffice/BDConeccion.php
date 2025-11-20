<?php
	$hostname = 'db';//localhost
	$username = 'api_backoffice';//root
	$password = 'BackOffice123!';//root
	$database = 'cooperativa';//cooperativa
	$conn = mysqli_connect($hostname, $username, $password, $database);
	if(!$conn){
		die('Coneccion Fallida: ' . mysqli_connect_error());
	}
?>
