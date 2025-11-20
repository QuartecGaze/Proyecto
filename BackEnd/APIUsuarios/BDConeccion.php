<?php
	$hostname = 'db';//localhost
	$username = 'api_usuarios';//root
	$password = 'Usuarios123!';//root
	$database = 'cooperativa';//cooperativa
	$conn = mysqli_connect($hostname, $username, $password, $database);
	if(!$conn){
		die('Coneccion Fallida: ' . mysqli_connect_error());
	}
?>
