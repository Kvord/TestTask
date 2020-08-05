<?php

function DBLink(){
	$db_host = "localhost";
	$db_name = "testtask";
	$db_user = "Kvord";
	$db_pass = "123"; //Test_1611

	$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die();
	if (! $link) { 
		echo "Ошибка доступа к MySql";
		exit();
	}
	mysqli_set_charset($link, "utf8");
	return $link;
}