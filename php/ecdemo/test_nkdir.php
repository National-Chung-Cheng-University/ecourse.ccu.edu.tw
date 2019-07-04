<?PHP
/*
 建立課程的目錄
*/
require 'fadmin.php';
?>
<html>
<head>
<title>更新開課</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div id="progress">	　
</div>

<?PHP
//test_used_course();
function test_used_course()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	//select sybase 之一般開課
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) )
	{	
		Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );
	}
	$csd = @sybase_select_db("academic", $cnx);	
	$cur = sybase_query("select a.year, a.term, a.unitname, a.cour_cd, a.grp, b.name, a.id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no" , $cnx);
	if(!$cur) 
	{  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	
	// 連結mysql
	($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) or
		die('Not connected : ' . mysql_error());	//改為pconnect
	
	$count = 0;	
	while($array=sybase_fetch_array($cur))
	{
		$cno = $array["cour_cd"]."_".$array["grp"];
		$Q0 = "select a.id from a31vcurriculum_tea a where a.cour_cd='".$array["cour_cd"]."' and a.grp = '".$array["grp"]."'";
		$rs0 = sybase_query($Q0,$cnx);		//去掉link 因為上面已用pconnect
		$num_rs0 = sybase_num_rows($rs0);
		if($num_rs0==1){
			$Q1 = "select count(u.a_id) as num from teach_course tc, course c, user u where tc.year=94 and tc.term=2 and c.a_id = tc.course_id and tc.teacher_id=u.a_id and u.authorization=1 and c.course_no='".$cno."' and u.id='".$array["id"]."'";
			$rs1 = mysql_db_query($DB,$Q1);		//去掉link 因為上面已用pconnect
			$num_rs1 = mysql_fetch_array($rs1);
			if($num_rs1['num']==1){
				$count++;
			}
		}else
		{
			echo "$num_rs0<BR>";
		}
	}
	echo "一般共有".$count."堂課沿用<BR>";
	
	//在職
	$csd = @sybase_select_db("academic_gra", $cnx);	
	$cur = sybase_query("select a.year, a.term, a.unitname, a.cour_cd, a.grp, b.name, a.id from a31vcurriculum_tea a, a30vcourse_tea b where a.cour_cd=b.course_no" , $cnx);
	if(!$cur) 
	{  
		Error_handler( "在 sybase_exec 有錯誤發生( 沒有指標傳回 ) " , $cnx );  
	}
	$count = 0;	
	while($array=sybase_fetch_array($cur))
	{
		$cno = $array["cour_cd"]."_".$array["grp"];
		$Q0 = "select a.id from a31vcurriculum_tea a where a.cour_cd='".$array["cour_cd"]."' and a.grp = '".$array["grp"]."'";
		$rs0 = sybase_query($Q0,$cnx);		//去掉link 因為上面已用pconnect
		$num_rs0 = sybase_num_rows($rs0);
		if($num_rs0==1){
			$Q1 = "select count(u.a_id) as num from teach_course tc, course c, user u where tc.year=94 and tc.term=2 and c.a_id = tc.course_id and tc.teacher_id=u.a_id and u.authorization=1 and c.course_no='".$cno."' and u.id='".$array["id"]."'";
			$rs1 = mysql_db_query($DB,$Q1);		//去掉link 因為上面已用pconnect
			$num_rs1 = mysql_fetch_array($rs1);
			if($num_rs1['num']==1){
				$count++;
			}
		}else
		{
			echo "$num_rs0<BR>";
		}
	}
	echo "在職共有".$count."堂課沿用<BR>";

}
function makedir(){
	$qs2 = "SELECT * FROM course order by a_id";
	if ($result2 = mysql_db_query($DB, $qs2)){
		//計算進度用
		$realcount=0;
		$temp = -1;
		$total = mysql_num_rows($result2);
		echo "總共 $total 門課<br>";
		ob_end_flush();
		ob_implicit_flush(1);
		//
		while($row2 = mysql_fetch_array($result2)){
			//計算進度用
			$realcount++;	
			$p = number_format((100*$realcount)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"確認及更新中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;
			//
			$aid = $row2['a_id'];
			if(!is_dir("../../".$aid)){
				echo "建立課程".$aid ."目錄<BR>";
				mkdir ( "../../".$aid, 0771 );
				chmod ( "../../".$aid, 0771 );
				mkdir ( "/backup/".$aid, 0771 );
				chmod ( "/backup/".$aid, 0771 );
				mkdir ( "../../".$aid."/homework", 0771 );
				chmod ( "../../".$aid."/homework", 0771 );
				mkdir ( "../../".$aid."/homework/comment", 0771 );
				chmod ( "../../".$aid."/homework/comment", 0771 );
				mkdir ( "../../".$aid."/homework/upload", 0771 );
				chmod ( "../../".$aid."/homework/upload", 0771 );
				mkdir ( "../../".$aid."/on_line", 0771 );
				chmod ( "../../".$aid."/on_line", 0771 );
				mkdir ( "../../".$aid."/textbook", 0771 );
				chmod ( "../../".$aid."/textbook", 0771 );
				mkdir ( "../../".$aid."/textbook/misc", 0771 );
				chmod ( "../../".$aid."/textbook/misc", 0771 );
				mkdir ( "../../".$aid."/student_info", 0771 );
				chmod ( "../../".$aid."/student_info", 0771 );
				mkdir ( "../../".$aid."/board", 0771 );
				chmod ( "../../".$aid."/board", 0771 );
				mkdir ( "../../".$aid."/intro", 0771 );
				chmod ( "../../".$aid."/intro", 0771 );
				mkdir ( "../../".$aid."/coop", 0771 );
				chmod ( "../../".$aid."/coop", 0771 );
				mkdir ( "../../".$aid."/exam", 0771 );
				chmod ( "../../".$aid."/exam", 0771 );
			}
		}
	}
	else{
		$error = "mysql資料庫讀取錯誤2!!<br>";
		return $error;
	}
}
?>
