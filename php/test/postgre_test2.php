<?php
  $db_name = 'academic';
	$conn_string = "host=140.123.30.12 dbname=".$db_name." user=acauser password=!!acauser13";
	$cnx = pg_pconnect($conn_string) or die('��Ʈw�S���^���A�еy��A��');
	
	$cur = pg_query($cnx, "select a.year, a.term, a.unitname, a.cour_cd, a.grp, b.name, a.id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no") or die('��ƪ��s�b�A�гq���q�⤤��');

	
	while ($line = pg_fetch_array($cur, null, PGSQL_ASSOC)) {
		echo $line['year']."--".$line['term']."--".$line['cour_cd']."--".mb_convert_encoding($line['name'], "big5", "utf-8")."<br>";
	}
	
	// Free resultset
	pg_free_result($cur);
	
	// Closing connection
	pg_close($cnx);

echo "123456";
	
?>