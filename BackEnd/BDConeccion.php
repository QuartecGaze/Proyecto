<?php
	$hostname = 'localhost';//db_mysql
	$username = 'root';//cooperativa_user
	$password = 'root';//coop123
	$database = 'cooperativa';//cooperativa
	$conn = mysqli_connect($hostname, $username, $password, $database);
	if(!$conn){
		die('Coneccion Fallida: ' . mysqli_connect_error());
	}
?>
