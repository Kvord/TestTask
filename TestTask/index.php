<?php
session_start(); // Запуск сессии

require './config/function.php'; // Включение файла
require './config/db_link.php';

$link = DBLink(); // Функция для подключения к базе данных

$num = 3; // Число выводимых задач на станице
$page = $_GET['page']; // URL текущей странице
$result = mysqli_query($link, "SELECT COUNT(*) FROM tasks"); // Oбщее число сообщений в базе данных
$posts = mysqli_fetch_row($result);
$total = intval(($posts[0] - 1) / $num) + 1; // Общее число страниц
$page = intval($page); // начало сообщений для текущей страницы

// Переход на страницу если $page < 1 или наоборот
if(empty($page) or $page < 0) { 
	$page = 1;
  	if($page > $total){ 
		$page = $total;
	}
}

$start = $page * $num - $num; // Вычисление номера задачи на странице

// Сортировка задач
if (isset($_GET['filter'])) {
	$filtering = $_GET['filter'];
	switch ($filtering) 
	{ 
	case 'username-desc'; 
	$filtering = 'username DESC'; 
	$filter_name = 'username-desc';
	break; 
	case 'username-asc'; 
	$filtering = 'username ASC'; 
	$filter_name = 'username-asc';
	break; 
	case 'email-desc'; 
	$filtering = 'email DESC'; 
	$filter_name = 'email-desc';
	break; 
	case 'email-asc'; 
	$filtering = 'email ASC'; 
	$filter_name = 'email-asc';
	break;
	case 'status-desc'; 
	$filtering = 'status DESC';
	$filter_name = 'status-desc';
	break;
	case 'status-asc'; 
	$filtering = 'status ASC';
	$filter_name = 'status-asc';
	break;
	case 'default'; 
	$filtering = 'id DESC';
	$filter_name = 'id-desc';
	break;
	default:
	$filtering = 'id DESC';
	$filter_name = 'id-desc';
	break;
	}
}

// Сборка запроса для вывода
$sqlresult = "SELECT * from tasks ";
if (isset($_GET['filter'])) {
	$sqlresult .= "order by $filtering ";
} else {
	$sqlresult .= "order by id DESC ";
}
$sqlresult .= "LIMIT $start, $num";
$result = mysqli_query($link, $sqlresult); // Выполнение запроса
// Цикл переноса в массив $postrow
while ( $postrow[] = mysqli_fetch_array($result))
// Проверка на стрелки назад
if ($page != 1) $pervpage = '<a style="color: rgb(120, 120, 120);" href="./index.php?page='.($page - 1).'&filter='.$filter_name.'">&#8592</a> ';
// Проверка на стрелки вперед
if ($page != $total) $nextpage = ' <a style="color: rgb(120, 120, 120);" href= ./index.php?page='.($page + 1).'&filter='.$filter_name.'>&#8594</a>';
// Две ближайшие станицы
if($page - 2 > 0) $page2left = ' <a style="color: rgb(120, 120, 120);" href= ./index.php?page='.($page - 2).'&filter='.$filter_name.'>'.($page - 2).'</a> &#8226 ';
if($page - 1 > 0) $page1left = '<a style="color: rgb(120, 120, 120);" href= ./index.php?page='.($page - 1).'&filter='.$filter_name.'>'.($page - 1).'</a> &#8226 ';
if($page + 2 <= $total) $page2right = ' &#8226 <a style="color: rgb(120, 120, 120);" href= ./index.php?page='.($page + 2).'&filter='.$filter_name.'>'.($page + 2).'</a>';
if($page + 1 <= $total) $page1right = ' &#8226 <a style="color: rgb(120, 120, 120);" href= ./index.php?page='.($page + 1) .'&filter='.$filter_name.'>'.($page + 1).'</a>';
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
	<script type="text/javascript" src="/js/jQuery.js"></script>
	<script type="text/javascript" src="/js/main.js"></script>
	<?php if(! isset($_SESSION['admin'])){ ?>
		<style type="text/css">
			<?php echo($done); ?>
		</style>
	<?php } ?>
</head>
<body>
	<header class="container-lg head">
		<div><h1>Task Manager</h1></div>
		<?php if(isset($_SESSION['admin'])){?>
		<div id="userBox">
			<p id="user"><?php echo($_SESSION['admin']['name']); ?></p>
        	<input type="button" onclick="logout();" value="Выйти">
		</div>
		<?php } else { ?>
		<div id="loginBox">
			<div class="showHiddenLogin" onclick="showLoginField();"><p>Войти</p></div>
			<div id="loginField" class="loginBoxHidden">
	            <input type="text" id="login" name="login" placeholder=" Логин">
	            <input type="password" id="loginPwd" name="loginPwd" placeholder=" Пароль">
	            <input type="button" onclick="login();" value="Войти">
        	</div>
        </div>
		<?php } ?>
	</header>
	<section class="container-lg">
		<div class="showHiddenTask" onclick="showBoxMakeTask();"><p>Создать задачу</p></div>
		<div id="taskField" class="boxHidden">
			<table class="list-tasks table">
				<tr>
					<td><input type="text" id="name" name="name" placeholder=" Имя"></td>
					<td><input type="text" id="email" name="email" placeholder=" E-mail"></td>
					<td><textarea id="taskmake" name="taskmake" placeholder=" Задача"></textarea></td>
					<td><input type="button" onclick="makeTask();" value="Создать"></td>
				</tr>
			</table>
		</div>
		<div class="page-sort">
			<div class="num"><?php echo $pervpage.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$nextpage; // Вывод номеров страниц ?></div>
			<div id="sort_menu">
				<nav class="menu">
					<ul>
						<li><p>Сортировать</p>
							<ul>
								<li><a href="index.php?<?php echo('page='.$page); ?>&filter=username-desc">Имена по убыванию</a></li>
								<li><a href="index.php?<?php echo('page='.$page); ?>&filter=username-asc">Имена по возрастанию</a></li>
								<li><a href="index.php?<?php echo('page='.$page); ?>&filter=email-desc">E-mail по убыванию</a></li>
								<li><a href="index.php?<?php echo('page='.$page); ?>&filter=email-asc">E-mail по возрастанию</a></li>
								<li><a href="index.php?<?php echo('page='.$page); ?>&filter=status-desc">Статус по убыванию</a></li>
								<li><a href="index.php?<?php echo('page='.$page); ?>&filter=status-asc">Статус по возрастанию</a></li>
								<li><a href="index.php?<?php echo('page='.$page); ?>&filter=default">Сбросить</a></li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
		</div>

		<table class="list-tasks table">
			<tr>
				<th scope="col">Имя</th>
				<th scope="col">E-mail</th>
				<th scope="col">Задача</th>
				<th scope="col">Статус</th>
				<?php if(isset($_SESSION['admin'])){?>
					<th scope="col">Редактирование</th>
				<?php } ?>
			</tr>
		<?php for($i = 0; $i < $num; $i++) { if(isset($postrow[$i]['id'])){ ?>
			<tr>
				<td><input id="nametable" type="text" <?php if(! isset($_SESSION['admin'])){ echo('readonly');} ?> value="<?php echo($postrow[$i]['username']);?>"></td>
				<td><input id="emailtable" type="text" <?php if(! isset($_SESSION['admin'])){ echo('readonly');} ?> value="<?php echo($postrow[$i]['email']);?>"></td>
				<td>
					<textarea id="tasktable" type="text" <?php if(! isset($_SESSION['admin'])){ echo('readonly');} ?>><?php echo($postrow[$i]['task']);?></textarea>
					<?php if($postrow[$i]['adminchange'] == 1){ echo('<p>Отредактированно администратором</p>'); } ?>
				</td>
				<td><p>Выполнено: <input id="itemStatus_<?php echo($postrow[$i]['id']);?>" <?php if(! isset($_SESSION['admin'])){ echo('readonly');} ?> type="checkbox" <?php if($postrow[$i]['status'] == 1){ echo('checked="checked"'); } ?> onclick="setStatus(<?php echo($postrow[$i]['id'])?>);"></p></td>
				<?php if(isset($_SESSION['admin'])){?>
				<td>
					<input type="button" name="change" value="Изменить" onclick="changeTask(<?php echo($postrow[$i]['id']);?>);">
				</td>
				<?php } ?>
			</tr>
		<?php }} ?>
		</table>

	</section>
</body>
</html>