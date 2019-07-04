<?php
require 'fadmin.php';
require_once 'my_rja_db_lib.php';

if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
if ( isset($rank_type) && $rank_type != NULL )
	show_rank ();
else
	show_page_d ( );

function show_page_d ( ) {
	global $version, $check, $guest, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->assign( SKINNUM , $skinnum );
	if($version=="C")
		$tpl->define(array(student_rank => "StudentRank1_Ch.tpl"));
	else
		$tpl->define(array(student_rank => "StudentRank1_En.tpl"));
	
	if( $check == 2 )
	{
		if ( $version == "C" ) {
			$tpl->assign(EXTENSION ,"最大比數：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=quantity VALUE=10><BR>\n<INPUT TYPE=RADIO CHECKED NAME=order VALUE=TOP>From Top\n<INPUT TYPE=RADIO NAME=order VALUE=BUTTOM>From Buttom<br>\n<INPUT TYPE=RADIO CHECKED NAME=credit VALUE=1>只顯示正修生\n<INPUT TYPE=RADIO NAME=credit VALUE=0>所有學生");
			$tpl->assign(NOLOGINQUERY ,"<HR>\n查詢超過 n天未登入學生列表<BR>\n<FORM ACTION=NoLoginQuery.php METHOD=POST name=nologin>\n天數：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=days VALUE=5><BR>\n<INPUT TYPE=SUBMIT VALUE=OK OnClick=\"return Check2();\">\n</FORM>");
		}
		else {
			$tpl->assign(EXTENSION ,"Limit：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=quantity VALUE=10><BR>\n<INPUT TYPE=RADIO CHECKED NAME=order VALUE=TOP>From Top\n<INPUT TYPE=RADIO NAME=order VALUE=BUTTOM>From Buttom<br>\n<INPUT TYPE=RADIO CHECKED NAME=credit VALUE=1>Formal Stu.\n<INPUT TYPE=RADIO NAME=credit VALUE=0>All Stu.");
			$tpl->assign(NOLOGINQUERY ,"<HR>\nStu. List of n Day Not Login<BR>\n<FORM ACTION=NoLoginQuery.php METHOD=POST name=nologin>\nDays：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=days VALUE=5><BR>\n<INPUT TYPE=SUBMIT VALUE=OK OnClick=\"return Check2();\">\n</FORM>");
		}
	}
	else
	{	
		if ($guest != 1 )
			$tpl->assign(EXTENSION ,"<INPUT TYPE=RADIO CHECKED NAME=credit VALUE=1>只顯示正修生\n<INPUT TYPE=RADIO NAME=credit VALUE=0>所有學生");
		else
			$tpl->assign(EXTENSION ,"");
		$tpl->assign(NOLOGINQUERY ,NULL);
	}
	
	$tpl->parse(BODY, "student_rank");
	$tpl->FastPrint("BODY");
}

//add by w60292 @ 20091007 找尋參與線上同步教學次數 , $mmcList => Array型態
function find_mmc_num ($mmcList, $stu_ID) {
	$total_num = count($mmcList);
	for($i=0; $i<$total_num; $i++)
	{
		if($mmcList[$i]["stuId"] == $stu_ID)
			return $mmcList[$i]["count"];
	}
	return 0;
}

function show_rank () {
	global $rank_type, $course_id, $version, $check, $user_id, $quantity, $PHPSESSID, $order, $credit, $guest, $skinnum, $course_year, $course_term;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	$tpl->assign( SKINNUM , $skinnum );
	$DefaultQuantity = 15;
	if($check != 2)
	{
		$quantity = $DefaultQuantity;
		$order = "TOP";
	}
	if($quantity <= 0)
		header("Location:StudentRank1.php?PHPSESSID=$PHPSESSID");

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "資料庫連結錯誤!!" );
		return;
	}

	if($version=="C") {
		$tpl->define(array(rank_list => "StudentRank2_Ch.tpl"));
	}
	else {
		$tpl->define(array(rank_list => "StudentRank2_En.tpl"));
	}
	$tpl->define_dynamic("row", "rank_list");
	$tpl->assign(COLOR, "#000066" );
	if($version=="C") {
		$tpl->assign(ORDER,"<font color = #FFFFFF><b>排名</b></font>" );
		$tpl->assign(STUDENT_ID,"<font color = #FFFFFF><b>學號</b></font>" );
		$tpl->assign(STUDENT_NAME,"<font color = #FFFFFF><b>姓名</b></font>" );
	}
	else {
		$tpl->assign(ORDER,"<font color = #FFFFFF><b>Order</b></font>" );
		$tpl->assign(STUDENT_ID,"<font color = #FFFFFF><b>Id</b></font>" );
		$tpl->assign(STUDENT_NAME,"<font color = #FFFFFF><b>Name</b></font>" );
	}
		
	$ch = "check".$order;
	$$ch = "checked";
	if ( $credit == "" )
		$cr1 = "checked";
	else {
		$ch2 = "cr".$credit;
		$$ch2 = "checked";
	}
	if ( $check == 2 ) {
		if ( $version == "C" ) {
			$tpl->assign(NOTIFY,"<TH><font color = #FFFFFF>通知?</font></TH>" );
			$tpl->assign(EXTENSION ,"最大比數：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=quantity VALUE=$quantity><BR>\n<INPUT TYPE=RADIO $checkTOP NAME=order VALUE=TOP>From Top\n<INPUT TYPE=RADIO $checkBUTTOM NAME=order VALUE=BUTTOM>From Buttom<br>\n<INPUT TYPE=RADIO $cr1 NAME=credit VALUE=1>只顯示正修生\n<INPUT TYPE=RADIO $cr0 NAME=credit VALUE=0>所有學生");
			$tpl->assign(NOLOGINQUERY ,"<HR>\n查詢超過 n天未登入學生列表<BR>\n<FORM ACTION=NoLoginQuery.php METHOD=POST name=nologin>\n天數：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=days VALUE=5><BR>\n<INPUT TYPE=SUBMIT VALUE=OK OnClick=\"return Check2();\">\n</FORM>");
		}
		else {
			$tpl->assign(NOTIFY,"<TH><font color = #FFFFFF>Inform?</font></TH>" );
			$tpl->assign(EXTENSION ,"Limit：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=quantity VALUE=$quantity><BR>\n<INPUT TYPE=RADIO $checkTOP NAME=order VALUE=TOP>From Top\n<INPUT TYPE=RADIO $checkBUTTOM NAME=order VALUE=BUTTOM>From Buttom<br>\n<INPUT TYPE=RADIO $cr1 NAME=credit VALUE=1>Credit Stu.\n<INPUT TYPE=RADIO $cr0 NAME=credit VALUE=0>All Stu.");
			$tpl->assign(NOLOGINQUERY ,"<HR>\nStu. List of n Day Not Login<BR>\n<FORM ACTION=NoLoginQuery.php METHOD=POST name=nologin>\nDays：<INPUT TYPE=TEXT MAXLENGTH=4 SIZE=4 NAME=days VALUE=5><BR>\n<INPUT TYPE=SUBMIT VALUE=OK OnClick=\"return Check2();\">\n</FORM>");
		}
	}else {
		if ( $guest != 1 )
			$tpl->assign(EXTENSION ,"<INPUT TYPE=RADIO $cr1 NAME=credit VALUE=1>只顯示正修生\n<INPUT TYPE=RADIO $cr0 NAME=credit VALUE=0>所有學生");
		else
			$tpl->assign(EXTENSION ,"");
		$tpl->assign(NOLOGINQUERY ,NULL);
		$tpl->assign(NOTIFY ,NULL );
	}
	$tpl->assign(MESSAGE,"");
	if($rank_type=="LoginRank")
	{
		$tpl->assign(CH0 ,"selected" );
		if ( $credit == 1 )
			$SQL = "Select student_id From take_course Where course_id = '$course_id' and credit='1' and year='$course_year' and term = '$course_term'";
		else
			$SQL = "Select student_id From take_course Where course_id = '$course_id' and year='$course_year' and term = '$course_term'";
		$resultOBJ = mysql_db_query( $DB, $SQL );
		if($version=="C")
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"登入次數排行(由高而低)");
			else
				$tpl->assign(RANKING_TYPE ,"登入次數排行(由低而高)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>登入次數</b></font>");
		}
		else
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"Name Chart of Login(From TOP)");
			else
				$tpl->assign(RANKING_TYPE ,"Name Chart of Login(From BUTTOM)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>No. of Login</b></font>");
		}
		$tpl->parse(ROWS, ".row");
		if( mysql_num_rows ( $resultOBJ ) == 0 )
		{
			if ( $version == "C" ) {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>目前無任何學生資料</TD></TR>" );
			}
			else {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>No records are available</TD></TR>" );
			}
			$tpl->assign(MAIL_LINK ,NULL );
		}
		else {
			if ( $credit == 1 )
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			else
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			$resultOBJ = mysql_db_query( $DB, $SQL);
			while($row = mysql_fetch_array($resultOBJ))
			{
				$id[$row[a_id]]=$row[id];
				$name[$row[a_id]]=$row[name];
				$email[$row[a_id]]=$row[email];
				$SQL1="Select tag3 From log Where user_id='".$row['a_id']."' AND event_id='2'";
				$result1 = mysql_db_query( $DB.$course_id, $SQL1);
				if($row1 = mysql_fetch_array($result1))
					$record[$row[a_id]]=$row1['tag3'];
				else
					$record[$row[a_id]]=0;
			}
			if($order=="TOP")
				arsort($record);
			else if($order=="BUTTOM")
				asort($record);
			$count=0;

			$color = "#F0FFEE";
			for(reset($record);$key=key($record);next($record))
			{
				if($check == 2)
				{
					if ( $color == "#F0FFEE" )
						$color = "#E6FFFC";
					else
						$color = "#F0FFEE";
					$tpl->assign(COLOR, $color);
					if($email[$key]!=NULL)
					{
						$mail_array[]='\''.$email[$key].'\'';
						$tpl->assign(NOTIFY, "<TD><INPUT TYPE=CHECKBOX NAME=COUNT ></TD>");
						$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$email[$key].">".$name[$key]."</A>");
					}
					else
					{
						$tpl->assign(NOTIFY, "<TD></TD>");
						$tpl->assign(STUDENT_NAME, $name[$key]);
					}
				}
				else
				{
					if($user_id==$id[$key])
						$tpl->assign(COLOR, "#FF0000");
					else {
						if ( $color == "#F0FFEE" )
							$color = "#E6FFFC";
						else
							$color = "#F0FFEE";
						$tpl->assign(COLOR, $color);
					}
					$tpl->assign(NOTIFY, NULL);
					$tpl->assign(STUDENT_NAME, $name[$key]);
				}
				$tpl->assign(ORDER, ++$count);
				$tpl->assign(STUDENT_ID, $id[$key]);
				$tpl->assign(RECORD, $record[$key]);
				$tpl->parse(ROWS, ".row");
				if($count>=$quantity)
					break;
			}
		}
	}
	else if($rank_type=="TimeRank")
	{
		$tpl->assign(CH1 ,"selected" );
		if ( $credit == 1 )
			$SQL="Select student_id From take_course Where course_id='$course_id' and credit = '1' and year='$course_year' and term = '$course_term'";
		else
			$SQL="Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term'";
		$resultOBJ = mysql_db_query( $DB, $SQL );
		if($version=="C")
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"使用時數排行(由高到低)");
			else
				$tpl->assign(RANKING_TYPE ,"使用時數排行(由低到高)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>使用時間(Minute : Second)</b></font>");
		}
		else
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"Name Chart of Staying long(From TOP)");
			else
				$tpl->assign(RANKING_TYPE ,"Name Chart of Staying long(From BUTTOM)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>Time of Staying(Minute : Second)</b></font>");
			
		}
		$tpl->parse(ROWS, ".row");
		if( mysql_num_rows ( $resultOBJ ) == 0 )
		{
			if ( $version == "C" ) {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>目前無任何學生資料</TD></TR>" );
			}
			else {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>No records are available</TD></TR>" );
			}
			$tpl->assign(MAIL_LINK ,NULL );
		}
		else {
			if ( $credit == 1 )
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			else
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			$resultOBJ = mysql_db_query( $DB, $SQL);
			while($row = mysql_fetch_array($resultOBJ))
			{
				$SQL1="Select tag3 From log Where user_id='".$row['a_id']."' AND event_id='7'";
				$result1 = mysql_db_query( $DB.$course_id, $SQL1);
				$row1 = mysql_fetch_array($result1);
				$id[$row[a_id]]=$row[id];
				$name[$row[a_id]]=$row[name];
				$email[$row[a_id]]=$row[email];
				if($row1!=NULL)
					$record[$row[a_id]]=$row1[tag3];
				else
					$record[$row[a_id]]=0;
			}
			if($order=="TOP")
				arsort($record);
			else if($order=="BUTTOM")
				asort($record);
			$count=0;
			$color = "#F0FFEE";
			for(reset($record);$key=key($record);next($record))
			{
				if($check == 2)
				{
					if ( $color == "#F0FFEE" )
						$color = "#E6FFFC";
					else
						$color = "#F0FFEE";
					$tpl->assign(COLOR, $color);
					if($email[$key]!=NULL)
					{
						$mail_array[]='\''.$email[$key].'\'';
						$tpl->assign(NOTIFY, "<TD><INPUT TYPE=CHECKBOX NAME=COUNT ></TD>");
						$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$email[$key].">".$name[$key]."</A>");
					}
					else
					{
						$tpl->assign(NOTIFY, "<TD></TD>");
						$tpl->assign(STUDENT_NAME, $name[$key]);
					}
				}
				else
				{
					if($user_id==$id[$key])
						$tpl->assign(COLOR, "#FF0000");
					else {
						if ( $color == "#F0FFEE" )
							$color = "#E6FFFC";
						else
							$color = "#F0FFEE";
						$tpl->assign(COLOR, $color);
					}
					$tpl->assign(NOTIFY, NULL);
					$tpl->assign(STUDENT_NAME, $name[$key]);
				}
				$tpl->assign(ORDER, ++$count);
				$tpl->assign(STUDENT_ID, $id[$key]);
				$tpl->assign(RECORD, (int)($record[$key]/60) ." : ". $record[$key]%60);
				$tpl->parse(ROWS, ".row");
				if($count>=$quantity)
					break;
			}
		}
	}
	else if($rank_type=="PostRank")
	{
		$tpl->assign(CH2 ,"selected" );
		if ( $credit == 1 )
			$SQL="Select student_id From take_course Where course_id='$course_id' and credit ='1' and year='$course_year' and term = '$course_term'";
		else
			$SQL="Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term'";
		$resultOBJ = mysql_db_query( $DB, $SQL);
		if($version=="C")
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"發表文章次數排行(由高到低)");
			else
				$tpl->assign(RANKING_TYPE ,"發表文章次數排行(由低到高)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>發表次數</b></font>");
		}
		else
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"Name Chart of Posting Articles(From TOP)");
			else
				$tpl->assign(RANKING_TYPE ,"Name Chart of Posting Articles(From BUTTOM)");
			$tpl->assign(RECORD, "<font color = #FFFFFF>No. of Posting Article</b></font>");
		}
		$tpl->parse(ROWS, ".row");
		if( mysql_num_rows ( $resultOBJ ) == 0 )
		{
			if ( $version == "C" ) {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>目前無任何學生資料</TD></TR>" );
			}
			else {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>No records are available</TD></TR>" );
			}
			$tpl->assign(MAIL_LINK ,NULL );
		}
		else {
			if ( $credit == 1 )
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit='1' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			else
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			$resultOBJ = mysql_db_query( $DB, $SQL);
			while($row = mysql_fetch_array($resultOBJ))
			{
				$SQL1="Select tag3 From log Where user_id='".$row[a_id]."' AND event_id='6'";
				$result1 = mysql_db_query( $DB.$course_id, $SQL1);
				$row1 = mysql_fetch_array($result1);
				$id[$row[a_id]]=$row[id];
				$name[$row[a_id]]=$row[name];
				$email[$row[a_id]]=$row[email];
				if($row1!=NULL)
					$record[$row[a_id]]=$row1[tag3];
				else
					$record[$row[a_id]]=0;
			}
			if($order=="TOP")
				arsort($record);
			else if($order=="BUTTOM")
				asort($record);
			$count=0;
		
			$color = "#F0FFEE";
			for(reset($record);$key=key($record);next($record))
			{
				if($check == 2)
				{
					if ( $color == "#F0FFEE" )
						$color = "#E6FFFC";
					else
						$color = "#F0FFEE";
					$tpl->assign(COLOR, $color);
					if($email[$key]!=NULL)
					{
						$mail_array[]='\''.$email[$key].'\'';
						$tpl->assign(NOTIFY, "<TD><INPUT TYPE=CHECKBOX NAME=COUNT ></TD>");
						$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$email[$key].">".$name[$key]."</A>");
					}
					else
					{
						$tpl->assign(NOTIFY, "<TD></TD>");
						$tpl->assign(STUDENT_NAME, $name[$key]);
					}
				}
				else
				{
					if($user_id==$id[$key])
						$tpl->assign(COLOR, "#FF0000");
					else {
						if ( $color == "#F0FFEE" )
							$color = "#E6FFFC";
						else
							$color = "#F0FFEE";
						$tpl->assign(COLOR, $color);
					}
					$tpl->assign(NOTIFY, NULL);
					$tpl->assign(STUDENT_NAME, $name[$key]);
				}
				$tpl->assign(ORDER, ++$count);
				$tpl->assign(STUDENT_ID, $id[$key]);
				$tpl->assign(RECORD, $record[$key]);
				$tpl->parse(ROWS, ".row");
				if($count>=$quantity)
					break;
			}
		}
	}

	/* 
	 * modify by w60292 @ 20091006 修改"聊天次數"為"參與線上同步教學次數"
         */
	else if($rank_type=="TalkRank")
	{
		$tpl->assign(CH3 ,"selected" );
		if ( $credit == 1 )
			$SQL="Select student_id From take_course Where course_id='$course_id' and credit='1' and year='$course_year' and term = '$course_term'";
		else
			$SQL="Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term'";
		$resultOBJ = mysql_db_query( $DB, $SQL);
		if($version=="C")
		{
			
			if($order=="TOP")
				//$tpl->assign(RANKING_TYPE ,"參與聊天次數排行(由高到低)");
				$tpl->assign(RANKING_TYPE ,"參與線上同步教學次數排行(由高到低)");
			else
				//$tpl->assign(RANKING_TYPE ,"參與聊天次數排行(由低到高)");
				$tpl->assign(RANKING_TYPE ,"參與線上同步教學次數排行(由低到高)");
			//$tpl->assign(RECORD, "<font color = #FFFFFF><b>聊天次數</b></font>");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>參與線上同步教學次數</b></font>");
		}
		else
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"Name Chart of Chatting(From TOP)");
			else
				$tpl->assign(RANKING_TYPE ,"Name Chart of Chatting(From BUTTOM)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>No. of Chatting</b></font>");
		}
		$tpl->parse(ROWS, ".row");
		if( mysql_num_rows ( $resultOBJ ) == 0 )
		{
			if ( $version == "C" ) {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>目前無任何學生資料</TD></TR>" );
			}
			else {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>No records are available</TD></TR>" );
			}
			$tpl->assign(MAIL_LINK ,NULL );
		}
		else {
			if ( $credit == 1 )
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit ='1' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			else
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term' Order By id";

			//去mmc server抓取學生的參與線上同步教學次數列表
			$my_result = query_db_to_array($SQL);
                        $my_a_id=Array();
                        foreach($my_result as $value)
			{
                        	$my_a_id[]=$value['a_id'];
                        }

                        require_once('../my_stuJoinMeetingList.php');

                        $stuList = getStuJoinMeetingList($course_id, $my_a_id);
			$mmc_count = count($stuList);
			//資料抓取完成

			$resultOBJ = mysql_db_query( $DB, $SQL);
			while($row = mysql_fetch_array($resultOBJ))
			{
			/* modify by w60292 @ 20091007 原本的"聊天次數"改成"參與線上同步教學次數"
			 *
				$SQL1="Select sum(tag3) as tag3 From log Where user_id='".$row[a_id]."' AND event_id='4'";
				$result1 = mysql_db_query( $DB.$course_id, $SQL1);
				$row1 = mysql_fetch_array($result1);
			 */

				$id[$row[a_id]]=$row[id];
				$name[$row[a_id]]=$row[name];
				$email[$row[a_id]]=$row[email];
				//if(!empty($row1[tag3]))
					//$record[$row[a_id]]=$row1[tag3]);
				if($mmc_count != 0)
					$record[$row[a_id]] = find_mmc_num($stuList, $row[a_id]);
				else
					$record[$row[a_id]] = 0;
			}
			//end modify by w60292
			if($order=="TOP")
				arsort($record);
			else if($order=="BUTTOM")
				asort($record);
			$count=0;
			$color = "#F0FFEE";
			for(reset($record);$key=key($record);next($record))
			{
				if($check == 2)
				{
					if ( $color == "#F0FFEE" )
						$color = "#E6FFFC";
					else
						$color = "#F0FFEE";
					$tpl->assign(COLOR, $color);
					if($email[$key]!=NULL)
					{
						$mail_array[]='\''.$email[$key].'\'';
						$tpl->assign(NOTIFY, "<TD><INPUT TYPE=CHECKBOX NAME=COUNT ></TD>");
						$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$email[$key].">".$name[$key]."</A>");
					}
					else
					{
						$tpl->assign(NOTIFY, "<TD></TD>");
						$tpl->assign(STUDENT_NAME, $name[$key]);
					}
				}
				else
				{
					if($user_id==$id[$key])
						$tpl->assign(COLOR, "#FF0000");
					else {
						if ( $color == "#F0FFEE" )
							$color = "#E6FFFC";
						else
							$color = "#F0FFEE";
						$tpl->assign(COLOR, $color);
					}
					$tpl->assign(NOTIFY, NULL);
					$tpl->assign(STUDENT_NAME, $name[$key]);
				}
				$tpl->assign(ORDER, ++$count);
				$tpl->assign(STUDENT_ID, $id[$key]);
				$tpl->assign(RECORD, $record[$key]);
				$tpl->parse(ROWS, ".row");
				if($count>=$quantity)
					break;
			}
		}
	}
	else if($rank_type=="PageRank")
	{
		$tpl->assign(CH4 ,"selected" );
		if ( $credit == 1 )
			$SQL="Select student_id From take_course Where course_id='$course_id' and credit ='1' and year='$course_year' and term = '$course_term'";
		else
			$SQL="Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term'";
		$resultOBJ = mysql_db_query( $DB, $SQL);
		if($version=="C")
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"學生瀏覽教材次數排行(由高到低)");
			else
				$tpl->assign(RANKING_TYPE ,"學生瀏覽教材次數排行(由低到高)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>瀏覽教材次數</b></font>");
		}
		else
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"Name Chart of Students for borwsing teaching contents(From TOP)");
			else
				$tpl->assign(RANKING_TYPE ,"Name Chart of Students for borwsing teaching contents(From BUTTOM)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>No. of Browsing teaching contents</b></font>");
		}
		$tpl->parse(ROWS, ".row");
		if( mysql_num_rows ( $resultOBJ ) == 0 )
		{
			if ( $version == "C" ) {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>目前無任何學生資料</TD></TR>" );
			}
			else {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>No records are available</TD></TR>" );
			}
			$tpl->assign(MAIL_LINK ,NULL );
		}
		else {
			if ( $credit == 1 )
				$SQL="Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit ='1' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			else
				$SQL="Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			$resultOBJ = mysql_db_query( $DB, $SQL);
			while($row = mysql_fetch_array($resultOBJ))
			{
				$chapterResult = mysql_db_query( $DB.$course_id, "Select * From chap_title Where sect_num=0");
				if(mysql_num_rows ( $chapterResult ) == 0)
				{
					$SQL1="Select tag3 From log Where user_id='".$row['a_id']."' AND event_id='3' AND tag1='0' AND tag4='0'";
					$result1 = mysql_db_query( $DB.$course_id, $SQL1);
					$row1 = mysql_fetch_array($result1);
					$count = $row1[tag3];
				}
				else
				{
					$SQL1="Select tag3 From log Where user_id='".$row['a_id']."' AND event_id='3' AND tag1!='0'";
					$result1 = mysql_db_query( $DB.$course_id, $SQL1);
					$count=0;
					while($row1 = mysql_fetch_array($result1))
						$count+=$row1[tag3];
				}
				$id[$row[a_id]]=$row[id];
				$name[$row[a_id]]=$row[name];
				$email[$row[a_id]]=$row[email];
				$record[$row[a_id]]=$count;
			}
			if($order=="TOP")
				arsort($record);
			else if($order=="BUTTOM")
				asort($record);
			$count=0;
			$color = "#F0FFEE";
			for(reset($record);$key=key($record);next($record))
			{
				if($check == 2)
				{
					if ( $color == "#F0FFEE" )
						$color = "#E6FFFC";
					else
						$color = "#F0FFEE";
					$tpl->assign(COLOR, $color);
					if($email[$key]!=NULL)
					{
						$mail_array[]='\''.$email[$key].'\'';
						$tpl->assign(NOTIFY, "<TD><INPUT TYPE=CHECKBOX NAME=COUNT ></TD>");
						$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$email[$key].">".$name[$key]."</A>");
					}
					else
					{
						$tpl->assign(NOTIFY, "<TD></TD>");
						$tpl->assign(STUDENT_NAME, $name[$key]);
					}
				}
				else
				{
					if($user_id==$id[$key])
						$tpl->assign(COLOR, "#FF0000");
					else {
						if ( $color == "#F0FFEE" )
							$color = "#E6FFFC";
						else
							$color = "#F0FFEE";
						$tpl->assign(COLOR, $color);
					}
					$tpl->assign(NOTIFY, NULL);
					$tpl->assign(STUDENT_NAME, $name[$key]);
				}
				$tpl->assign(ORDER, ++$count);
				$tpl->assign(STUDENT_ID, $id[$key]);
				$tpl->assign(RECORD, $record[$key]);
				$tpl->parse(ROWS, ".row");
				if($count>=$quantity)
					break;
			}
		}
	}
	else if($rank_type=="TextRank")
	{
		$tpl->assign(CH5 ,"selected" );
		if ( $credit == 1 )
			$SQL = "Select student_id From take_course Where course_id = '$course_id' and credit='1' and year='$course_year' and term = '$course_term'";
		else
			$SQL = "Select student_id From take_course Where course_id = '$course_id' and year='$course_year' and term = '$course_term'";
		$resultOBJ = mysql_db_query( $DB, $SQL );
		if($version=="C")
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"瀏覽教材總時間排行(由高而低)");
			else
				$tpl->assign(RANKING_TYPE ,"瀏覽教材總時間排行(由低而高)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>瀏覽總時間</b></font>");
		}
		else
		{
			if($order=="TOP")
				$tpl->assign(RANKING_TYPE ,"Name Chart of Students for borwsing teaching contents Time(From TOP)");
			else
				$tpl->assign(RANKING_TYPE ,"Name Chart of Students for borwsing teaching contents Time(From BUTTOM)");
			$tpl->assign(RECORD, "<font color = #FFFFFF><b>Time of Browsing teaching contents</b></font>");
		}
		$tpl->parse(ROWS, ".row");

		if( mysql_num_rows ( $resultOBJ ) == 0 )
		{
			if ( $version == "C" ) {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>目前無任何學生資料</TD></TR>" );
			}
			else {
				$tpl->assign(MESSAGE,"<TR bgcolor=\"#FF0000\"><TD nowrap align=center colspan=5>No records are available</TD></TR>" );
			}
			$tpl->assign(MAIL_LINK ,NULL );
		}
		else {
			if ( $credit == 1 )
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			else
				$SQL = "Select u.* From user u,take_course tc Where u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$course_year' and tc.term = '$course_term' Order By id";
			$resultOBJ = mysql_db_query( $DB, $SQL);
			while($row = mysql_fetch_array($resultOBJ))
			{
				$id[$row[a_id]]=$row[id];
				$name[$row[a_id]]=$row[name];
				$email[$row[a_id]]=$row[email];
				$SQL1="Select tag3 From log Where user_id='".$row['a_id']."' AND event_id='11' and tag1 != '' and tag4 != ''";
				$SQL2="delete From log Where user_id='".$row['a_id']."' AND event_id='11' and tag1 = '' and tag4 = ''";
				mysql_db_query( $DB.$course_id, $SQL2);
				$result1 = mysql_db_query( $DB.$course_id, $SQL1);
				$record[$row[a_id]]=0;
				while ( $row1 = mysql_fetch_array($result1) ) {
					$record[$row[a_id]] += $row1['tag3'];
				}	
			}
			if($order=="TOP")
				arsort($record);
			else if($order=="BUTTOM")
				asort($record);
			$count=0;

			$color = "#F0FFEE";
			for(reset($record);$key=key($record);next($record))
			{
				if($check == 2)
				{
					if ( $color == "#F0FFEE" )
						$color = "#E6FFFC";
					else
						$color = "#F0FFEE";
					$tpl->assign(COLOR, $color);
					if($email[$key]!=NULL)
					{
						$mail_array[]='\''.$email[$key].'\'';
						$tpl->assign(NOTIFY, "<TD><INPUT TYPE=CHECKBOX NAME=COUNT ></TD>");
						$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$email[$key].">".$name[$key]."</A>");
					}
					else
					{
						$tpl->assign(NOTIFY, "<TD></TD>");
						$tpl->assign(STUDENT_NAME, $name[$key]);
					}
				}
				else
				{
					if($user_id==$id[$key])
						$tpl->assign(COLOR, "#FF0000");
					else {
						if ( $color == "#F0FFEE" )
							$color = "#E6FFFC";
						else
							$color = "#F0FFEE";
						$tpl->assign(COLOR, $color);
					}
					$tpl->assign(NOTIFY, NULL);
					$tpl->assign(STUDENT_NAME, $name[$key]);
				}
				$tpl->assign(ORDER, ++$count);
				$tpl->assign(STUDENT_ID, $id[$key]);
				$tpl->assign(RECORD, (int)($record[$key]/60) ." : ". $record[$key]%60);
				//$tpl->assign(RECORD, $record[$key]);
				$tpl->parse(ROWS, ".row");
				if($count>=$quantity)
					break;
			}
		}
	}

	if(count($mail_array)!=0)
	{
		$mail_list=implode(",",$mail_array);
		$tpl->assign(MAIL_LIST, $mail_list);
	}
	else
		$tpl->assign(MAIL_LIST, NULL);

	if($check == 2)
	{
		if ( $version == "C" )
			$tpl->assign(MAIL_LINK ,"<A HREF=mailto:^_^ OnClick =\"this.href = 'mailto:' + getEmailAddress() + '?subject=通知信'\">送出通知信件</A>" );
		else
			$tpl->assign(MAIL_LINK ,"<A HREF=mailto:^_^ OnClick =\"this.href = 'mailto:' + getEmailAddress() + '?subject=Notify Mail'\">Send Notify Mail</A>" );
	}
	else
	{
		$tpl->assign(MAIL_LINK ,NULL);
	}
	$tpl->parse(BODY, "rank_list");
	$tpl->FastPrint("BODY");
}
?>
