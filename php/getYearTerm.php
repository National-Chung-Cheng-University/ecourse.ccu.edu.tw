<?php

function getYearTerm($file){
$fp = fopen($file,'r');
$str = '';

while(!feof($fp)){
	$str .= fread($fp,451);
}

$start = strpos($str,'"�з���">')+9;
$end = strpos($str,'<br>');

return substr($str,$start,$end-$start);
}

?>
