<?php
	$hostname = 'localhost';
	$username = 'root';
	$password = 'root';
	$database = 'cooperativa';
	$conn = mysqli_connect($hostname, $username, $password, $database);
	if(!$conn){
		die('Coneccion Fallida: ' . mysqli_connect_error());
	}
?>
