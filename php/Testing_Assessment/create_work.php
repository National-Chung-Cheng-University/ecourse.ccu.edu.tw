<?php
require 'fadmin.php';
require '../CoreDescript.php';
update_status ("出新作業");


if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $total_chap, $course_id;
	$group_id = Get_group_id($course_id);

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	//ciel
	$Qd = "SELECT chap_num FROM chap_title ORDER BY chap_num DESC";
	if ( !($resultd = mysql_db_query( $DB.$course_id, $Qd ) ) ) {
		show_page( "not_access.tpl" ,"請先編輯教材章節" );
	}
	$rowd = mysql_fetch_array($resultd);
	$total_chap = $rowd[0];
	
	
	if($action == "editcontent") {
		$row = CheckError();
		if($row == "null")
		{
			if( $version=="C" )
				$message = "請輸入作業名稱、比例和期限!";
			else
				$message = "Please input homework name、ratio and due!";
			show_page_d();
		}
		else if($row == "errorvalue")
		{
			if( $version=="C" )
				$message = "比例須介於0~100之間";
			else
				$message = "Please input ratio between 0 and 100!";
			show_page_d();
		}
		else if($row == "exist")
		{
			if($version == "C")
				$message = "名稱已存在,請更換作業名稱!";
			else
				$message = "This homework name exists, and please change the homework name!";
			show_page_d();
		}
		else  //輸入資料都符合格式 新增入資料庫
		{
			//取出勾選了那些核心能力
			$str = "";
			for($i=0; $i<count($CoreAbility); $i++){
				if($str != "") $str = $str . "," . $CoreAbility[$i];
				else $str = $CoreAbility[$i];
			}

			$Q1 = "INSERT INTO homework (chap_num,name,percentage,due,CoreAbilities) values ('$chap_num','$work_name','$work_ratio','$work_due','$str')";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			$work_id = mysql_insert_id();
			
			$Q3 = "SELECT tc.student_id, u.id FROM take_course tc,user u WHERE tc.course_id = '$course_id' AND tc.student_id = u.a_id and tc.credit ='1' and tc.year='$course_year' and tc.term = '$course_term'";
			if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤!!1" );
			}
			
			while ( $row3 = mysql_fetch_array($result3) ) {
				$Q4 = "INSERT INTO handin_homework (homework_id,student_id, handin_time) values ('$work_id','".$row3[0]."','0000-00-00')";
				mysql_db_query($DB.$course_id,$Q4);
			}
			mkdir( "../../$course_id/homework/$work_id", 0771 );
			chmod( "../../$course_id/homework/$work_id", 0771 );
			mkdir( "../../$course_id/homework/$work_id/teacher", 0771 );
			chmod( "../../$course_id/homework/$work_id/teacher", 0771 );
			show_content ( "que" );
		}
	}
	else if ( $action == "showwork" ) {
		if( $content == "" )
		{
			if($version == "C")
				$message = "未輸入題目!";
			else 
				$message = "Question is Null!";
			show_content( "que" );
		}
		else
		{
			if ( stristr($content,"<html>") == NULL )
				$content=ereg_replace("\n","<BR>\n",$content);

			$Q1 = "UPDATE homework SET question='$content',public='1' WHERE a_id='$work_id'";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			view_content ( "que" );
		}
	}
	else if ( $action == "editans" ) {
		show_content( "ans" );
	}
	else if ( $action == "showans" ) {
		if( $content == "" )
		{
			if($version == "C") {
				$message = "未輸入答案!";
			}
			else {
				$message = "Answer is Null!";
			}
			show_content( "ans" );
		}
		else
		{
			if ( stristr($content,"<html>") == NULL )
				$content=ereg_replace("\n","<BR>\n",$content);

			$Q1 = "UPDATE homework SET answer='$content' WHERE a_id='$work_id'";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			view_content ( "ans" );
		}
	}
	else {
		show_page_d();
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

function CheckError()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $work_name, $work_due, $work_ratio;

	if($work_name == "" || $work_ratio == "" || $work_due == "")
		return "null";
	elseif($work_ratio > 100 || $work_ratio < 0)
		return "errorvalue";
	else
	{
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		
		$Q1 = "SELECT name FROM homework WHERE name='$work_name'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!2" );
		}
		if(mysql_num_rows($result) != 0 )
			return "exist";
		else
			return "ok";
	}
}

function show_page_d () {
	global $message, $work_name, $work_due, $work_ratio, $version, $skinnum,$total_chap,$select_chap_num, $group_id;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define( array(main=>"create_work.tpl") );
	else
		$tpl->define( array(main=>"create_work_E.tpl") );
	//ciel
	if(empty($select_chap_num)){
		$select_chap = "";	
		for($i=0; $i<=$total_chap; $i++){	
			$select_chap = $select_chap."<option value=".$i." >".$i."</option>";
		}
	}else{
		$select_chap = $select_chap."<option value=".$select_chap_num." >".$select_chap_num."</option>";
	}
	if($group_id == 11){
		$CoreAbilities =
			"<tr bgcolor='#F0FFEE'><td align=left bgcolor='#E6FFFC'><div align='center'>核心能力 (Core Abilities)：". 
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
			<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"11\">4.2".
			"</div></td></tr>";
	}
	else if($group_id==12){
		$CoreAbilities =
			"<tr bgcolor='#F0FFEE'><td align=left bgcolor='#E6FFFC'><div align='center'>核心能力 (Core Abilities)：". 
			"<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"1\">A1
			<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"2\">A2
			<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"3\">A3
			<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"4\">A4
			<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"5\">A5
			<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"6\">A6
			<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"7\">A7
			<input type=\"checkbox\" name=\"CoreAbility[]\" value=\"8\">A8".
			"</div></td></tr>";
	}
	$tpl->assign(SELECT_CHAP,$select_chap);		
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign(ACT1,"create_work.php");
	$tpl->assign(ACT2,"editcontent");
	$tpl->assign(WORKNAME,$work_name);
	$tpl->assign(WORKDUE,$work_due);
	$tpl->assign(WORKRATIO,$work_ratio);
	$tpl->assign(WORKID,"");
	$tpl->assign(MESSAGE,$message);
	$tpl->assign(CoreAbilities,$CoreAbilities);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
	echo CADes($group_id);
}

function show_content ( $type ) {
	global $message, $work_name, $work_due, $work_ratio, $work_id, $content, $version, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define(array(main=>"edit_work.tpl"));
	else
		$tpl->define(array(main=>"edit_work_E.tpl"));
	$tpl->assign( SKINNUM , $skinnum );
	if($version == "C") {
		if ( $type == "ans" ) {
			$tpl->assign(TOPIC,"編輯作業答案");
			$tpl->assign(ACT2,"showans");
		}
		else {
			$tpl->assign(TOPIC,"編輯作業題目");
			$tpl->assign(ACT2,"showwork");
			$tpl->assign(WORKID,$work_id);
		}
	}
	else {
		if ( $type == "ans" ) {
			$tpl->assign(TOPIC,"Edit Answer");
			$tpl->assign(ACT2,"showans");
		}
		else {
			$tpl->assign(TOPIC,"Edit Homework");
			$tpl->assign(ACT2,"showwork");
		}
	}
	

	$tpl->assign(WORKNAME,$work_name);
	$tpl->assign(WORKDUE,$work_due);
	$tpl->assign(WORKRATIO,$work_ratio);
	$tpl->assign(WORKID,$work_id);
	$tpl->assign(CONTENT,$content);
	$tpl->assign(MESSAGE,$message);		
	$tpl->assign(ACT1,"create_work.php");
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
function view_content ( $type ) { 
	global $work_name, $work_due, $work_ratio, $work_id, $content, $version, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C")
		$tpl->define(array(main=>"show_content.tpl"));
	else
		$tpl->define(array(main=>"show_content_E.tpl"));
	$tpl->assign( SKINNUM , $skinnum );
	if($version == "C") {
		if ( $type == "ans" ) {
			$tpl->assign(TYPE,"作業答案");
			$tpl->assign(SUBMIT,"<a href=\"./create_work.php\">完成</a>");
		}
		else {
			$tpl->assign(TYPE,"作業題目");
			$tpl->assign(SUBMIT,"<input type=submit name=submit value=編輯作業答案>");
		}
	}
	else {
		if ( $type == "ans" ) {
			$tpl->assign(TYPE,"Answer");
			$tpl->assign(SUBMIT,"<a href=\"./create_work.php\">Complete</a>");
		}
		else {
			$tpl->assign(TYPE,"Question");
			$tpl->assign(SUBMIT,"<input type=submit name=submit value=\"Edit Answer\">");
		}
		
	}
	$tpl->assign(CONTENT,$content);
	$tpl->assign(WORKNAME,$work_name);
	$tpl->assign(WORKDUE,$work_due);
	$tpl->assign(WORKRATIO,$work_ratio);
	$tpl->assign(WORKID,$work_id);
	$tpl->assign(ACT1,"create_work.php");
	$tpl->assign(ACT2,"editans");
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
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
