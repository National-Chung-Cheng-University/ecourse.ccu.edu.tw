<?php
require 'fadmin.php';

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
}
	
//$SQL = "SELECT a_id FROM course order by a_id";
$SQL = "SELECT DISTINCT `course_id` 
FROM `teach_course` 
WHERE `year` =100
AND `term` =1
ORDER BY `teach_course`.`course_id` ASC 
";
$results = mysql_db_query($DB, $SQL);
$num = 0;

echo "<table border=1>";
while($row = mysql_fetch_assoc($results))
{
	$course_id = $row['course_id'];
	$SQL = "update function_list set warning = '1'";
	
		
	if ( !($result1 = mysql_db_query( $DB.$course_id, $SQL ) ) ) {
		show_page( "not_access.tpl" ,"資料庫查詢錯誤!!" );
	}
		
	echo "<tr><td>課程編號</td><td>$DB.$course_id</td></tr>";
}	
echo "</table>";
	

?>