<?php
session_start();

require './config/function.php';
require './config/db_link.php';

function loginUser($login, $pwd){
	$link = DBLink();
	$email = htmlspecialchars(mysqli_real_escape_string($link, $email)); // Экранирование символов и сохранение значений
	$sql = "SELECT * FROM adminusers
			WHERE (login = '{$login}')
			LIMIT 1";

	$sqlHash = "SELECT pwd FROM adminusers
				WHERE (login = '{$login}')
				LIMIT 1";

    $rsHash = mysqli_query($link, $sqlHash); // Выполнение запроса

	$rsHash = createRsArray($rsHash);
	if (isset($rsHash[0])) {

		$rsHash = implode($rsHash[0]);	  // Форматироваание
		$rsHash = substr($rsHash, 0, 60); // хешированного пароля

		$rs = mysqli_query($link, $sql);
		$rs = createRsArray($rs);
		if (password_verify($pwd, $rsHash)) { // Проверка пароля TRUE/FALSE
			$rs['success'] = 1;
		} else {
			$rs['success'] = 0;
		}
	} else {
		$rs['success'] = 0;
	}
	return $rs;
}

function updateStatus($itemId, $status){
	$link = DBLink();
	$sql = "UPDATE tasks
			SET status = '{$status}'
			WHERE id = '{$itemId}'";

	$rs = mysqli_query($link, $sql);
	return $rs;
}

function createTask($name, $email, $taskmake){
	$link = DBLink();
	$name = htmlspecialchars(mysqli_real_escape_string($link, $name));
	$email = htmlspecialchars(mysqli_real_escape_string($link, $email));
	$taskmake = htmlspecialchars(mysqli_real_escape_string($link, $taskmake));
	$email = trim($email);
	$sql = "INSERT INTO tasks (id, username, email, task, status, adminchange) 
            VALUES (NULL, '{$name}', '{$email}', '{$taskmake}', '{0}', '{0}');";
	$rs = mysqli_query($link, $sql);
    return $rs;
}

function updateTask($name, $email, $task, $itemId){
	$link = DBLink();
	$name   = htmlspecialchars(mysqli_real_escape_string($link, $name));
	$email  = htmlspecialchars(mysqli_real_escape_string($link, $email));
	$task = htmlspecialchars(mysqli_real_escape_string($link, $task));
	$email = trim($email);
	if (isset($_SESSION['admin'])) {
		$sql = "UPDATE tasks SET username = '{$name}', email = '{$email}', task = '{$task}', adminchange = '1' 
				WHERE id = '{$itemId}'";
		$rs = mysqli_query($link, $sql);
		$rst['success'] = 1;
	} else {
		$rst['success'] = 0;
	}
    return $rst;
}