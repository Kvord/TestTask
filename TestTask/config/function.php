<?php 

// Перебор данных в массив
function createRsArray($rs){
	if (! $rs) return false;
	$arRs = array();
	while ($row = mysqli_fetch_assoc($rs)) {
		$arRs[] = $row;
	}
	return $arRs;
}