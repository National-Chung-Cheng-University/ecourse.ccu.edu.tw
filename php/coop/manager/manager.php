<?php
require 'fadmin.php';
update_status ("�ժ����");

if(!(isset($PHPSESSID) && session_check_teach($PHPSESSID) && check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2 ) )
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
if ( $action == "update" && $teacher != 1 ) {
	update();
}
show_page_d ();

function update () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $coopcaseid, $coopgroup, $course_id, $user_id, $version, $user_id, $duty, $aid;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	$Q1 = "update coop_".$coopcaseid."_group set leader_id = NULL Where group_num = '$coopgroup' and student_id = '$user_id'";
	mysql_db_query( $DBC.$course_id, $Q1 ) or die (  "��Ʈw��s���~!!" );
	while(list($key,$value) = each($duty)) {
		if ( $value == 1 ) {
			$Q1 = "update coop_".$coopcaseid."_group set leader_id = '".$aid[$key]."' Where group_num = '$coopgroup' and student_id = '$user_id'";
			mysql_db_query( $DBC.$course_id, $Q1 ) or die (  "��Ʈw��s���~!!" );
		}
	}
	$Q2 = "Select * From coop_".$coopcaseid."_group Where group_num = '$coopgroup'";
	if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
		$message = "��ƮwŪ�����~!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	else {
		$i = 0;
		while ( $row2 = mysql_fetch_array ( $result2 ) ) {
			for ( $j = 0 ; $j < $i ; $j ++ ) {
				if ( $data[$j][0] == $row2['leader_id'] ) {
					$data[$j][1] ++;
					continue;
				}
			}
			$data[$i][0] = $row2['leader_id'];
			$data[$i][1] = 1;
			$i ++;
		}
		$helf_total = mysql_num_rows ( $result2 )/2;
		
		for ( $j = 0 ; $j < $i ; $j ++ ) {
			if ( $data[$j][1] > $helf_total ) {
				$Q3 = "select id from user where a_id = '".$data[$j][0]."'";
				$result3 = mysql_db_query( $DB, $Q3 ) or die (  "��ƮwŪ�����~!!" );
				$row3 = mysql_fetch_array( $result3 );
				$Q4 = "update coop_".$coopcaseid."_group set duty = '1' Where group_num = '$coopgroup' and student_id = '".$row3['id']."'";
				$Q5 = "update coop_".$coopcaseid."_group set duty = '0' Where group_num = '$coopgroup' and student_id != '".$row3['id']."'";
				mysql_db_query( $DBC.$course_id, $Q4 ) or die (  "��Ʈw��s���~!!" );
				mysql_db_query( $DBC.$course_id, $Q5 ) or die (  "��Ʈw��s���~!!" );
			}
		}
	}
}

function show_page_d ( ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $coopcaseid, $coopgroup, $course_id, $user_id, $version, $teacher;
	$Q1 = "Select student_id,duty From coop_".$coopcaseid."_group Where group_num = '$coopgroup'";
	$Q3 = "Select leader_id From coop_".$coopcaseid."_group Where group_num = '$coopgroup' and student_id='$user_id'";

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	else if ( !($resultOBJ = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		$message = "��ƮwŪ�����~!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	else if ( !($resultOBJ3 = mysql_db_query( $DBC.$course_id, $Q3 ) ) ) {
		$message = "��ƮwŪ�����~!!";
		show_page( "not_access.tpl" , $message);
		return;
	}
	else if ( mysql_num_rows ( $resultOBJ ) != 0 ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $version == "C" ) {
			$tpl->define ( array ( body => "manager.tpl") );
		}
		else {
			$tpl->define ( array ( body => "manager.tpl") );
		}
		
		$tpl->define_dynamic("user_list", "body");
		
		if ( check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2 && $teacher != 1 ) {
			$tpl->assign( BOTTON , "<input type=submit value=��s> <input type=reset value=����>" );
		}
		else {
			$tpl->assign( BOTTON, "" );
		}
		$i = 0;
		$color = "#CCCCCC";
		$row3 = mysql_fetch_array ( $resultOBJ3 );
		while ( $row = mysql_fetch_array ( $resultOBJ ) ) {
			$Q2 = "Select * From user Where id = '".$row['student_id']."'";
			if ( !($resultOBJ2 = mysql_db_query( $DB, $Q2 ) ) ) {
				$message .= "-- ��ƮwŪ�����~!!";
			}
			$row2 = mysql_fetch_array ( $resultOBJ2 );
			$i ++;
			if ( $color == "#CCCCCC" )
				$color = "#F0FFEE";
			else
				$color = "#CCCCCC";

			if ( $row3['leader_id'] == $row2['a_id']) {
				$tpl->assign( LEADER , "selected" );
				$tpl->assign( MEMBER , "" );
			}
			else{
				$tpl->assign( LEADER , "" );
				$tpl->assign( MEMBER , "selected" );
			}
			if ( $row['duty'] == 1) {
				if ( $version == "C" ) {
					$tpl->assign( NOW , "�ժ�" );
				}
				else {
					$tpl->assign( NOW , "Leader" );
				}
			}
			else {
				if ( $version == "C" ) {
					$tpl->assign( NOW , "�խ�" );
				}
				else {
					$tpl->assign( NOW , "Staff" );
				}
			}
			$tpl->assign( BCOLOR , $color );
			$tpl->assign( "NO", $i);
			$tpl->assign("AID", $row2['a_id']);
			$tpl->assign("NICKN", $row2['nickname']);
			$tpl->assign("NAME", $row2['name']);
			
			$sexIndex = $row2['color'];
			if($sexIndex == "0")
			{
				if ( $version == "C" ) {
			    		$sexStr = "�k";
				}
				else {
			    		$sexStr = "F";
				}
			}
			else if($sexIndex == "1")
			{
				if ( $version == "C" ) {
			    		$sexStr = "�k";
				}
				else {
			    		$sexStr = "M";
				}
			}
	
			$tpl->assign("SEX", $sexStr);
			if ( $version == "C" ) {
				if($row2['color'] == 1)
					$scolor = "���";
				else if($row2['color'] == 2)
					$scolor = "����";
				else if($row2['color'] == 3)
					$scolor = "�Ŧ�";
				else if($row2['color'] == 4)
					$scolor = "���";
				else
					$scolor = "�m�i";
			}else {
				if($row2['color'] == 1)
					$scolor = "Orange";
				else if($row2['color'] == 2)
					$scolor = "Gold";
				else if($row2['color'] == 3)
					$scolor = "Blue";
				else if($row2['color'] == 4)
					$scolor = "Green";
				else
					$scolor = "Rainbow";
			}
			$tpl->assign("SCOLOR", $scolor);
			$tpl->assign("JOB", $row2['job']);
			if ( $row2['php'] == "" || $row2['php'] == NULL ) {
				$tpl->assign("ID", $row2['id']);
			}
			else {
				$tpl->assign("ID", "<a href=".$row2['php']." target=_blank>".$row2['id']."</a>");
			}
			$tpl->assign("EMAIL", $row2['email']);
			$tpl->parse(ROW, ".user_list");
		}
		$tpl->parse(BODY,"body");
		$tpl->FastPrint("BODY");
		
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"�ثe�L����խ����");
		else
			show_page( "not_access.tpl" ,"No data now!!");
	}
}
?>
