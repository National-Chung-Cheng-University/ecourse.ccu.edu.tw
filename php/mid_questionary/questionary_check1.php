<?php
	require 'fadmin.php';

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!" );
	}
	$count = 0;
	$year = 101; 
	$term = 2;

	$Q2 = "SELECT distinct teach_course.course_id FROM teach_course WHERE teach_course.year = '$year' AND teach_course.term = '$term' ORDER BY teach_course.course_id "; //��ܦ��Ǵ����ҵ{id
	if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~3!!" );
	}		
	while ( $row2 = mysql_fetch_array($result2) ) {			
		if ($row2['course_id'] == '0') continue;
    $count++;
		$course_id = $DB.$row2['course_id'];
		echo $count."--".$course_id;
		
    $Q4 = "SHOW TABLES LIKE 'mid_ans' ";		
		if ( !($result4=mysql_db_query($DB.$row2['course_id'],$Q4)) ){
			//show_page( "not_access.tpl" ,"��Ʈw�g�J���~4!!" );
			echo "��Ʈw�g�J���~4!!"."</br>";
		} 
		else {
			if(mysql_num_rows($result4)==1) {
				echo "--Table exists";
				$Q5 = "ALTER TABLE mid_ans ADD q8 TEXT NULL AFTER q7, ADD q9 TEXT NULL AFTER q8 ";
				if ( !($result5=mysql_db_query($DB.$row2['course_id'],$Q5)) ){
					echo "��Ʈw�g�J���~5!!"."</br>";
				}
				else {
					echo "Update successful"."</br>";
				}
			}
			else {
				echo "--Table does not exists"."</br>";
			}
		}
	}
  echo "Total Numbers Is :".$count;

?>
