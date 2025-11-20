<?php
	$hostname = 'db';//localhost
	$username = 'api_cooperativa';//root
	$password = 'Cooperativa123!';//root
	$database = 'cooperativa';//cooperativa
	$conn = mysqli_connect($hostname, $username, $password, $database);
	if(!$conn){
		die('Coneccion Fallida: ' . mysqli_connect_error());
	}
?>
