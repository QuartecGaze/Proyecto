<?php
	$hostname = 'db';//localhost
	$username = 'root';//root
	$password = 'RootAdministrador5749';//root
	$database = 'cooperativa';//cooperativa
	$conn = mysqli_connect($hostname, $username, $password, $database);
	if(!$conn){
		die('Coneccion Fallida: ' . mysqli_connect_error());
	}
?>
