<?php
	$hostname = '192.168.1.50';//localhost
	$username = 'root';//root
	$password = 'Root';//root
	$database = 'cooperativa';//cooperativa
	$conn = mysqli_connect($hostname, $username, $password, $database);
	if(!$conn){
		die('Coneccion Fallida: ' . mysqli_connect_error());
	}
?>
