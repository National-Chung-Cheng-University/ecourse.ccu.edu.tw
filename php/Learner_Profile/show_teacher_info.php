<?php
/*
�ǥ��[�ݱЮv��T
ghost777
2008/11/26
*/
	require 'fadmin.php';
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~" );
	}
	else{	
		//���d�X�Ҧ����Ѯv,���ͩ��Q,�I�諸�ɭԨ̷�teacher_id�d�߸ӦѮv�����		
		$all_teacher = get_course_teacher($course_id, $course_year, $course_term);		
		
		if($all_teacher == -1 || $all_teacher == 0){
			show_page( "not_access.tpl" ,"�S���Юv��T!!" );
		}
		else{
			if(!isset($_GET['teacher_num'])){
				//�d�X�Юv���
				$sql = "SELECT * FROM user WHERE a_id='".$all_teacher[0]['teacher_id']."'";
				if(!($res = mysql_db_query( $DB, $sql))){
					show_page( "not_access.tpl" ,"��Ʈw�s�����~!!m" );
				}
				else{					
					$row = mysql_fetch_array($res);
					if(count($row)!=0){	
						//���Ѯv���ӤH���
						$info = parse_info($row);
						//output			
						output_page($all_teacher, $info, 0); //�]�w�]��ܲĤ@�� �ҥH��0
					}
					else{
						echo "�d�L���Юv";
					}
				}
			}
			else{
				$sql = "SELECT * FROM user WHERE a_id='".$all_teacher[$_GET['teacher_num']]['teacher_id']."'";
				if(!($res = mysql_db_query( $DB, $sql))){
					show_page( "not_access.tpl" ,"��Ʈw�s�����~!!m1" );
				}
				else{
					$row = mysql_fetch_array($res);				
					if(count($row)!=0){
						//���Ѯv��office time
						$info = parse_info($row);
						//output				
						output_page($all_teacher, $info, $_GET['teacher_num']); 
					}
					else{
						echo "�d�L���Юv";
					}
				}			
			}
		}
	}
	
function parse_info($row){
	//���X  �m�W �q��  ��}  ����  e-mail  ����  �M��  ²��  �g��
	$info[0] = $row['name'];
	$info[1] = $row['tel'];
	$info[2] = $row['addr'];
	$info[3] = $row['php'];
	$info[4] = $row['email'];
	$info[5] = $row['interest'];
	$info[6] = $row['skill'];
	$info[7] = $row['introduction'];
	$info[8] = $row['experience'];
	/*if($row['comment'] !="")
		$office_time[5] = $row['comment'];
	else
		$office_time[5] = "�Юv�|����g";
	if($row['location'] != "")		
		$office_time[6] = $row['location'];
	else
		$office_time[6] = "�Юv�|����g";	
	*/
	return  $info;
}

function get_course_teacher($course_id, $course_year, $course_term){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$sql = "SELECT tc.teacher_id, u.name, u.tel, u.email, u.id FROM teach_course tc, user u WHERE tc.teacher_id=u.a_id and tc.course_id=$course_id and tc.year=$course_year and tc.term=$course_term ORDER BY teacher_id ASC";
	if(!($res = mysql_db_query($DB,$sql))){
		show_page( "not_access.tpl" ,"��Ʈw�s�����~!!g" );
		return -1; //sql�d�߿��~
	}
	else{
		$count = mysql_num_rows($res);
		//echo $count;
		if( $count != 0){
			for($i=0; $i < $count ;$i++){
				$row = mysql_fetch_array($res);
				$all_teacher[$i]['teacher_id']	= $row['teacher_id'];
				$all_teacher[$i]['name'] 		= $row['name'];
				if($row['tel'] != "")
					$all_teacher[$i]['tel']	= $row['tel'];
				else
					$all_teacher[$i]['tel']	= "�Юv�|����g";
				if($row['email'] !="")	
					$all_teacher[$i]['email'] = $row['email'];
				else
					$all_teacher[$i]['email'] =	"�Юv�|����g";
				$all_teacher[$i]['id']	= $row['id'];
			}
			return $all_teacher;		
		}
		else{ //�S���Юv
			return 0;
		}
	}		
}

function output_page($all_teacher, $info, $teacher_num){
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "show_teacher_info.tpl") );
	$tpl->define_dynamic ( "teacher_list" , "body" );

	//show teacher list
	for($i=0; $i < count($all_teacher); $i++){
		$tpl->assign(NUM, $i);
		if($_GET['teacher_num'] == $i)
			$tpl->assign(SELD, "selected");
		else
			$tpl->assign(SELD, "");
		if($all_teacher[$i]['name'] != '')
			$tpl->assign(TEACHER_NAME, $all_teacher[$i]['name']);
		else
			$tpl->assign(TEACHER_NAME, $all_teacher[$i]['id']);
		$tpl->parse ( TEACHER_LIST, ".teacher_list" );		
	}		
	//show info
	$tpl->assign(NAME, $info[0]);
	$tpl->assign(TEL, $info[1]);
	$tpl->assign(ADDR, $info[2]);
	$tpl->assign(PAGE, $info[3]);
	$tpl->assign(EMAIL, $info[4]);
	$tpl->assign(INTEREST, $info[5]);
	$tpl->assign(SKILL, $info[6]);
	$tpl->assign(INTRO, $info[7]);
	$tpl->assign(EXPER, $info[8]);
	$tpl->parse(BODY,"body");
	$tpl->FastPrint("BODY");
}

function add_tail($time, $office_time, $i)
{
	$row = "<td bgcolor=#66CCFF align=CENTER onmouseover=\"getChoose(this);\" ; onmouseout=\"resetColor(this);\">\n";
	if($office_time[$i*3+0] == 1){
		$row .= ($i*3+1)."<br>\n";
	}	
	if($office_time[$i*2+15] == 1){
		$row .= $time[$i*2]."<br>\n";
	}
	if($office_time[$i*3+1] == 1){
		$row .= ($i*3+2)."<br>\n";
	}
	if($office_time[$i*2+16] == 1){
		$row .= $time[$i*2+1]."<br>\n";
	}		
	if($office_time[$i*3+2] == 1){
		$row .= ($i*3+3)."<br>\n";
	}							
	$row .= "</td>\n";
	return $row;
}

function show_left($time, $i, $tmp)
{
$tmp_T = array("��", "��", "��", "��", "��");

$row =
		"<TH bgcolor='#006666'>
			<TABLE border=0 width=100%>
				<TR>
					<TD bgcolor=#66CCFF rowspan=6>" . $tmp_T[$i] . "</TD>
					<TD rowspan=2 bgcolor=#66CC99 align=CENTER id=color_".($i*3+1)."><FONT size=1>��<BR>" . ($i*3+1) . "<BR>�`</TD>
					<TD rowspan=2 bgcolor=#99CCFF align=CENTER id=C_".($i*3+1)."><FONT size=1>" . $time[$i*3+1] . "</TD>
					<TD rowspan=3 bgcolor=#66CC99 align=CENTER id=color_".$tmp[$i*2]."><FONT size=1>��<BR>" . $tmp[$i*2] . "<BR>�`</TD>
					<TD rowspan=3 bgcolor=#99CCFF id=C_".$tmp[$i*2]."><FONT size=1>" . $time[$i*2+16] . "</TD>
				</TR>
				<TR></TR>
				<TR>
					<TD rowspan=2 bgcolor=#66CC99 align=CENTER id=color_".($i*3+2)."><FONT size=1>��<BR>" . ($i*3+2) . "<BR>�`</TD>
					<TD rowspan=2 bgcolor=#99CCFF align=CENTER id=C_".($i*3+2)."><FONT size=1>" . $time[$i*3+2] . "</TD>
				</TR>
				<TR>			
					<TD rowspan=3 bgcolor=#66CC99 align=CENTER id=color_".$tmp[$i*2+1]."><FONT size=1>��<BR>" . $tmp[$i*2+1] . "<BR>�`</TD>
					<TD rowspan=3 bgcolor=#99CCFF align=CENTER id=C_".$tmp[$i*2+1]."><FONT size=1>" . $time[$i*2+17] . "</TD>
				</TR>
				<TR>
					<TD rowspan=2 bgcolor=#66CC99 align=CENTER id=color_".($i*3+3)."><FONT size=1>��<BR>" .  ($i*3+3) . "<BR>�`</TD>
					<TD rowspan=2 bgcolor=#99CCFF align=CENTER id=C_".($i*3+3)."><FONT size=1>" . $time[$i*3+3] . "</TD>
				</TR>
			</TABLE>
		</TH>";
	return $row;
}

function define_time()
{
	$l_time[1] = "07:10<br>~<br>08:00";
	$l_time[2] = "08:10<br>~<br>09:00";
	$l_time[3] = "09:10<br>~<br>10:00";
	$l_time[4] = "10:10<br>~<br>11:00";
	$l_time[5] = "11:10<br>~<br>12:00";
	$l_time[6] = "12:10<br>~<br>13:00";
	$l_time[7] = "13:10<br>~<br>14:00";
	$l_time[8] = "14:10<br>~<br>15:00";
	$l_time[9] = "15:10<br>~<br>16:00";
	$l_time[10] = "16:10<br>~<br>17:00";
	$l_time[11] = "17:10<br>~<br>18:00";
	$l_time[12] = "18:10<br>~<br>19:00";
	$l_time[13] = "19:10<br>~<br>20:00";
	$l_time[14] = "20:10<br>~<br>21:00";
	$l_time[15] = "21:10<br>~<br>22:00";
	$l_time[16] = "07:15<br>~<br>08:30";
	$l_time[17] = "08:45<br>~<br>10:00";
	$l_time[18] = "10:15<br>~<br>11:30";
	$l_time[19] = "11:45<br>~<br>13:00";
	$l_time[20] = "13:15<br>~<br>14:30";
	$l_time[21] = "14:45<br>~<br>16:00";
	$l_time[22] = "16:15<br>~<br>17:30";
	$l_time[23] = "17:45<br>~<br>19:00";
	$l_time[24] = "19:15<br>~<br>20:30";
	$l_time[25] =  "20:45<br>~<br>22:00";
	return $l_time;
}

?>
