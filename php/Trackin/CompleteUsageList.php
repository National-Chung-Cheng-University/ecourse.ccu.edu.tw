<?php
require 'fadmin.php';
//modify this line by rja: �ޥΧڦۤv�� db lib
require_once 'my_rja_db_lib.php';

update_status ("�ǥͧ���ϥΰO��");

if( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) && !(session_is_registered("admin") && $admin == 1) )
{
	show_page( "not_access.tpl" ,"�v�����~");
	exit;
}
if($check < 2 )
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}


global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum, $course_year, $course_term;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "��Ʈw�s�����~!!" );
	return;
}
$Q1 = "Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term' Order By student_id ASC";
if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) )
{
	echo ("��ƮwŪ�����~!!");
	return;
}
else
{
	if( mysql_num_rows ( $resultOBJ ) == 0 )
	{
		if( $version=="C" )
			show_page( "not_access.tpl" ,"���ҵ{�|��������ǥ�!");
		else
			show_page( "not_access.tpl" ,"There is no Student in this Class!!");
	}
	else
	{
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		$tpl->define(array(student_list => "CompleteUsageList.tpl"));

		$tpl->define_dynamic("row", "student_list");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>�Ǹ�</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>�m�W</font></b>" );
			$tpl->assign( LOGIN_TIMES, "<b><font color=#FFFFFF>�n�J����</font></b>" );
			$tpl->assign( LASTLOGIN_TIME, "<b><font color=#FFFFFF>�W���n�J�ɶ�</font></b>" );
			$tpl->assign( STAY_TIME, "<b><font color=#FFFFFF>�ϥήɶ�(Minute:Second)</font></b>" );
			$tpl->assign( POST_TIMES, "<b><font color=#FFFFFF>�o����</font></b>" );
			// modify by w60292 @ 20091006 �N"��Ѧ���"�אּ"�ѻP�u�W�P�B�оǦ���"
			//$tpl->assign( CHAT_TIMES, "<b><font color=#FFFFFF>��Ѧ���</font></b>" );
			$tpl->assign( CHAT_TIMES, "<b><font color=#FFFFFF>�ѻP�u�W�P�B�оǦ���</font></b>" );
			$tpl->assign( BROWSE_TIMES, "<b><font color=#FFFFFF>�Ч��s������</font></b>" );
		}
		else {
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>ID</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>Name</font></b>" );
			$tpl->assign( LOGIN_TIMES, "<b><font color=#FFFFFF>No. of Login</font></b>" );
			$tpl->assign( LASTLOGIN_TIME, "<b><font color=#FFFFFF>Last Login</font></b>" );
			$tpl->assign( STAY_TIME, "<b><font color=#FFFFFF>Time of Staying(Minute:Second)</font></b>" );
			$tpl->assign( POST_TIMES, "<b><font color=#FFFFFF>No. of Posting Article</font></b>" );
			$tpl->assign( CHAT_TIMES, "<b><font color=#FFFFFF>No. of Chatting</font></b>" );
			$tpl->assign( BROWSE_TIMES, "<b><font color=#FFFFFF>No. of Browsing teaching contents</font></b>" );
		}
		$tpl->parse(ROWS, ".row");
		$color = "#BFCEBD";
		if ( $nocredit != 1 )
			$Q1 = "Select u.id, u.email, u.name, u.a_id From user u ,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' Order By u.id ASC";
		else
			$Q1 = "Select u.id, u.email, u.name, u.a_id From user u ,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and tc.credit = '0' and tc.year='$course_year' and tc.term = '$course_term' Order By u.id ASC";
		$result = mysql_db_query( $DB, $Q1 );




		/* modify by rja 
		   �o�@�q�Ψӧ�ǥͰѥ[�P�B������
		 */
		$my_result = query_db_to_array($Q1);
		//var_dump($my_result);
		$my_a_id=Array();
		if(!empty($my_result))
		{
			foreach($my_result as $value)
				$my_a_id[]=$value['a_id'];
		}	

		require_once('../my_stuJoinMeetingList.php');

		$stuList = getStuJoinMeetingList($course_id, $my_a_id);
		//print_r($stuList);

		//end of modify




		$color == "#F0FFEE";
		while ( $row1 = mysql_fetch_array($result) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$tpl->assign(STUDENT_ID, $row1[id]);

			if( $row1['email'] != NULL )
				$tpl->assign(STUDENT_NAME, "<A HREF=mailto:".$row1['email'].">".$row1['name']."</A>");
			else
				$tpl->assign(STUDENT_NAME, $row1['name']);

			$a_id = $row1['a_id'];
			$Q2 = "Select mtime, tag3, event_id From log Where user_id='$a_id'";
			if ( $resultOBJ = mysql_db_query( $DB.$course_id, $Q2 ) ) {
				$tpl->assign(LOGIN_TIMES, "0");
				if($version=="C")
					$tpl->assign(LASTLOGIN_TIME, "�|���n�J�L");
				else
					$tpl->assign(LASTLOGIN_TIME, "Never");
				$tpl->assign(CHAT_TIMES, "0");
				$tpl->assign(STAY_TIME, "0 : 0");
				$tpl->assign(POST_TIMES, "0");
				while ( $row = mysql_fetch_array($resultOBJ) ) {
					switch ( $row['event_id'] ) {
						case '2':
						if( $row["tag3"] != NULL )
							$tpl->assign(LOGIN_TIMES, $row[tag3]);
						else
							$tpl->assign(LOGIN_TIMES, "0");
		
						if( $row["tag3"] != 0 )
						{
							//$tempDate=array(substr($row[mtime],0,4),substr($row[mtime],4,2),substr($row[mtime],6,2));
							//$tempTime=array(substr($row[mtime],8,2),substr($row[mtime],10,2),substr($row[mtime],12,2));
							//$date=implode("-",$tempDate)." ".implode(":",$tempTime);
							$date=$row[mtime];
							$tpl->assign(LASTLOGIN_TIME, $date);
						}
						else
						{
							if($version=="C")
								$tpl->assign(LASTLOGIN_TIME, "�|���n�J�L");
							else
								$tpl->assign(LASTLOGIN_TIME, "Never");
						}
						break;
						case '4':
						/* 
						   modify by rja 
						   ��쥻����Ѧ��ơA�令 mmc ���Q�צ���
						 */
/*
						$my_chat_log_sql = "Select  sum(tag3) as tag3 From log Where user_id='$a_id' and event_id = 4";
						$thisvalue = query_db_to_value($my_chat_log_sql, $DB.$course_id);

						if( !empty($thisvalue) )
							$tpl->assign(CHAT_TIMES, $thisvalue);
						else
							$tpl->assign(CHAT_TIMES, "0");
						break;
*/

						// end of modify 
						break;

						case '7':
						if( $row["tag3"] != NULL )
							$tpl->assign(STAY_TIME, (int)($row[tag3]/60) ." : ". $row[tag3]%60);
						else
							$tpl->assign(STAY_TIME, "0 : 0");
						break;
						case '6':
						if( $row["tag3"] != NULL )
							$tpl->assign(POST_TIMES, $row[tag3]);
						else
							$tpl->assign(POST_TIMES, "0");
						break;
						default:
						break;
					}
				}
			}

/* modify by rja
�o�̻ݭn��쥻�� CHAT_TIMES (��Ѧ���)�A�ΰ��ѥ[�P�B������
*/

			//next to do : ��o�̧令 foreach $stuList .. �j�M stuId,  �����ܴNassign�A�_�hassign ��0 
			//print_r($stuList);
			$have_chatted = 0 ; 
			foreach ($stuList as $value){
				if($a_id == $value['stuId']){
					$have_chatted = 1;
					break;
				}
			}
			if($have_chatted)
				$tpl->assign(CHAT_TIMES, "{$value['count']}" );
			else
				$tpl->assign(CHAT_TIMES, "0" );
//end of modify


			$Q3 = "Select * From chap_title Where sect_num = '0'";
			$chapterResult = mysql_db_query( $DB.$course_id, $Q3 );
			if( mysql_num_rows ( $chapterResult ) == 0)
			{
				$Q4 = "Select tag3 From log Where user_id='$a_id' AND event_id = '3' AND tag1='0' AND tag4='0'";
				$resultOBJ = mysql_db_query( $DB.$course_id, $Q4 );
				$row = mysql_fetch_array($resultOBJ);
				$count = $row["tag3"];
			}
			else
			{
				$Q5 = "Select tag3 From log Where user_id = '$a_id' AND event_id = '3' AND tag1 != '0'";
				$resultOBJ = mysql_db_query( $DB.$course_id, $Q5);
				$count=0;
				while($row = mysql_fetch_array($resultOBJ))
					$count+=$row[tag3];
			}
			if ( $count == NULL )
				$count = 0;
			$tpl->assign(BROWSE_TIMES, $count);

			$tpl->parse(ROWS, ".row");
		}
		if ( $version == "C" ) {
			$tpl->assign(IMG, "img");
			$tpl->assign(CNAME0, "���ץ�");
			$tpl->assign(CNAME1, "��ť��");
		}
		else {
			$tpl->assign(IMG, "img_E");
			$tpl->assign(CNAME0, "Credit");
			$tpl->assign(CNAME1, "No Credit");
		}
		$tpl->assign(NOCREDIT, $nocredit);
		$tpl->assign(CID.$nocredit, "selected");
		$tpl->parse(BODY, "student_list");
		$tpl->FastPrint("BODY");
	}
}
?>
