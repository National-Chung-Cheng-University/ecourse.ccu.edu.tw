<?PHP
/**
 *course���O��2�Ǵ����ҵ{
 */
  require 'fadmin.php';
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	$sybase_name="academic_gra";
	//select sybase ���@��}��
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) )
	{	
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );
	}
	$csd = @sybase_select_db($sybase_name, $cnx);	
	//$cur = sybase_query("select a.year year, a.term term, a.unitname unitname, a.cour_cd cour_cd, a.grp grp, b.name name, a.id id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no" , $cnx);
	$cur = sybase_query("select a.year year, a.term term, a.unitname unitname, a.cour_cd cour_cd, a.grp grp,b.name name, a.id id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no" , $cnx);
	//$cur = sybase_query("select * from a31vcurriculum_tea", $cnx);
	echo "cnx NO $cnx <br>";
	echo "cur No $cur <br>";
	if(!$cur) 
	{  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	
	while($array=sybase_fetch_array($cur))
	{
		echo "$array[unitname]<br>";
	}
	$total = sybase_num_rows($cur);
	echo "total rows $total<br>";
	sybase_close( $cnx);
	
	$sybase_name="academic";
	//select sybase ���@��}��
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) )
	{	
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );
	}
	$csd = @sybase_select_db($sybase_name, $cnx);	
	$cur = sybase_query("select a.year, a.term, a.unitname, a.cour_cd, a.grp, b.name, a.id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no" , $cnx);
	echo "cnx NO $cnx <br>";
	echo "cur No $cur <br>";
	if(!$cur) 
	{  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	$total = sybase_num_rows($cur);
	echo "total rows $total<br>";
	sybase_close( $cnx);
?>
