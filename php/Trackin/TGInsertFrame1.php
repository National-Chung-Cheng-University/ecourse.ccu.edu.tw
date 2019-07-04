<?php
require 'fadmin.php';
require '../CoreDescript.php';
update_status ("處理成績");

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if($action == "newtest")
	{
		$row = CheckError();
		if( $row == "null" )
		{
			if($version == "C")
				$message = "考試名稱或所佔比例尚未輸入!";
			else
				$message = "Please Input Test Name Or Ratio !";
			show_page_d();
		}
		elseif($row == "errorvalue")
		{
			if($version == "C")
				$message = "比例須介於0~100之間!";
			else
				$message = "Please Input Ratio Between 0 And 100 !";
			show_page_d();
		}
		elseif($row == "exist")
		{
			if($version == "C")
				$message = $test_name."已存在,請更換考試名稱!";
			else
				$message = $test_name."This Test Name Exists, And Please Change The Test Name!";
			show_page_d();
		}
		else
		{
			//取出勾選了那些核心能力
            $str = "";
            for($i=0; $i<count($CoreAbility); $i++){
                if($str != "") $str = $str . "," . $CoreAbility[$i];
                else $str = $CoreAbility[$i];
            }
			$Q1 = "INSERT INTO exam (name,is_online,public,percentage,random,CoreAbilities) values ('$test_name','0','1','$test_ratio','0','$str')";
			if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			$exam_id = mysql_insert_id();
			$Q2 = "SELECT u.id, u.name, u.a_id FROM user u,take_course tc WHERE tc.student_id = u.a_id AND tc.course_id = '$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' ORDER BY u.id";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
			}

			if( mysql_num_rows($result2) != 0 )
			{
				global $message, $test_name, $test_ratio, $version;
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate("./templates");
				if( $version == "C" )
					$tpl->define(array(main=>"TGInsertFrame2.tpl"));
				else
					$tpl->define(array(main=>"TGInsertFrame2_E.tpl"));
				$tpl->define_dynamic("row","main");
				$tpl->assign( SKINNUM , $skinnum );
				$color = "#E6FFFC";
				
				$tpl->assign(SMARK2,"");
				$tpl->assign(SMARK3,"");
				$tpl->assign(EMARK3,"");
				while ( $rows2 = mysql_fetch_array($result2) ) {
					if ( $color == "#F0FFEE" )
						$color = "#E6FFFC";
					else
						$color = "#F0FFEE";
					$tpl->assign( COLOR , $color );
					$Q3 = "INSERT INTO take_exam ( exam_id, grade, student_id ) values ('$exam_id', '-1', '$rows2[2]')";
					if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
						echo ( "資料庫讀取錯誤!!" );
						exit;
					}
					$tpl->assign(SNAME1,$rows2[1]);
					$tpl->assign(SNO1,$rows2[0]);
					$tpl->assign(SVAR1,$rows2[2]);
					$tpl->assign(GRADE1,"");
					//$tpl->assign(IMG, "a21.gif");
					//------------------應某老師要求修改-___- ----------------------
					if( $rows2 = mysql_fetch_array($result2) ){
						$Q3 = "INSERT INTO take_exam ( exam_id, grade, student_id ) values ('$exam_id', '-1', '$rows2[2]')";
						if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
							echo ( "資料庫讀取錯誤!!" );
							exit;
						}
						$tpl->assign(SNAME2,$rows2[1]);
						$tpl->assign(SNO2,$rows2[0]);
						$tpl->assign(SVAR2,$rows2[2]);
						$tpl->assign(GRADE2,"");
					}
					else{
						$tpl->assign(SMARK2,"<td bgcolor=\"#FFFFFF\"></td><td bgcolor=\"#FFFFFF\"></td><td bgcolor=\"#FFFFFF\"></td><!-- ");
						$tpl->assign(EMARK3," -->");
					}
					
					if( $rows2 = mysql_fetch_array($result2) ){
						$Q3 = "INSERT INTO take_exam ( exam_id, grade, student_id ) values ('$exam_id', '-1', '$rows2[2]')";
						if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
							echo ( "資料庫讀取錯誤!!" );
							exit;
						}
						$tpl->assign(SNAME3,$rows2[1]);
						$tpl->assign(SNO3,$rows2[0]);
						$tpl->assign(SVAR3,$rows2[2]);
						$tpl->assign(GRADE3,"");
					}
					else{
						$tpl->assign(SMARK3,"<td bgcolor=\"#FFFFFF\"></td><td bgcolor=\"#FFFFFF\"></td><td bgcolor=\"#FFFFFF\"></td><!-- ");
						$tpl->assign(EMARK3," -->");
					}
					//------------------------------------------------------------------
					
					$tpl->parse(ROWS,".row");
				}
				$tpl->assign(EXAMID,$exam_id);
				$tpl->assign(ACT1,"TGInsertFrame1.php");
				$tpl->assign(ACT2,"insertgrade");
				$tpl->parse(BODY,"main");
				$tpl->FastPrint("BODY");
			}
			else
			{
				if( $version=="C" )
					show_page( "not_access.tpl" ,"此課程尚未有任何學生!");
				else
					show_page( "not_access.tpl" ,"There is no Student in this Class!!");
			}
		}
	}
	elseif($action == "insertgrade")
	{
		$Q1 = "SELECT u.id, u.name, u.a_id FROM user u,take_course tc WHERE tc.student_id = u.a_id AND tc.course_id = '$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' ORDER BY u.id";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		while ( $rows1 = mysql_fetch_array($result1) )
		{
			$Q0 = "select * from take_exam where exam_id='$exam_id' and student_id='".$rows1[2]."'";
			$result0 = mysql_db_query($DB.$course_id, $Q0);
			$nums = mysql_num_rows($result0);
			
			$grade = "G".$rows1['a_id'];
			if( $nums != 0)
			{
				if ( $$grade != "" ) {
					$gradev = $$grade;
					$Q2 = "UPDATE take_exam SET grade='$gradev' WHERE exam_id='$exam_id' AND student_id='$rows1[2]'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
					}
				}
				else {
					$Q2 = "UPDATE take_exam SET grade=-1 WHERE exam_id='$exam_id' AND student_id='$rows1[2]'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
					}
				}
			}
			else
			{
				if($$grade != "")
				{
					$gradev = $$grade;
					$Q2 = "insert into take_exam (grade, exam_id, student_id) values ('$gradev', '$exam_id', '$rows1[2]')";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤22!!" );
					}
				}/*
				else
				{
					$Q2 = "UPDATE take_exam SET grade=-1 WHERE exam_id='$exam_id' AND student_id='$rows1[2]'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
					}
				}*/
			}
		}
		if($version == "C")
			$message = "成績新增完成!";
		else
			$message = "Grade Input Completely!";
		show_page_d ();
	}
	else
		show_page_d();
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

function CheckError( ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $test_name, $test_ratio;

	if($test_name == "" || $test_ratio == "")
		return "null";
	elseif($test_ratio > 100 || $test_ratio < 0)
		return "errorvalue";
	else
	{
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		$Q1 = "SELECT name FROM exam WHERE name='$test_name'";
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
		}
		
		if( mysql_num_rows($result1) != 0 )
			return "exist";
		else
			return "ok";
	}
}

function show_page_d () {
	global $message, $test_name, $test_ratio, $version, $skinnum, $course_id;
	$group_id = Get_group_id($course_id);

    if($group_id == 11){
        $CoreAbilities =
            "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"1\">1.1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"2\">1.2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"3\">1.3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"4\">2.1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"5\">2.2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"6\">2.3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"7\">3.1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"8\">3.2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"9\">3.3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"10\">4.1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"11\">4.2";
    }
    else if($group_id==12){
        $CoreAbilities =
            "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"1\">A1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"2\">A2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"3\">A3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"4\">A4
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"5\">A5
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"6\">A6
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"7\">A7
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"8\">A8";
    }
    else if($group_id==15){
        $CoreAbilities =
            "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"1\">B1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"2\">B2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"3\">B3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"4\">B4
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"5\">B5
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"6\">B6
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"7\">B7
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"8\">B8
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"9\">B9
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"10\">B10
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"11\">B11
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"12\">B12";
    }

    else if($group_id==16){
        $CoreAbilities =
            "<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"1\">D1
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"2\">D2
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"3\">D3
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"4\">D4
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"5\">D5
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"6\">D6
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"7\">D7
            <input type=\"checkbox\" name=\"CoreAbility[]\" value=\"8\">D8";
    }


	


	
	include("class.FastTemplate.php3");
	$tpl=new FastTemplate("./templates");
	if( $version == "C" )
		$tpl->define(array(main=>"TGInsertFrame1.tpl"));
	else
		$tpl->define(array(main=>"TGInsertFrame1_E.tpl"));
	$tpl->assign(MESSAGE, $message);
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign(ACT1,"TGInsertFrame1.php");
	$tpl->assign(ACT2,"newtest");
	$tpl->assign(TESTNAME,$test_name);
	$tpl->assign(RATIO,$test_ratio);
	$tpl->assign(IMG, "a21.gif");
	$tpl->assign(CoreAbilities,$CoreAbilities);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");

	echo CADes($group_id); 
}

function Get_group_id($a_id){
        //SQL Server的資料
        global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

        //從資料庫取得group_id
        $SQL_Select = "SELECT group_id FROM course WHERE a_id = '$a_id'";
        if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
                $message = "function Get_group_id($a_id) 資料庫讀取錯誤!!<br>";
                echo $message;
        }
        $row = mysql_fetch_array( $result );

        return $row['group_id'];
}

?>
