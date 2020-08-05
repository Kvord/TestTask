<?php
session_start();

require './doing.php';

// При условии если передано название функции осуществляется ее вызов 
if(isset($_REQUEST['func'])){
    $function = $_REQUEST['func'];
    $_REQUEST['args'];
    $function();
}

// 
// Вход администратора
// 
function login(){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Проверка на метод POST в запросе
		$login = isset($_REQUEST['args'][0]) ? $_REQUEST['args'][0] : NULL; // Занесение в переменную с проверкой на наличие
		$login = trim($login); // Удаление пробелов по бокам
		$pwd = isset($_REQUEST['args'][1]) ? $_REQUEST['args'][1] : NULL;
        $pwd = trim($pwd);
		$userData = loginUser($login, $pwd);
		if ($userData['success']) { // При условии данные о логине заносятся в сессию
			$userData = $userData[0];
			$_SESSION['admin'] = $userData;
			$_SESSION['admin']['displayName'] = $userData['name'] ? $userData['name'] : $userData['email'];
			$resData = $_SESSION['admin'];
            $resData['success'] = 1;
		} else {
			$resData['success'] = 0;
		}
	}
	echo json_encode($resData);
	exit();
}

// 
// Выход из логина
// 
function logout() {
    $result = null;

    // Если сессия существует то сессия удаляется
    if(isset($_SESSION['admin'])) {
        unset($_SESSION['admin']);
    }

    // Условие для возвращает данные JSON для обратной связи
    if(! $_SESSION['admin']) {
        $result['success'] = 1;
    } else {
        $result['success'] = 0;
    }
    echo json_encode($result); // Возвращение JSON
    exit();
}

// 
// Создание задачи
// 
function makeTask(){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name     = isset($_REQUEST['args'][0]) ? $_REQUEST['args'][0] : NULL;
        $email    = isset($_REQUEST['args'][1]) ? $_REQUEST['args'][1] : NULL;
        $taskmake = isset($_REQUEST['args'][2]) ? $_REQUEST['args'][2] : NULL;
        $pattern = "|^([a-z0-9_.-]{1,20})@([a-z0-9.-]{1,20}).([a-z]{2,4})|is";
        if (preg_match($pattern, strtolower($email))) { // Проверка E-mail на валидность
            $res = createTask($name, $email, $taskmake);
            if ($res) {
                $resData['success'] = 1;
                $resData['message'] = "Задача создана.";
            } else {
                $resData['success'] = 0;
                $resData['message'] = "Ошибка создания!";
            }
        } else {
            $resData['success'] = 0;
            $resData['message'] = "E-mail не валиден";
        }
    }
    echo json_encode($resData);
    exit();
}

// 
// Редактирование задачи администратором
// 
function changeTask(){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name  = isset($_REQUEST['args'][0])  ? $_REQUEST['args'][0] : NULL;
        $email = isset($_REQUEST['args'][1])  ? $_REQUEST['args'][1] : NULL;
        $task  = isset($_REQUEST['args'][2])  ? $_REQUEST['args'][2] : NULL;
        $itemId = $_POST['args'][3];
        $res = updateTask($name, $email, $task, $itemId);
        if ($res['success']) {
            $resData['success'] = 1;
        } else {
            $resData['success'] = 0;
        }
    }
    echo json_encode($resData);
    exit();
}

// 
// Изменение статуса задачи
// 
function setStatus(){
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $itemId = $_REQUEST['args'][0];
        $status = $_REQUEST['args'][1];
        $res = updateStatus($itemId, $status);
        if ($res) {
            $resData['success'] = 1;
        } else {
            $resData['success'] = 0;
        }
    }
    echo json_encode($resData);
    exit();
}