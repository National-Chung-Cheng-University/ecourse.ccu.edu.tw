<?php
require 'fadmin.php';
include '../picture_encryption.php';
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
		
		$tpl->define(array(student_list => "RollBook.tpl"));

		$tpl->define_dynamic("row", "student_list");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );
		//���X���I�W���
		$record_count = 0;
		//
		//���X�ҵ{�W�١A�H�q���H��
		$Q_course = "select * from course where a_id='".$course_id."'";
		$result_course = mysql_db_query( $DB, $Q_course);
		$row_course = mysql_fetch_array($result_course);
		//
		if ( $version == "C" ) {
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>�Ǹ�</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>�m�W</font></b>" );
                        
 			/*2008.07.23 @modify by w60292 �s�W�Ӥ��@��*/

			$tpl->assign( STUDENT_PIC, "<b><font color=#FFFFFF>�Ӥ�</font></b>" );
	
			/*******************************************/

			/*2009.03.28 @modify by w60292 Botton�^���*/
			 
			$tpl->assign( ADD_ROLL, "<form action=\"addRollRecord.php\" method=\"post\"><input type=\"submit\" value=\"�s�W�I�W�O��\" ></form>" );
			$tpl->assign( ELECTION_ROLL, "<form action=\"ElectionRoll.php\" method=\"post\"><input type=\"submit\" value=\"�W���I�W�O��\" ></form>" );

			/*******************************************/
			$Qdate = "Select distinct roll_id ,roll_date From roll_book Order By roll_id ASC";		 	
			$result_date = mysql_db_query( $DB.$course_id, $Qdate );
			$date_html = "";
			$modify_btn_html = "";
			$del_btn_html = "";
			$mail_to_absence_html="";
			
			while ( $row_date = mysql_fetch_array($result_date) )			
			{
					//�p���I�W���
					$record_count++; 
					//
					$date_html = $date_html."<TD><b><font color=#FFFFFF>"
								.$row_date['roll_date']								
								."</font></b></TD>";		
								
					$modify_btn_html = $modify_btn_html."<TD>"
								."<form action='modifyRollRecord.php' method='post'>"
								."<input name='roll_id' type='hidden' value='".$row_date['roll_id']."'>"
								."<input type='submit' value='�ק�O��' ></form>"
								."</TD>";	
					$del_btn_html = $del_btn_html."<TD>"
								."<form action='do_delRollRecord.php' method='post'>"
								."<input name='roll_id' type='hidden' value='".$row_date['roll_id']."'>"
								."<input type='submit' value='�R���O��' ></form>"
								."</TD>";
								
					//���ͷ����Ҧ��ʮu�ǥͪ�EMAIL			
					$Q_absence = "select u.name, u.email from roll_book ro , study.user u, study.take_course tc where ro.roll_id='".$row_date['roll_id']."' and ro.state='1' and tc.student_id=ro.user_id and tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' and ro.user_id = u.a_id Order By u.id ASC";
					$result_absence = mysql_db_query( $DB.$course_id, $Q_absence);
					$email_list ="";
					while ( $row_absence = mysql_fetch_array($result_absence) )								
					{
						if( $row_absence ['email'] != NULL ){
							$email_list = $email_list.$row_absence['email'].",";
						}
					}
					$mail_to_absence_html = $mail_to_absence_html."<TD>"
									   		."<A HREF=mailto:?subject=".$row_course['name']."�q���H!&bcc=$email_list>�H�H���ʮu�ǥ�</A>"
									   		."</TD>";
					//
			}
			
			$tpl->assign( MODIFY_BTN, $modify_btn_html );
			$tpl->assign( DEL_BTN, $del_btn_html );
			$tpl->assign( DATE, $date_html );
			$tpl->assign( MAIL_TO_BTN, $mail_to_absence_html );
			$tpl->assign( COUNT, "<b><font color=#FFFFFF>�έp</font></b>" );
		}
		else {
			$tpl->assign( STUDENT_ID, "<b><font color=#FFFFFF>ID</font></b>" );
			$tpl->assign( STUDENT_NAME, "<b><font color=#FFFFFF>Name</font></b>" );
		
			/*2008.07.23 @modify by w60292 �s�W�Ӥ��@��*/	

			$tpl->assign( STUDENT_PIC, "<b><font color=#FFFFFF>Picture</font></b>" );

			/*******************************************/

			/*2009.03.28 @modify by w60292 Botton�^���*/

                        $tpl->assign( ADD_ROLL, "<form action=\"addRollRecord.php\" method=\"post\"><input type=\"submit\" value=\"Add a New Record\" ></form>" );
                        $tpl->assign( ELECTION_ROLL, "<form action=\"ElectionRoll.php\" method=\"post\"><input type=\"submit\" value=\"Upload a New Record\" ></form>" );

                        /*******************************************/

			$Qdate = "Select distinct roll_id ,roll_date From roll_book Order By roll_id ASC";		 	
			$result_date = mysql_db_query( $DB.$course_id, $Qdate );
			$date_html = "";

			while ( $row_date = mysql_fetch_array($result_date) )			
			{
				//�p���I�W���
				$record_count++; 
				//
				$date_html = $date_html."<TD><b><font color=#FFFFFF>".$row_date['roll_date']."</font></b></TD>";
				$modify_btn_html = $modify_btn_html."<TD>"
							."<form action='modifyRollRecord.php' method='post'>"
							."<input name='roll_id' type='hidden' value='".$row_date['roll_id']."'>"
							."<input type='submit' value='Modify record' ></form>"
							."</TD>";	
				$del_btn_html = $del_btn_html."<TD>"
							."<form action='do_delRollRecord.php' method='post'>"
							."<input name='roll_id' type='hidden' value='".$row_date['roll_id']."'>"
							."<input type='submit' value='Delete record' ></form>"
							."</TD>";
							
				//���ͷ����Ҧ��ʮu�ǥͪ�EMAIL			
				$Q_absence = "select u.name, u.email from roll_book ro , study.user u, study.take_course tc where ro.roll_id='".$row_date['roll_id']."' and tc.student_id=ro.user_id and tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' and ro.user_id = u.a_id Order By u.id ASC";
				$result_absence = mysql_db_query( $DB.$course_id, $Q_absence);
				$email_list ="";
				while ( $row_absence = mysql_fetch_array($result_absence) )								
				{
					if( $row_absence ['email'] != NULL ){
						$email_list = $email_list.$row_absence['email'].",";
					}
				}
				$mail_to_absence_html = $mail_to_absence_html."<TD>"
										."<A HREF=mailto:?subject=".$row_course['name']."�q���H!&bcc=$email_list>Email to Absence Student</A>"
										."</TD>";
				//					
			}
			$tpl->assign( MODIFY_BTN, $modify_btn_html );
			$tpl->assign( DEL_BTN, $del_btn_html );
			$tpl->assign( DATE, $date_html );
			$tpl->assign( MAIL_TO_BTN, $mail_to_absence_html );
			$tpl->assign( COUNT, "<b><font color=#FFFFFF>Conut</font></b>" );
		}
		$tpl->parse(ROWS, ".row");
		$color = "#BFCEBD";
		
		$Q1 = "Select u.id, u.email, u.name, u.a_id From user u ,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' Order By u.id ASC";
		
		$result = mysql_db_query( $DB, $Q1 );
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

			/*2008.07.23 @modify by w60292 �s�W�Ӥ��@��*/

			//$tpl->assign(STUDENT_PIC, "<IMG SRC=\"../../Stu-Photo/".$row1[id].".jpg\" width=\"103\" height=\"133\">");
		 	$picid = pic_encrypt($row1[id]);
			$tpl->assign(STUDENT_PIC, "<img src=\"../url_convert.php?id=".$picid."\" width=\"103\" height=\"133\">");
			/*******************************************/

			$a_id = $row1['a_id'];
			
			
			//�ѨM�ǥͬ���ӷs�W�ҥi��a�Ӫ����~������ -- �ʤ��I�W�O��
			$Q11 = "SELECT COUNT(DISTINCT roll_id) From roll_book WHERE user_id = '$a_id'";
			$result11 = mysql_db_query( $DB.$course_id, $Q11 );
			if ($row11 = mysql_fetch_array($result11) ){
				if( $row11[0] != $record_count){
					$Q12 = "SELECT DISTINCT roll_id ,roll_date From roll_book Order By roll_id ASC";
					$result12 = mysql_db_query( $DB.$course_id, $Q12 );
					while ($row12 = mysql_fetch_array($result12) ){
						$Q13 = "SELECT roll_id  From roll_book WHERE user_id = '$a_id' AND roll_id = '".$row12['roll_id']."'";
						$result13 = mysql_db_query( $DB.$course_id, $Q13 );
						if(!($row13 = mysql_fetch_array($result13))){
							$Q14 =  "insert into roll_book (user_id, roll_id, roll_date, state, note) values ('$a_id', '".$row12['roll_id']."', '".$row12['roll_date']."','5', '')";
							mysql_db_query( $DB.$course_id, $Q14 );
						}
					}
					
				}
			}
			//------
			
			$Q2 = "Select state, note  From roll_book where user_id = '$a_id' Order By roll_id ASC";		 	
			$result2 = mysql_db_query( $DB.$course_id, $Q2 );
			$date_html = "";
			$count = array(0,0,0,0,0,0);
			while ( $row2 = mysql_fetch_array($result2) )			
			{
			
					switch ($row2['state'] )
					{
							case '0':
								$count[0]++;
								$date_html = $date_html."<TD>�X�u</TD>";
								break;
							case '1':
								$count[1]++;
								$date_html = $date_html."<TD>�ʮu</TD>";
								break;
							case '2':
								$count[2]++;
								$date_html = $date_html."<TD>���</TD>";
								break;
							case '3':
								$count[3]++;
								$date_html = $date_html."<TD>���h</TD>";
								break;
							case '4':
								$count[4]++;
								$date_html = $date_html."<TD>�а�</TD>";
								break;
							case '5':
								$count[5]++;
								$date_html = $date_html."<TD>".$row2['note']."</TD>";
								break;
										
					}
										
			}
			$tpl->assign( DATE, $date_html );
			$count_html="";
			
			if($count[0]>0)
			{
				$count_html = $count_html."�X�u:".$count[0]." ";
			}
			if($count[1]>0)
			{
				$count_html = $count_html."�ʮu:".$count[1]." ";
			}			
			if($count[2]>0)
			{
				$count_html = $count_html."���:".$count[2]." ";
			}			
			if($count[3]>0)
			{
				$count_html = $count_html."���h:".$count[3]." ";
			}			
			if($count[4]>0)
			{
				$count_html = $count_html."�а�:".$count[4]." ";
			}			
			if($count[5]>0)
			{
				$count_html = $count_html."��L:".$count[5]." ";
			}			
			
			
			
			$tpl->assign( COUNT, $count_html );
			
			$tpl->parse(ROWS, ".row");
		}
		
		$tpl->assign(NOCREDIT, $nocredit);
		$tpl->assign(CID.$nocredit, "selected");
		$tpl->parse(BODY, "student_list");
		$tpl->FastPrint("BODY");
	}
}
?>
