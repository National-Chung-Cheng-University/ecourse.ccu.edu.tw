<?php
require 'fadmin.php';
update_status ("批改作業");

if( isset($PHPSESSID) && session_check_teach($PHPSESSID) >= 2 )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if($action == "pubans")
	{
		$Q1 = "SELECT public FROM homework WHERE a_id = '$work_id'";
		$Q10 = "UPDATE homework SET public='2' WHERE a_id = '$work_id'";
		$Q11 = "UPDATE homework SET public='3' WHERE a_id = '$work_id'";
		$Q12 = "UPDATE homework SET public='0' WHERE a_id = '$work_id'";
		$Q13 = "UPDATE homework SET public='1' WHERE a_id = '$work_id'";
		
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		$rows = mysql_fetch_array( $result1 );
		$Q = "Q1".$rows[0];
		$rs=mysql_db_query($DB.$course_id, $$Q );
		show_page_d();
	}
	elseif($action == "pubwork")
	{
		$Q1 = "SELECT public FROM homework WHERE a_id = '$work_id'";
		$Q10 = "UPDATE homework SET public='1' WHERE a_id = '$work_id'";
		$Q11 = "UPDATE homework SET public='0' WHERE a_id = '$work_id'";
		$Q12 = "UPDATE homework SET public='3' WHERE a_id = '$work_id'";
		$Q13 = "UPDATE homework SET public='2' WHERE a_id = '$work_id'";

		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		$rows = mysql_fetch_array( $result1 );
		$Q = "Q1".$rows[0];
		$rs=mysql_db_query($DB.$course_id, $$Q );
    		show_page_d ();
	}
	elseif($action == "delay") //作業遲交　
	{
		$Q1 = "SELECT late FROM homework WHERE a_id=".$work_id;
		$Q2 = "UPDATE homework SET late='1' WHERE a_id='".$work_id."'";
		$Q3 = "UPDATE homework SET late='0' WHERE a_id='".$work_id."'";

		if (!($result1 = mysql_db_query($DB.$course_id,$Q1)))
			show_page("not_access.tpl","資料庫寫入錯誤!!");
		else
			$rows = mysql_fetch_array($result1);

		if ($rows['late'] == '0')
			$rs = mysql_db_query($DB.$course_id,$Q2);
		else
			$rs = mysql_db_query($DB.$course_id,$Q3);

		show_page_d();
	}			
	elseif($action == "showwork")
	{
		showwork ();
	}
	elseif($action == "checkstudent")
	{
		checkstu ();
	}
	elseif($action == "showtool")
	{
		include("class.FastTemplate.php3");
		$tpl=new FastTemplate("./templates");
		$tpl->define(array(main=>"tools.tpl"));
		$tpl->assign(SNO,$sid);
		$tpl->assign(WORKID,$work_id);
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	elseif($action == "loadwork")
	{
		$Q1 = "SELECT work,upload FROM handin_homework WHERE homework_id='$work_id' AND student_id='$sid'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		$rows = mysql_fetch_array($result1);
		
		if ( $rows[1] == 0 ) {
			include("class.FastTemplate.php3");
			$tpl=new FastTemplate("./templates");
			$tpl->define(array(main=>"correctwork.tpl"));
			$tpl->assign(SNO,$sid);
			$tpl->assign(WORKID,$work_id);
			$tpl->parse(BODY,"main");
			$tpl->FastPrint("BODY");
		}
		else
			filelist();
		
	}
	elseif($action == "seestuwork")
	{
		$Q1 = "SELECT work FROM handin_homework WHERE homework_id='$work_id' AND student_id='$sid'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		$rows = mysql_fetch_array($result1);
		echo "<HTML>";
		echo "<head><link rel='stylesheet' type='text/css' href='./default.css'></head>";
		echo "<script type='text/javascript' src='/js/ASCIIMathML.js'></script>";

		echo $rows[0];
		echo "</HTML>";
	}
	elseif($action == "sendresult")
	{
		if ( $grade == "" )
			$grade = -1;
		if ( $vml != "" && $vml != "null" && $vml != NULL )
			$Q1 = "UPDATE handin_homework SET grade='$grade',work='$vml' WHERE student_id='$sid' AND homework_id='$work_id'";
		else
			$Q1 = "UPDATE handin_homework SET grade='$grade' WHERE student_id='$sid' AND homework_id='$work_id'";
	  	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		else
	    		checkstu ();
	}
	elseif($action == "updateg")
	{
	/************************************************************/
	/**
       modified by devon 2005-05-02，一次上傳全部成績
                                                         **/
		$Q0 = "select u.a_id, u.id from user u, take_course tc where u.a_id = tc.student_id and tc.course_id = '$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' order by u.id";
		if( !($result0 = mysql_db_query( $DB, $Q0 ) ) )
			echo("資料庫讀取錯誤0!!");
		$row0 = mysql_num_rows($result0);
		
		for( $i=0; $i<$row0; $i++ )
		{
			$wgrade = $_POST["wgrade".$i];
			$sid = $_POST["id".$i];
			
			if( $wgrade == "" )
			{
				$Q2 = "SELECT grade FROM handin_homework WHERE student_id ='$sid' AND homework_id = '$work_id'";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					echo ( "資料庫讀取錯誤2!!" );
					exit;
				}
				$rows2 = mysql_fetch_array( $result2 );

				if ( $rows2['grade'] == NULL || $rows2['grade'] == "-1" )
					$wgrade = -1;
				else
					$wgrade = $rows2['grade'];
			}
			$Q1 = "UPDATE handin_homework SET grade='$wgrade' WHERE homework_id='$work_id' AND student_id='$sid'";
			if( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) )
				show_page( "not_access.tpl", "資料庫更新錯誤!!" );			
		}
		checkstu();
	/************************************************************/
/*
		if ( $wgrade == "" )
			$wgrade = -1;
		$Q1 = "UPDATE handin_homework SET grade='$wgrade' WHERE homework_id='$work_id' AND student_id='$sid'";

		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		else
				checkstu ();
*/
	}
	elseif($action == "pubstuwork")
	{
		if($ispub == "pubwork")
			$Q1 = "UPDATE handin_homework SET public='1' WHERE homework_id='$work_id' AND student_id='$sid'";
		else
			$Q1 = "UPDATE handin_homework SET public='0' WHERE homework_id='$work_id' AND student_id='$sid'";

		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		else
			checkstu ();
	}
	////////////////////////////////////////////////////////////////////////
	//modified by devon 2005-04-15
	//一次下載全部的學生作業
	else if($action == "downloadallwork") {
		$location="../../$course_id/homework/";
		exec("cd $location; tar -cvf $work_id.tar $work_id/*");
		header("Location: http://$SERVER_NAME/$course_id/homework/$work_id.tar");
	}
	////////////////////////////////////////////////////////////////////////
	else {
		show_page_d ();
	}
}
else
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}

function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $version, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name, percentage, due, public, a_id,chap_num,late FROM homework ORDER BY chap_num, a_id";
	$result1 = mysql_db_query($DB.$course_id, $Q1);

	if ( mysql_num_rows ( $result1 ) ) {
		
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"check_allwork.tpl"));
		else
			$tpl->define(array(main=>"check_allwork_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#F0FFEE";
		while ( $rows = mysql_fetch_array( $result1 ) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$tpl->assign(WORKNAME,$rows[0]);
			$tpl->assign(WORKRATIO,$rows[1]);
			$tpl->assign(WORKDUE,$rows[2]);
			$tpl->assign(WORKID,$rows[4]);
			$tpl->assign(CHAP_NUM,$rows[5]);
			////////////////////////////////////////////////////////////////////////
			//modified by devon 2005-04-15
			//如果都沒人繳交作業就不給下載
			$work_id = $rows[4];
			if( !isEmptyDir("../../$course_id/homework/$work_id") )
			{
				$tpl->assign( DISABLED, "" );
			}else{
				$tpl->assign( DISABLED, "disabled" );
			}
			////////////////////////////////////////////////////////////////////////
			if($rows[3] == "0")
			{
				if($version == "C")
				{  
					$tpl->assign(ISPUBANS,"公佈答案");
					$tpl->assign(ISPUBWORK,"公佈作業");
				}
				else
				{  
					$tpl->assign(ISPUBANS,"Public it");
					$tpl->assign(ISPUBWORK,"Public it");
				}
			}
			elseif($rows[3] == "1")
			{
				if($version == "C")
				{  
					$tpl->assign(ISPUBANS,"公佈答案");
					$tpl->assign(ISPUBWORK,"不公佈");
				}
				else
				{  
					$tpl->assign(ISPUBANS,"Public it");
					$tpl->assign(ISPUBWORK,"Never_Public");
				}
			}
			elseif($rows[3] == "2")
			{
				if($version == "C")
				{  
					$tpl->assign(ISPUBANS,"不公佈");
					$tpl->assign(ISPUBWORK,"公佈作業");
				}
				else
				{  
					$tpl->assign(ISPUBANS,"Never_Public");
					$tpl->assign(ISPUBWORK,"Public it");
				}
			}
			elseif($rows[3] == "3")
			{
				if($version == "C")
				{  
					$tpl->assign(ISPUBANS,"不公佈");
					$tpl->assign(ISPUBWORK,"不公佈");
				}
				else
				{  
					$tpl->assign(ISPUBANS,"Never_Public");
					$tpl->assign(ISPUBWORK,"Never_Public");
				}
			}
			/* --------------------------------------------- */
			/* by carlyle					 */
			/* --------------------------------------------- */
			if ($rows['late'] == '0') {
				if ($version == 'C')
					$tpl->assign(ISDELAY,"允許補交");
				else
					$tpl->assign(ISDELAY,"Allow delay");
			} else if ($rows['late'] == '1') {
				if ($version == 'C')
					$tpl->assign(ISDELAY,"關閉補交");
				else
                	$tpl->assign(ISDELAY,"No delay");
			} 
			/* --------------------------------------------- */			
			$tpl->parse(ROWS,".row");
		}
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前沒有任何作業可供修改!");
		else
			show_page( "not_access.tpl" ,"There is no work for Check!!");
	}
}

function showwork () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $work_id, $course_id, $version, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name,due,percentage,question, q_type FROM homework WHERE a_id='$work_id'";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
	}
	$rows = mysql_fetch_array( $result1 );
	$q_file = "../../$course_id/homework/$work_id/teacher/Question".$rows['q_type'];
	if ( $rows['question'] == "" && $rows['q_type'] != "" && is_file ( $q_file ) ) {
		header( "location: $q_file" );
		exit;
	}
	else {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		$tpl->assign( SKINNUM , $skinnum );
		if($version == "C") {
			$tpl->define(array(main=>"showwork.tpl"));
			$tpl->assign(SHOWTYPE,"作業題目");
		}
		else {
			$tpl->define(array(main=>"showwork_E.tpl"));
			$tpl->assign(SHOWTYPE,"Topic");
		}
		$content = $rows['question'];
		if ( stristr($content,"<html>") == NULL ) {
			$content=htmlspecialchars( $content );
			$content=ereg_replace("\n","<BR>\n",$content);
		}
		$tpl->assign(QUESTION,$content);
		$tpl->assign(WORKNAME,$rows['name']);
	
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
}

function checkstu () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $work_id, $course_id, $version, $skinnum, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "Select u.a_id, u.id, u.name from user u, take_course tc where u.a_id = tc.student_id and tc.course_id = '$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' order by u.id";
	
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}

	if ( mysql_num_rows($result1) != 0 ) {
		include("../class.FastTemplate.php3");
		$tpl=new FastTemplate("./templates");
		if ( $version == "C" )
			$tpl->define(array(main=>"all_stuwork.tpl"));
		else
			$tpl->define(array(main=>"all_stuwork_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#F0FFEE";

			$Q2 = "SELECT student_id, handin_time, grade, public, work FROM handin_homework WHERE homework_id = '$work_id'";
			
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
				 echo ( "資料庫讀取錯誤!!" );
				 exit;
			}

			while($tmp2 = mysql_fetch_array( $result2 ) ){
				$rows2['id'] = $tmp2['student_id'];
				$rows2['handin_time'][ $tmp2['student_id'] ] = $tmp2['handin_time'];
				$rows2['grade'][ $tmp2['student_id'] ] = $tmp2['grad'];
				$rows2['public'][ $tmp2['student_id'] ] = $tmp2['public'];
				$rows2['work'][ $tmp2['student_id']] = $tmp2['work'];
			}

		//為了下面夾帶資料用的變數 i //
		$i = 0;
		while ( $rows1 = mysql_fetch_array($result1) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
		
			$tpl->assign( COLOR , $color );

			if ( $rows1[2] != NULL )
				$name = "(".$rows1[1].")".$rows1[2];
			else
				$name = $rows1[1];
			
			$tpl->assign( USERID, $name );
			$tpl->assign( SNO, $rows1[0] );

			if($rows2['handin_time'][ $rows1[0] ] == "" || $rows2['handin_time'][ $rows1[0] ] == "0000-00-00" )
			{
			    //$Q3 = "SELECT due FROM homework where a_id='$work_id' ";
			    //$result3 = mysql_db_query( $DB.$course_id, $Q3 ) or die ('資料庫讀取錯誤!!');
			    //$row3 = mysql_fetch_array( $result3 );
			    //如果還沒到deadline
			    //if( strtotime('Y-m-d') <= strtotime($row3['due']) ){
				if($version == "C")
					$tpl->assign(SDATE,"未繳交");
				else
					$tpl->assign(SDATE,"Haven't Hand in");
			    //}
			    //如果已經到deadline且又未繳交 , 則
			    //else{

			    //}
			}
			else
			{
				$tpl->assign(SDATE,$rows2['handin_time'][ $rows1[0] ]);
			}
			if ( $rows2['grade'][ $rows1[0] ] == NULL || $rows2['grade'][ $rows1[0] ] == "-1" )
				$tpl->assign(SGRADE,"N/A");
			else
				$tpl->assign(SGRADE,$rows2['grade'][ $rows1[0] ]);

			$tpl->assign(WORKID,$work_id);

			if($rows2['public'][ $rows1[0] ] == "0")
			{
				$tpl->assign(PUBWORK,"pubwork");
				if($version == "C")
					$tpl->assign(ISPUB,"公佈學生作業");
				else
					$tpl->assign(ISPUB,"Public_Student's_Homework");
			}
			else
			{
				$tpl->assign(PUBWORK,"unpubwork");
				if($version == "C")
					$tpl->assign(ISPUB,"不公佈學生作業");
				else
					$tpl->assign(ISPUB,"Never_Public_Student's_Homework");
			}
//modified by devon 2005-05-04
//夾帶哪位學生的成績
//*****************************	                                
			$stuid = "id".$i;
			$tpl->assign(ID, $stuid);
			$scoredata = "wgrade".$i;
			$tpl->assign(WGRADE, $scoredata);
			$i++;
//*****************************

			$tpl->parse(ROW,".row");
		}
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此課程尚未有任何學生!");
		else
			show_page( "not_access.tpl" ,"There is no Student in this Class!!");
	}
}//checkstu()

function filelist () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $work_id, $course_id, $version, $sid, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT id FROM user WHERE a_id = '$sid'";
	
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}

	if ( mysql_num_rows($result1) != 0 ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if ( $version == "C" )
			$tpl->define(array(main=>"stuwork.tpl"));
		else
			$tpl->define(array(main=>"stuwork_E.tpl"));
		$tpl->define_dynamic("file_list", "main");
		$tpl->assign( SKINNUM , $skinnum );
		$rows = mysql_fetch_array($result1);
		$work_dir = "../../$course_id/homework/$work_id/".$rows['id'];
		if ( is_dir( $work_dir ) ) {
			$handle = dir($work_dir);
			$i=false;
			while (( $file = $handle->read() ) ) {
				if(strcmp($file,".") !=0 && strcmp($file,"..")&& is_file($work_dir."/".$file)) {   
				// 除了 '.' '..'和非正常檔案(如目錄)之外的檔案輸出
					$tpl->assign("FILE_N", $file);
					$tpl->assign("FILE_LINK", $work_dir."/".$file);
					$tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
					$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file)));
		
				// 顏色控制.
					if($i)
						$tpl->assign("F_COLOR", "#ffffff");
					else
						$tpl->assign("F_COLOR", "#edf3fa");
		
					$i=!$i;
					
					$tpl->parse(ROWF, ".file_list");
					$set_file = 1;
				}
			}
			$handle->close();
		}
		if($set_file==0) {
			$tpl->assign("FILE_N", "");
			$tpl->assign("FILE_SIZE", "");
			$tpl->assign("FILE_DATE", "");
		}
		$tpl->assign(SNO,$sid);
		$tpl->assign(WORKID,$work_id);
		$tpl->assign(STUWORK,"");
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此學生不存在!");
		else
			show_page( "not_access.tpl" ,"No This Student!!");
	}
}
/////////////////////////////////////////////////////////////////////
//modified by devon 2005-04-15
//判斷目錄是否為空的function
function isEmptyDir($dirName)
{
	//echo $dirName;
    $result = true;
    $handle = opendir($dirName);
    while(($file = readdir($handle)) !== false)
    {
        if($file != '.' && $file != '..' && $file !='teacher')
        {
            $result = false;
            break;
        }
    }
    closedir($handle);
    return $result;
}

?>
