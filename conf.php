<?php
	$db_username = "root";
	$db_password = "";
	$dsn = "mysql:host=localhost;dbname=1;charset=utf8";
	try{
		$pdo = new PDO($dsn, $db_username, $db_password);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
		exit();
	}
	$pdo->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
?>
