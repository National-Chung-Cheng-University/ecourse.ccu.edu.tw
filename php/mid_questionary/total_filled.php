<?php
//devon 2005-11-08
//�[�`�Ҧ���fill_count���ȡA�H�o���g���ǥ��`�H�ơC
//���M���Ǿǥͷ|�����Эp���A���L�]���O�H�C���Ҭ��@�ӳ��A�ҥH�S���Y�C
$DB_SERVER = "localhost";
$DB_LOGIN = "study";
$DB_PASSWORD = "2720411";
$DB = "study";

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
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