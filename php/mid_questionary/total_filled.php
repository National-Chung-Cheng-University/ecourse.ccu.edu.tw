<?php
//devon 2005-11-08
//加總所有的fill_count欄位值，以得到填寫的學生總人數。
//雖然有些學生會有重覆計算到，不過因為是以每門課為一個單位，所以沒關係。
$DB_SERVER = "localhost";
$DB_LOGIN = "study";
$DB_PASSWORD = "2720411";
$DB = "study";

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
}

$total_fill_count = 0;

$Q0 = "select fill_count from mid_statistic order by course_no";
$result0 = mysql_db_query($DB, $Q0);
while($rows0 = mysql_fetch_array($result0))
{
	$total_fill_count = $total_fill_count + $rows0['fill_count'];
}
echo $total_fill_count;

?>