<?php
    $config = array(
		'host'    => $settings->production->database->connection,
		'username'    => $settings->production->database->user,
		'password'    => $settings->production->database->password,
		'dbname'    => $settings->production->database->name
	);

	$db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'], $config['password']);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
