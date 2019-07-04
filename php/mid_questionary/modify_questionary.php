<?php
require 'fadmin.php';
//require './templates/top.tpl';
update_status ("編輯問卷");
/* modifynr:修改名稱與屬性 modifyquestionary:修改問卷 showquestionary:試填問卷  
showtotal:觀看問卷統計(記名) showalltotal:(不記名) showdetail:?
pubquestionary:問卷發佈設定  show_page_d ():show問卷 */
if( isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2) )
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if($action == "modifynr") //修改名稱與屬性
	{
		$Q1 = "SELECT year, term, name FROM mid_subject WHERE a_id='$q_id'";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		$rows = mysql_fetch_array($result);
		$year = $rows['year'];
		$term = $rows['term'];
		$questionary_name = $rows['name'];
		//$is_named = $rows['is_named'];
		//$is_once = $rows['is_once'];
		modifynr ();
	}
	else if($action == "modifyquestionarynr") //修改問卷
	{
		$row = CheckError();
		if($row == "null")
		{
			if($version == "C")
				$message = "請輸入問卷名稱!";
			else
				$message = "Please input questionary name!";
			modifynr ();
		}
		/*elseif($row == "exist")
		{
			if($version == "C")
				$message = $questionary_name."已存在,請更換問卷名稱!";
			else
				$message = "This questionary name $questionary_name exist, and please change the questionary name!";
			modifynr ();
		}*/
		else
		{
			$Q1 = "UPDATE mid_subject set name = '$questionary_name', year = '$year', term = '$term' where a_id = '$q_id'";
			
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
			if($version == "C")
				$message = "問卷名稱和屬性修改成功!";
			else
				$message = "Update Successfully !";
			show_page_d ();
		}
	}
	else if($action == "showquestionary")
	{
		$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5 FROM mid_question WHERE q_id='$q_id' and type='3' order by a_id";
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"showquestionary.tpl"));
			if($version == "C") {
				$tpl->assign(IMG,"img");
				$tpl->assign(SUBMIT,"繳交問卷");
				$tpl->assign(RESET,"重新填寫");
				$tpl->assign(QDISABLE,"disabled");
			}
			else {
				$tpl->assign(IMG,"img_E");
				$tpl->assign(SUBMIT,"Complete");
				$tpl->assign(RESET,"Reset");
			}
			$tpl->define_dynamic("rows","main");
			$qcounter = 0;
			while ( $rows = mysql_fetch_array($result) ) {
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(TYPE,"");
				//$tpl->assign(QGRADE,"");
				$tpl->assign(QNO,"");
				$tpl->parse(ROWS,".rows");
				
				$Q2 = "SELECT q_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple FROM mid_question WHERE q_id='$q_id' and block_id='".$rows['a_id']."' and type != '3' order by a_id";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				while ( $rows2 = mysql_fetch_array($result2) ) {
					$qcounter ++;
					$tpl->assign(QNO,$qcounter.".");
					$tpl->assign(QUESTION,$rows2['question']);
					$tpl->assign(SEL,"");
					if ( $rows2['type'] == "1" ) {
						if($rows2['ismultiple'] == "0") {
								if($version == "C") {
									$tpl->assign(TYPE,"(單選題)");
								}
								else {
									$tpl->assign(TYPE,"Single-select)");
								}
								$tpl->define(array(cont=>"showquestionarys.tpl"));
								$tpl->define_dynamic("row","cont");
								for ( $i = 0 ; $i < 5 ; $i ++ ) {
									$j = $i + 1;
									$sele = "selection".$j;
									if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
										$tpl->assign(ORDER, $j);
										$tpl->assign(VALUE,pow(2,$i));  //?
										$tpl->assign(QUES, $rows2["$sele"]);
										$tpl->parse(CONT,".row");
									}
								}
						}
						else {
								if($version == "C") {
									$tpl->assign(TYPE,"(複選題)");
								}
								else {
									$tpl->assign(TYPE,"Multi-select)");
								}
								$tpl->define(array(cont=>"showquestionarym.tpl"));
								$tpl->define_dynamic("row","cont");
								for ( $i = 0 ; $i < 5 ; $i ++ ) {
									$j = $i + 1;
									$sele = "selection".$j;
									if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
										$tpl->assign(ORDER, $j);
										$tpl->assign(QUES, $rows2["$sele"]);
										$tpl->parse(CONT,".row");
									}
								}																
						}
					}
					else if ( $rows2['type'] == "2" ) {
						if($version == "C") {
							$tpl->assign(TYPE,"(簡答題)");
						}
						else {
							$tpl->assign(TYPE,"Q&A)");
						}
						$tpl->define(array(cont=>"showquestionaryf.tpl"));
						$tpl->assign(TEXTAREA, "");
					}
					//讓parse重新抓cont指向的檔案 特殊用法
					$tpl->parse(ROWS,".rows");
					$tpl->parse(ROWS,".cont");
					$tpl->row = "";
					$tpl->CONT = "";
					$tpl->cont = "";
				}
			}
			$tpl->assign(QID,$q_id);
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else {
			if($version == "C")
				$message = "此題沒有題目!!<br>請至 '修改問卷' 增加題目";
			else
				$message = "There is no Question on this Exam!!";
			show_page_d ();
		}
	}
	else if($action == "modifyquestionary")
		modifyquestionary ();
/*	else if($action == "updatequestionary") */
	else if($action == "deletequestion") {
		$Q1 = "DELETE FROM tiku WHERE a_id='$a_id'";  //tiku?
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫刪除錯誤!!" );
		}
		if ( $version == "C" )
			$message = "刪除完成";
		else
			$message = "Question is delete";
		$a_id = "";
		modifyquestionary ();
	}
/*	elseif($action == "insertquestionary")
*/	else if($action == "showtotal")
		showtotal();
	else if($action == "detail")
		showdetail();
/*	else if($action == "requestionary") {
*/	else if($action == "deletequestionary")
	{
		$Q0 = "Select name FROM mid_subject WHERE a_id='$q_id'";
		if ( !($result = mysql_db_query( $DB, $Q0 ) ) ) {
			$message ="資料庫讀取錯誤!!";
			show_page_d();
			exit;
		}
		$rows = mysql_fetch_array($result);
		$Q1 = "DELETE FROM mid_subject WHERE a_id='$q_id'";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q2 = "DELETE FROM mid_question WHERE q_id='$q_id'";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		/*$Q3 = "DELETE FROM take_questionary WHERE q_id='$q_id'";
		if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}
		$Q4 = "Drop table questionary_".$q_id;
		if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
			$message ="資料庫刪除錯誤!!";
			show_page_d();
			exit;
		}*/
		if ( $version == "C" )
			$message = $rows['name']." 刪除完成!";
		else
			$message = $rows['name']." Delete Complete";
		show_page_d();
	}
	else if($action == "pubquestionary")
		pubquestionary();
	else if($action == "modifypubquestionary") {
		if ( $pub == 1 ) {
			$beg_time=$sel_year.$sel_month.$sel_day.$sel_hour.$sel_minute."00";
			$end_time=$ed_year.$ed_month.$ed_day."235959";
			$check_range = timecount($ed_year, $ed_month, $ed_day, date(H), date(i), $sel_year, $sel_month, $sel_day, $sel_hour, $sel_minute);
		}
		else {
			$beg_time=$sel_year.$sel_month.$sel_day.$sel_hour.$sel_minute."00";
			$year=(int) $sel_year;
			$month=(int) $sel_month;
			$day=(int) $sel_day;
			$hour=(int) $sel_hour;
			$minute=(int) $sel_minute;
			switch($range)
			{
				case 1: $hour=$hour + 1;
					break;
				case 2: $hour=$hour + 2;
					break;
				case 4: $hour=$hour + 4;
					break;
				case 8: $hour=$hour + 8;
					break;
			}
			if($hour > 23)
			{
				$hour=$hour - 24;
				$day++;
			}
			if($day > 31)
			{
				$day=$day - 31;
				$month++;
			}
			if($month > 12)
			{
				$month=$month - 12;
				$year++;
			}
			if($minute < 10)
				$minute="0".$minute;
			if($hour < 10)
				$hour="0".$hour;
			if($day < 10)
				$day="0".$day;
			if($month < 10)
				$month="0".$month;
			$second = "00";
			$end_time=$year.$month.$day.$hour.$minute.$second;
		}
			
		if ( !isset($range) && $sure != "取消發佈" && $sure != "Not Public" && $pub != "1") {		
			if ( $version == "C" )
				$message = "請選擇時間間隔!!";
			else
				$message = "Please Choise Rang!!!";
		}	
		else {
			if ( $sure == "取消發佈" || $sure == "Not Public" ) {
				$now=date("YmdHis");
				$Q1 = "UPDATE mid_subject SET beg_time='$now',end_time='00000000000000',is_public='0' WHERE a_id='$q_id'";
			}
			else if($pub == 1)
			{
				if ( $check_range < 0 ) {
					if ( $version == "C" )
						$message = "設定錯誤!!";
					else
						$message = "Setup failure!!!";
					pubquestionary();
					exit;
				}
				$Q1 = "UPDATE mid_subject SET beg_time='$beg_time',end_time='$end_time',is_public='1' WHERE a_id='$q_id'";
			}
			else {
				$Q1 = "UPDATE mid_subject SET beg_time='$beg_time',end_time='$end_time',is_public='3' WHERE a_id='$q_id'";
			}
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫更新錯誤!!" );
			}
			if ( $version == "C" )
				$message = "設定完成!!";
			else
				$message = "Setup Complete!!!";
		}
		pubquestionary();
	}
	else
		show_page_d();
}
else
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
	else
		show_page( "not_access.tpl" ,"You have No Permission!!");
}
  
function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT year, term, name, a_id FROM mid_subject ORDER BY a_id";
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}

	if( mysql_num_rows($result) != 0)
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"modify_questionary.tpl"));
		else
			$tpl->define(array(main=>"modify_questionary_E.tpl"));
		$tpl->define_dynamic("row","main");
		$color == "#BFCEBD";
		while ( $rows = mysql_fetch_array( $result ) ) {
			if ( $color == "#BFCEBD" )
				$color = "#D0DFE3";
			else
				$color = "#BFCEBD";
			$tpl->assign( COLOR , $color );
			$tpl->assign(QUESYEAR,$rows['year']);
			
			if($rows['term'] == 1) 
				$tpl->assign(QUESTERM,"1");
			else
				$tpl->assign(QUESTERM,"2");
			/*if($rows['is_named'] == 0) {
				if($version == "C")  
					$tpl->assign(QUESTYPE,"不記名");
				else
					$tpl->assign(QUESTYPE,"Unnamed");
			}
			else {
				if($version == "C")
					$tpl->assign(QUESTYPE,"記名");
				else
					$tpl->assign(QUESTYPE,"Named");
			}
			if($version == "C")  
				$tpl->assign(QUESLIMIT,"限填".$rows['is_once']."次");
			else
				$tpl->assign(QUESLIMIT,"limit".$rows['is_once']."Times");*/
			$tpl->assign(QUESNAME,$rows['name']);
			$tpl->assign(QUESID,$rows['a_id']);
			$tpl->parse(ROWS,".row");
		}
		$tpl->assign(MESSAGE,$message);
		$tpl->parse(BODY,"main");
 		$tpl->FastPrint("BODY");
	}
	else
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"目前沒有任何問卷可供修改!");
		else
			show_page( "not_access.tpl" ,"There is no questionary for modification!!");
	}
}

function modifynr () {
	global $message, $year, $term, $questionary_name, $q_id, $version;
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define( array(main=>"create_questionary.tpl") );
		$tpl->assign(BUTTON,"修改");
		
	}
	else {
		$tpl->define( array(main=>"create_questionary_E.tpl") );
		$tpl->assign(BUTTON,"Modify");
	}
	$tpl->assign(IMG,"a332.gif");
	$tpl->assign(ACT1,"modify_questionary.php");
	$tpl->assign(ACT2,"modifyquestionarynr");
	$tpl->assign(YEAR,$year);
	$tpl->assign(QUES_NAME,$questionary_name);
	
	if ( $term == "1") 
		$tpl->assign( TERM1, "checked");
	else 
		$tpl->assign( TERM2, "checked");
	/*if ( $is_once < 10 )
		$R = "R0".$is_once;
	else
		$R = "R".$is_once;
	$tpl->assign( $R, "selected");
	
	if ( $is_named != "1")
		$tpl->assign( NRM_NAME, "selected");
	else
		$tpl->assign( REM_NAME, "selected");*/
	$tpl->assign(QUESID,$q_id);
	$tpl->assign(MESSAGE,$message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}

function CheckError()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $questionary_name, $q_id;

	if($questionary_name == "" )
		return "null";
	else
	{
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		$Q1 = "SELECT name FROM mid_subject WHERE name='$questionary_name' and a_id != '$q_id'";
		if ( !($result = mysql_db_query( $DB, $Q1 )) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
		}
		if( mysql_num_rows($result) != 0 )
			return "exist";
		else
			return "ok";
	}
}

function modifyquestionary () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $q_id, $a_id, $message;

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->define(array(main=>"editor.tpl"));

	$tpl->assign(QUESID,$q_id);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
/*
function show_content 
*/
function showtotal () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $version, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "select * from mid_subject where a_id = '$q_id'";
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if ( mysql_num_rows($result1) != 0 ) {
		$row1 = mysql_fetch_array($result1);
		/*if ( $row1['is_named'] == 1 ) {  //記名
			$Q2 = "SELECT u.a_id, u.id, u.name FROM user u, take_course tc WHERE tc.course_id='$course_id' and tc.student_id = u.a_id and tc.credit = '1'";
			if ( !($result1 = mysql_db_query( $DB, $Q2 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
			}
			if ( mysql_num_rows($result1) != 0 ) {
				include("class.FastTemplate.php3");
				$tpl = new FastTemplate("./templates");
				if($version == "C")
					$tpl->define(array(main=>"showtotal.tpl"));
				else
					$tpl->define(array(main=>"showtotal_E.tpl"));
				$tpl->define_dynamic("total","main");
				$color == "#BFCEBD";
				while ( $rows = mysql_fetch_array($result1) ) {
					if ( $color == "#BFCEBD" )
						$color = "#D0DFE3";
					else
						$color = "#BFCEBD";
					$tpl->assign( COLOR , $color );
					$tpl->assign(SNO,$rows[1]);
					$tpl->assign(SNN,$rows[2]);
					$tpl->assign( AID, $rows['a_id'] );
					$tpl->assign( QID, $q_id );
					$tpl->parse(TOTAL,".total");
				}
				$tpl->parse(BODY,"main");
				$tpl->FastPrint("BODY");
			}
			else {
				if( $version=="C" )
					show_page( "not_access.tpl" ,"此課程尚未有任何學生!!");
				else
					show_page( "not_access.tpl" ,"There is no student in this class!!");
			}
		}
		else {*/
			showalltotal($row1['year'],$row1['term']);  //不記名
		//}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"沒有此問卷!!");
		else
			show_page( "not_access.tpl" ,"Uncorrect Questionary!!");
	}
}

function showdetail () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $a_id, $version, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "select * from questionary_".$q_id." where student_id = '$a_id'"; //xx
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if ( mysql_num_rows($result) != 0 ) {
		$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5 FROM mid_question WHERE q_id='$q_id' and type='3' order by a_id";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result1) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"showdetail.tpl"));
			$tpl->assign(SUBMIT,"");
			$tpl->assign(RESET,"");
			if($version == "C") {
				$tpl->assign(IMG,"img");	
			}
			else {
				$tpl->assign(IMG,"img_E");
			}

			$tpl->define_dynamic("rows","main");
			$qcounter = 0;
			while ( $rows = mysql_fetch_array($result1) ) {
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(TYPE,"");
				//$tpl->assign(QGRADE,"");
				$tpl->assign(QNO,"");
				$tpl->parse(ROWS,".rows");
				
				$Q2 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5 FROM mid_question WHERE q_id='$q_id' and block_id='".$rows['a_id']."' and type != '3' order by a_id";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				while ( $rows2 = mysql_fetch_array($result2) ) {
					$qcounter ++;
					$tpl->assign(QNO,$qcounter);
					$tpl->assign(QUESTION,$rows2['question']);
					$tpl->assign(SEL,"");
					/*if ( $rows2['grade'] != null || $rows2['grade'] != "" ) {
						if ( $version == "C" )
							$tpl->assign(QGRADE,"(".$rows2['grade']."權重 ");
						else
							$tpl->assign(QGRADE,"(".$rows2['grade']."Weight ");
					}*/
					if ( $rows2['type'] == "1" ) {
						/*if($rows2['ismultiple'] == "0") {
							if($version == "C") {
								$tpl->assign(TYPE,"單選題)");
							}
							else {
								$tpl->assign(TYPE,"Single-select)");
							}
						}
						else {
							if($version == "C") {
								$tpl->assign(TYPE,"複選題)");
							}
							else {
								$tpl->assign(TYPE,"Multi-select)");
							}
						}*/
						$tpl->define(array(cont=>"showdetails.tpl"));
						$tpl->define_dynamic("row","cont");
						for ( $i = 0 ; $i < 5 ; $i ++ ) {
							$j = $i + 1;  //next xx
							$S1 = "select count(a_id) as SUM from questionary_".$q_id." where q".$rows2['a_id']." = '$j' and student_id = '$a_id'";
							if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
								echo( "資料庫讀取錯誤!! $S1" );
								exit;
							}
							$row = mysql_fetch_array($s1);
							$sele = "selection".$j;
							if ( $rows2["$sele"] != null || $rows2["$sele"] != "" ) {
								$tpl->assign(ORDER, $j);
								$tpl->assign(QUES, $rows2["$sele"]." <font color=\"#FF0000\">X".$row['SUM']."</font>" );
								$tpl->parse(CONT,".row");
							}
						}
					}
					else if ( $rows2['type'] == "2" ) {
						if($version == "C") {
							$tpl->assign(TYPE,"(簡答題)");
						}
						else {
							$tpl->assign(TYPE,"Q&A)");
						}
						$S1 = "select q".$rows2['a_id']." from questionary_".$q_id." where student_id = '$a_id' and q".$rows2['a_id']." != ''";
						if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
							echo( "資料庫讀取錯誤!! $S1" );
							exit;
						}
						
						$tpl->define(array(cont=>"showdetailf.tpl"));
						
						while ( $row = mysql_fetch_array($s1) ) {
							$answer .= "$row[0]<br>";
						}
						$tpl->assign(TEXTAREA, "$answer");
					}
					//讓parse重新抓cont指向的檔案 特殊用法
					$tpl->parse(ROWS,".rows");
					$tpl->parse(ROWS,".cont");
					$tpl->row = "";
					$tpl->CONT = "";
					$tpl->cont = "";
				}
			}
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else {
			if($version == "C")
				$message = "沒有題目!!";
			else
				$message = "No Question";
			show_page_d ();
		}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"尚未填寫!!");
		else
			show_page( "not_access.tpl" ,"Never full out!!");
	}
}
function showalltotal ($midyear, $midterm) { //不記名
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $a_id, $version, $course_id, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "select * from mid_ans where q_id='$q_id'";
	if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if ( mysql_num_rows($result) != 0 ) {
		$Q1 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5 FROM mid_question WHERE q_id='$q_id' and type='3' order by a_id";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		if ( mysql_num_rows($result1) != 0 ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			$tpl->define(array(main=>"showdetail.tpl"));
			$tpl->assign(SUBMIT,"");
			$tpl->assign(RESET,"");
			if($version == "C") {
				$tpl->assign(IMG,"img");	
			}
			else {
				$tpl->assign(IMG,"img_E");
			}

			$tpl->define_dynamic("rows","main");
			$qcounter = 0;
			while ( $rows = mysql_fetch_array($result1) ) {
				$tpl->assign(QUESTION,$rows['question']);
				$tpl->assign(TYPE,"");
				//$tpl->assign(QGRADE,"");
				$tpl->assign(QNO,"");
				$tpl->parse(ROWS,".rows");
				
				$Q2 = "SELECT a_id, q_id, type, question, selection1, selection2, selection3, selection4, selection5 FROM mid_question WHERE q_id='$q_id' and block_id='".$rows['a_id']."' and type != '3' order by a_id";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
				}
				$k=0;//為了得知第幾題而設的變數
				while ( $rows2 = mysql_fetch_array($result2) ) {
					$k++;
					$qcounter ++;$id_counter++;
					$tpl->assign(QNO,$qcounter);
					$tpl->assign(QUESTION,$rows2['question']);
					$tpl->assign(SEL,"");
					
					if ( $rows2['type'] == "1" )
					{
						if($version == "C")
						{
							$tpl->assign(TYPE,"(單選題)");
						}
						else
						{
							$tpl->assign(TYPE,"Single-select)");
						}
						$tpl->define(array(cont=>"showdetails.tpl"));  
						$tpl->define_dynamic("row","cont");  
						
						$sum1=0;
						$sum2=0;
						$sum3=0;
						$sum4=0;
						$sum5=0;
						$S1 = "select q$k from mid_ans where q_id='$q_id'";
						$results1 = mysql_db_query($DB.$course_id, $S1);
						while( $s1 = mysql_fetch_array($results1))
						{
							if($s1["q$k"] == 1)
								$sum1++;
							else if($s1["q$k"] == 2)
								$sum2++;
							else if($s1["q$k"] ==3)
								$sum3++;
							else if($s1["q$k"] ==4)
								$sum4++;
							else if($s1["q$k"] ==5)
								$sum5++;
						}
						$weight_sum = $sum1*5 + $sum2*4 + $sum3*3 + $sum4*2 + $sum5*1;
						$fill_counter = $sum1 + $sum2 + $sum3 + $sum4 + $sum5;
						if($fill_counter == 0)
						{
							echo "<font size=\"5\" color=\"red\">目前尚未有學生填寫期中問卷!</font>";
							exit;
						}
						else
						{
							$Q5 = "select * from take_course where course_id='$course_id' and year='$midyear' and term='$midterm' and credit='1'";
							$result5 = mysql_db_query($DB, $Q5);
							$nums = mysql_num_rows($result5);
							
							$avg_weight = $weight_sum / $fill_counter;
							$avg_weight_sec = number_format($avg_weight, 2); //number_format是為了取到小數以下第二位，取完後為一個string
							$percent = ( $avg_weight/5 ) * 100;
							$percent_sec = number_format($percent, 2); //number_format是為了取到小數以下第二位，取完後為一個string
							//echo( " 此課程的整體滿意度為： <font color=\"red\">".$avg_weight_sec."</font><br>" ); 
							//echo( " (滿意度介於1~5分) <br>" );
							//echo( " 此課程的整體滿意度百分比為： <font color=\"red\">".$percent_sec."%</font><br>" );
							//echo( " 此課程的填寫率為： <font color=\"red\">".$fill_counter."</font> / ".$nums."<br>" );
							//echo("<hr><br>");
							/*$Q3 = " SELECT * FROM mid_statistic WHERE course_no = $course_id ";
							$result3 = mysql_db_query ( $DB, $Q3 );
							if ( mysql_num_rows ($result3) !=0 )
							{
								$Q4 = " UPDATE mid_statistic SET fill_count = '$fill_counter', satisfy = '$avg_weight_sec' WHERE course_no='$course_id' ";
							}
							else
							{
								$Q4 = " INSERT INTO mid_statistic ( course_no, q_id, fill_count, satisfy ) VALUES ( '$course_id','$rows2[q_id]', '$fill_counter', '$avg_weight_sec' ) ";
							}
							if ( !($result4 = mysql_db_query( $DB, $Q4 ) ) ) {
								show_page( "not_access.tpl" ,"資料庫讀取錯誤$Q4!!" );
							}
							*/
							for ( $j=1; $j<=5; $j++)
							{
								if($j == 1)
									$tempqno = 5;
								else if($j == 2)
									$tempqno = 4;
								else if($j == 3)
									$tempqno = 3;
								else if($j == 4)
									$tempqno = 2;
								else if($j == 5)
									$tempqno = 1;
									
								$sele = "selection".$j;
								$sum = "sum".$j;
								$tpl->assign(ORDER, $tempqno);
								$tpl->assign(QUES, $rows2["$sele"]." X<font color=\"#FF0000\">".$$sum."</font>");
								$tpl->parse(CONT, ".row");
							}
						}
					}
					else if ( $rows2['type'] == "2" ) {
						if($version == "C") {
							$tpl->assign(TYPE,"(簡答題)");
						}
						else {
							$tpl->assign(TYPE,"Q&A)");
						}
						$S1 = "select q$k from mid_ans where q$k != '' and q_id='$q_id'";
						if ( !($s1 = mysql_db_query( $DB.$course_id, $S1 ) ) ) {
							echo( "資料庫讀取錯誤!! $S1" );
							exit;
						}
						$answer = "";
						
						$tpl->define(array(cont=>"showdetailf.tpl")); 
						 
						while ( $row = mysql_fetch_array($s1) ) {  //有幾個人填就跑幾次
							$answer .= "<li>$row[0]</li><br>";					
						}
						$tpl->assign(TEXTAREA, "$answer");
					}
					//讓parse重新抓cont指向的檔案 特殊用法
					$tpl->parse(ROWS,".rows");
					$tpl->parse(ROWS,".cont");
					$tpl->row = "";
					$tpl->CONT = "";
					$tpl->cont = "";
				}
			}
			$tpl->parse(BODY,"main");
 			$tpl->FastPrint("BODY");
		}
		else {
			if($version == "C")
				$message = "沒有題目!!";
			else
				$message = "No Question";
			show_page_d ();
		}
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"尚未填寫!!");
		else
			show_page( "not_access.tpl" ,"Never full out!!");
	}
}
/*function showalltotal () 
*/
function pubquestionary () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $q_id, $version, $course_id, $message;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT name,beg_time,end_time,is_public FROM mid_subject WHERE a_id='$q_id'";
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if ( $version == "C" )
		$tpl->define(array(main=>"pubquestionary.tpl"));
	else
		$tpl->define(array(main=>"pubquestionary_E.tpl"));
	$tpl->define_dynamic("yy","main");
	$tpl->define_dynamic("ye","main");

	$row1 = mysql_fetch_row( $result1 );
	$tpl->assign(QNAME,$row1[0]);
	$tpl->assign(QID,$q_id);
	if(($row1[2] == "00000000000000")&&($row1[3] == "0"))
	{  
		if($version == "C")  
			$tpl->assign(STATUS,"未發佈");
		else
			$tpl->assign(STATUS,"Never Public");
		$end_day = date("d");
		$end_month = date("m");
	}
	else
	{
		//echo $row1[1]."<br>";
		//echo (int) substr($row1[1],0,4)."-";
		//echo (int) substr($row1[1],5,2)."-";
		//echo (int) substr($row1[1],8,2)."-";
		$beg_y = (int) substr($row1[1],0,4);
		$beg_mo = (int) substr($row1[1],5,2);
		$beg_d = (int) substr($row1[1],8,2);
		$beg_h = (int) substr($row1[1],11,2);
		$beg_m = (int) substr($row1[1],14,2);
		$end_y = (int) substr($row1[2],0,4);
		$end_mo = (int) substr($row1[2],5,2);
		$end_d = (int) substr($row1[2],8,2);
		$end_h = (int) substr($row1[2],11,2);
		$end_m = (int) substr($row1[2],14,2);
		$range = timecount($end_y, $end_mo, $end_d, $end_h, $end_m, $beg_y, $beg_mo, $beg_d, $beg_h, $beg_m);
		if ( $row1[3] != 1 ) {
			switch($range)
			{	
				case 1: $tpl->assign(CHECK1,"checked");
					$tpl->assign(CHECK2,"");
					$tpl->assign(CHECK3,"");
					$tpl->assign(CHECK4,"");
					break;
				case 2: $tpl->assign(CHECK1,"");
					$tpl->assign(CHECK2,"checked");
					$tpl->assign(CHECK3,"");
					$tpl->assign(CHECK4,"");
					break;
				case 4: $tpl->assign(CHECK1,"");
					$tpl->assign(CHECK2,"");
					$tpl->assign(CHECK3,"checked");
					$tpl->assign(CHECK4,"");
					break;
				case 8: $tpl->assign(CHECK1,"");
					$tpl->assign(CHECK2,"");
					$tpl->assign(CHECK3,"");
					$tpl->assign(CHECK4,"checked");
					break;
				default:
					if($version == "C")  
						$tpl->assign(STATUS,"未發佈");
					else
						$tpl->assign(STATUS,"Never Public");
					break;
			}
			//$end_day = substr($row1[2],6,2);
			//$end_month = substr($row1[2],4,2);
			$end_day = substr($row1[2],8,2);
			$end_month = substr($row1[2],5,2);
		}
		else {
			//$end_day = substr($row1[2],6,2);
			//$end_month = substr($row1[2],4,2);
			$end_day = substr($row1[2],8,2);
			$end_month = substr($row1[2],5,2);
		}
		if ( $range >= 0 ) {
			if ( $row1[3] != 1 ) {
				if($version == "C" )
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],11,2),substr($row1[1],14,2),0,substr($row1[1],5,2),substr($row1[1],8,2),substr($row1[1],0,4)))."發佈 維期 $range 小時");
				else
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."Public During $range Hourse");
			}
			else {
				if($version == "C" )
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],11,2),substr($row1[1],14,2),0,substr($row1[1],5,2),substr($row1[1],8,2),substr($row1[1],0,4)))."發佈 ".date("Y-m-d H:i",mktime(substr($row1[2],11,2),substr($row1[2],14,2),0,substr($row1[2],5,2),substr($row1[2],8,2),substr($row1[2],0,4)))."結束");
				else
					$tpl->assign(STATUS,date("Y-m-d H:i",mktime(substr($row1[1],8,2),substr($row1[1],10,2),0,substr($row1[1],4,2),substr($row1[1],6,2),substr($row1[1],0,4)))."Public, End at".date("Y-m-d H:i",mktime(substr($row1[2],8,2),substr($row1[2],10,2),0,substr($row1[2],4,2),substr($row1[2],6,2),substr($row1[2],0,4))));
			}
		}
	}
	for($j=0;$j < 3;$j++)
	{
		$year = date("Y")+$j;
		if ( $year == substr( $row1[1], 0, 4 ) )
			$tpl->assign(YEAV, "$year selected");
		else
			$tpl->assign(YEAV,$year);
		$tpl->assign(YEAR,$year);
		$tpl->parse(YY,".yy");
	}
	for($j=0;$j < 3;$j++)
	{
		$year = date("Y")+$j;
		if ( ($j == 2 && $row1[3] != 1 && $row1[3] != 3) || ($year == substr( $row1[2], 0, 4 ) && $row1[3] == 1)  )
			$tpl->assign(YEAEV, "$year selected");
		else
			$tpl->assign(YEAEV,$year);
		$tpl->assign(YEAED,$year);
		$tpl->parse(YE,".ye");
	} 
	$beg_day=substr($row1[1],8,2);
	$beg_month=substr($row1[1],5,2);
	$DV = "DA".$beg_day;
	$MV = "MA".$beg_month;
	$tpl->assign($DV, "selected");	
	$tpl->assign($MV , "selected");
	$bh= substr($row1[1],11,2);
	$bm= substr($row1[1],14,2);
	$HV = "HB".$bh;
	$BV = "MB".$bm;
	$tpl->assign($HV, "selected");	
	$tpl->assign($BV , "selected");
	
	$DEV = "DE".$end_day;
	$MDV = "MOE".$end_month;
	$tpl->assign($DEV, "selected");	
	$tpl->assign($MDV , "selected");
	if ( $row1[3] == 1 )
		$tpl->assign(PUB, "checked" );
	$tpl->assign(MESSAGE, $message);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
?>