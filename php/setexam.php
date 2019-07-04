<?
$Q1 = "select * from course";
$Q2 = "update take_exam set grade= -1 where grade IS NULL";
mysql_pconnect( "127.0.0.1", "study", "study#root" );
$result = mysql_db_query( "study", $Q1);
while( $row = mysql_fetch_array($result) ) {
	var_dump ($row['a_id']);
	$result2 = mysql_db_query( "study".$row['a_id'], $Q2);
}
?>