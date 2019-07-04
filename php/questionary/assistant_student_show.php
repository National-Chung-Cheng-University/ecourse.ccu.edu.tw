<?php
require 'fadmin.php';
update_status ("觀看IEET問卷");

include("class.FastTemplate.php3");
$tpl = new FastTemplate ( "./templates" );

$Q1 = "SELECT authorization FROM user WHERE id='$user_id'";
if (!($result = mysql_db_query($DB,$Q1)))
	show_page("not_access.tpl","資料庫讀取錯誤!!");
else 
	$row1 = mysql_fetch_array($result); 

if ($row1[0] == '3') { //身份是學生才顯示
	$Q1 = "SELECT group_id,name FROM course WHERE a_id='$course_id'";
	if (!($result = mysql_db_query($DB,$Q1)))
		show_page("not_access.tpl","資料庫讀取錯誤!!");
	else {
		$row1 = mysql_fetch_array($result); //$row1[0] = group_id
		$course_name = $row1[1]; //$row1[1] = 課程名稱
	}
				
	$Q1 = "SELECT name FROM course_group WHERE a_id='$row1[0]'";
	if (!($result = mysql_db_query($DB,$Q1)))
		show_page("not_access.tpl","資料庫讀取錯誤!!");
	else
		$row1 = mysql_fetch_array($result); //$row1[0] = 系所名稱
	
	$Q1 = "SELECT id,group_name,beg_time,end_time,is_public,is_showname FROM questionary WHERE group_name='$row1[0]'";
	if (!($result = mysql_db_query($DB,$Q1)))
		show_page("not_access.tpl","資料庫讀取錯誤!!");
	else {
		if (mysql_num_rows($result)) {
			$row1 = mysql_fetch_array($result);
			
			if ($row1[5] == '1') //記名
				$is_showname = '<b>記名</b>';
			else
				$is_showname = '不記名';
		
			$Q1 = "SELECT group_name,beg_time,end_time FROM questionary WHERE id = '$row1[0]' and is_public = '1' and end_time > '".date("Y-m-d H:i:s")."'";
			if (!($result = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","資料庫讀取錯誤!!");
			else {
				if (mysql_num_rows($result)) {
					$row1 = mysql_fetch_array($result);
					
					$Q1 = "SELECT count(*) FROM questionary_r WHERE course_id = '$course_id' and student_id='$user_id'";
					if (!($result_tmp = mysql_db_query($DB,$Q1)))
						show_page("not_access.tpl","資料庫讀取錯誤!!");
					else {
						$row_tmp = mysql_fetch_array($result_tmp);
						if ($row_tmp[0] == 0) {
							$current_status = '<font color="#FF0000">尚未填寫</font>';
							$current_action = '填寫問卷';
						} else {
							$current_status = '<b>已填寫完畢</b>';
							$current_action = '重新填寫';
						}
					}
					
					$_display = $_display.'<br><table border="0" align="center" cellpadding="0" cellspacing="0" width="92%"><tr><td><div align="right"><img src="/images/skin1/bor/bor_01.GIF" width="12" height="11"></div></td><td><div align="center"><img src="/images/skin1/bor/bor_02.GIF" width="100%" height="11"></div></td><td><div align="left"><img src="/images/skin1/bor/bor_03.GIF" width="17" height="11"></div></td></tr><tr><td height=10>';
	
					$_display = $_display.'<div align="right"><img src="/images/skin1/bor/bor_04.GIF" width="12" height="100%"></div></td><td bgcolor="#CCCCCC"><table cellpadding=3 align=center border=0 bordercolorlight="#666666" bordercolordark="#FFFFFF" width="100%" cellspacing="1"><tr bgcolor=#000066 align=center><td colspan="6"><font color="#FFFF00"><b>系辦期末問卷調查</b></font></td></tr><tr bgcolor=#000066 align=center><td width="22%" bgcolor="#000066"><div align="center"><FONT color=#ffffff>課程名稱</FONT></div></td><td width="19%" bgcolor="#000066"><div align="center"><FONT color=#ffffff>開始時間</font></div></td><td width="18%" bgcolor="#000066"><div align="center"><font color="#FFFFFF">結束時間</font></div></td><td width="11%" bgcolor="#000066"><div align="center"><font color="#ffffff">類型</font></div></td><td width="15%" bgcolor="#000066"><div align="center"><font color="#FFFFFF">目前狀態</font></div></td><td width="14%" bgcolor="#000066"><div align="center"><font color="#FFFFFF">動作</font></div></td></tr><tr bgcolor="#F0FFEE"><td height="19" align=left><div align="center">'.$course_name.'</div></td><td align=left><div align="center">'.$row1[1].'</div></td><td align=left><div align="center">'.$row1[2].'</div></td><td align=left><div align="center">'.$is_showname.'</div></td><td align=left><div align="center">'.$current_status.'</div></td><td align=left><div align="center"><a href="../questionary/assistantquestionary.php?action=takequestionary">'.$current_action.'</a></div></td></tr></table></td>';
	
					$_display = $_display.'<td height=10><div align="left"><img src="/images/skin1/bor/bor_06.GIF" width="17" height="100%"></div></td></tr><tr><td><div align="right"><img src="/images/skin1/bor/bor_07.GIF" width="12" height="17"></div></td><td><div align="center"><img src="/images/skin1/bor/bor_08.GIF" width="100%" height="17"></div></td><td><div align="left"><img src="/images/skin1/bor/bor_09.GIF" width="17" height="17"></div></td></tr></table><br>';	
				}
				else{
					$_display = "問卷未發佈";
				}
			}
		}
		else{
			$_display = "問卷尚未設定";
		}
	}
	$tpl->define ( array ( main => "assistant_student_show.tpl" ) );
	$tpl->assign( DISPLAY, $_display );
	$tpl->parse( MAIN, "main" );
	$tpl->FastPrint("MAIN");
}
?>