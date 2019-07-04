<?PHP
/**
 *course中保持2學期的課程
 */
  require 'fadmin.php';
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	$sybase_name="academic_gra";
	//select sybase 之一般開課
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) )
	{	
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );
	}
	$csd = @sybase_select_db($sybase_name, $cnx);	
	//$cur = sybase_query("select a.year year, a.term term, a.unitname unitname, a.cour_cd cour_cd, a.grp grp, b.name name, a.id id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no" , $cnx);
	$cur = sybase_query("select a.year year, a.term term, a.unitname unitname, a.cour_cd cour_cd, a.grp grp,b.name name, a.id id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no" , $cnx);
	//$cur = sybase_query("select * from a31vcurriculum_tea", $cnx);
	echo "cnx NO $cnx <br>";
	echo "cur No $cur <br>";
	if(!$cur) 
	{  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	
	while($array=sybase_fetch_array($cur))
	{
		echo "$array[unitname]<br>";
	}
	$total = sybase_num_rows($cur);
	echo "total rows $total<br>";
	sybase_close( $cnx);
	
	$sybase_name="academic";
	//select sybase 之一般開課
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) )
	{	
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );
	}
	$csd = @sybase_select_db($sybase_name, $cnx);	
	$cur = sybase_query("select a.year, a.term, a.unitname, a.cour_cd, a.grp, b.name, a.id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no" , $cnx);
	echo "cnx NO $cnx <br>";
	echo "cur No $cur <br>";
	if(!$cur) 
	{  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	$total = sybase_num_rows($cur);
	echo "total rows $total<br>";
	sybase_close( $cnx);
?>
