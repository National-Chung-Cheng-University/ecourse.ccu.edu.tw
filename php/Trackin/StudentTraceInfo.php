<?php
require 'fadmin.php';
update_status ("個人使用紀錄");

if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "資料庫連結錯誤!!" );
	return;
}

if($check == 2)
{
	if($student_aid != NULL)
		$a_id = $student_aid;
  	else {
    		show_page( "not_access.tpl" ,"學號錯誤");
		exit;
	}
}
else
{
	$Q0 = "Select a_id, authorization From user Where id='$user_id'";
	if ( !($resultOBJ0 = mysql_db_query( $DB, $Q0 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		exit;
	}
	if ( !($row0 = mysql_fetch_array ( $resultOBJ0 )) ) {
		show_page( "not_access.tpl" ,"使用者資料錯誤!!" );
		exit;
	}
	if($row0['authorization'] == "9")
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
	$a_id = $row0['a_id'];
}

$Q1 = "Select name, id From user Where a_id = '$a_id'";
$Q2 = "Select tag3, mtime+0 From log Where user_id = '$a_id' AND event_id = '2'";
$Q3 = "Select tag3 From log Where user_id = '$a_id' AND event_id = '7'";
//$Q4 = "Select tag3 From log Where user_id = '$a_id' AND event_id = '6'";
//modify by rja , 原本的聊天次數改成 mmc 的討論次數
//update: 下面這個 query 應該也不用了，改成新的方法
$Q5 = "Select sum(tag3) as tag3 From log Where user_id = '$a_id' AND event_id = '4'";
$Q6 = "Select * From chap_title Where sect_num='0' Order By chap_num ASC";
$posted_num = posted_article_num($a_id);//貼過的文章總數
if ( !($resultOBJ1 = mysql_db_query( $DB, $Q1 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤1!!" );
	exit;
}
if ( !($row1 = mysql_fetch_array ( $resultOBJ1 )) ) {
	show_page( "not_access.tpl" ,"使用者資料錯誤12!!" );
	exit;
}

if ( !($resultOBJ2 = mysql_db_query( $DB.$course_id, $Q2)) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
	exit;
}
$row2 = mysql_fetch_array ( $resultOBJ2 );

if ( !($resultOBJ3 = mysql_db_query( $DB.$course_id, $Q3)) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
	exit;
}
$row3 = mysql_fetch_array ( $resultOBJ3 );

/*if ( !($resultOBJ4 = mysql_db_query( $DB.$course_id, $Q4 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤4!!" );
	exit;
}
$row4 = mysql_fetch_array ( $resultOBJ4 );
*/
if ( !($resultOBJ5 = mysql_db_query( $DB.$course_id, $Q5 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤5!!" );
	exit;
}
$row5 = mysql_fetch_array ( $resultOBJ5 );

if ( !($resultOBJ6 = mysql_db_query( $DB.$course_id, $Q6 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤6!!" );
	exit;
}

if( mysql_num_rows( $resultOBJ1 ) != 0 )
{
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version=="C")
		$tpl->define(array(student_info => "StudentTraceInfo_Ch.tpl"));
	else
		$tpl->define(array(student_info => "StudentTraceInfo_En.tpl"));
	$tpl->define_dynamic("row", "student_info");
	$tpl->assign(STUDENT_NAME, $row1['name']);
	$tpl->assign(STUDENT_ID, $row1['id']);
}
else {
	show_page( "not_access.tpl" ,"使用者不存在!!" );
	exit;
}

if( mysql_num_rows( $resultOBJ2 ) != 0 ) {
	$tpl->assign(LOGIN_TIMES, $row2['tag3']);
	$tempDate=array(substr($row2['mtime+0'],0,4),substr($row2['mtime+0'],4,2),substr($row2['mtime+0'],6,2));
	$tempTime=array(substr($row2['mtime+0'],8,2),substr($row2['mtime+0'],10,2),substr($row2['mtime+0'],12,2));
	$date=implode("-",$tempDate)." ".implode(":",$tempTime);
	$tpl->assign(LASTLOGIN_TIME, $date);
}
else {
	$tpl->assign(LOGIN_TIMES, "0");
	if($version=="C")
		$tpl->assign(LASTLOGIN_TIME, "尚未登入過");
	else
		$tpl->assign(LASTLOGIN_TIME, "Never Login");
}

if( mysql_num_rows( $resultOBJ3 ) != 0 )
	$tpl->assign(STAY_TIME, (int)($row3['tag3']/60) ." : ". $row3['tag3']%60);
else
	$tpl->assign(STAY_TIME, "0 : 0");

if( $posted_num != 0 ){//modify by intree@2007/12/14
 	if($check > 1)//表示是教師介面
        	$tpl->assign(POST_TIMES, "<a href=posted_discuss.php?student_aid=$student_aid >$posted_num</a>");//.$row4['tag3'].'</a>');
	else//表示是學生介面
        	$tpl->assign(POST_TIMES, "<a href=posted_discuss.php >$posted_num</a>");
}
else
	$tpl->assign(POST_TIMES, "0");



	/*
	   modify by rja
	   把原本的聊天次數改成 mmc 的討論次數
	 */

	 require_once('../my_stuJoinMeetingList.php');

	 $stuList = getStuJoinMeetingList($course_id, $a_id);
	 //print_r($stuList);
	 if (empty($stuList)){
		 $tpl->assign(CHAT_TIMES, "0");
	 }else{
		 $JoinMeetingTimes = $stuList[0]['count'];
		 $tpl->assign(CHAT_TIMES, $JoinMeetingTimes);
	 }


/*
   下面這段 code 不必了，要改成上面這段的新方法，來把原本的聊天次數改成 mmc 的討論次數
 */
/*
   if( mysql_num_rows( $resultOBJ5 ) != 0 ){
//modify by rja , 原本的聊天次數改成 mmc 的討論次數
	if(empty($row5['tag3']))
		$row5['tag3']=0;
	$tpl->assign(CHAT_TIMES, $row5['tag3']);
}
else
	$tpl->assign(CHAT_TIMES, "0");
*/

if( mysql_num_rows( $resultOBJ6 ) != 0 )
{

	$Q7 = "Select * From chap_title Where sect_num = '0' Order By chap_num ASC";
	if ( !($resultOBJ7 = mysql_db_query( $DB.$course_id, $Q7)) ) {
		echo ( "資料庫讀取錯誤7!!" );
		return;
	}
	$first_dir = true;

	while($row7 = mysql_fetch_array($resultOBJ7))
	{
		$T1 = "Select tag3 From log Where event_id = '3' AND user_id = '$a_id' AND tag1 = '".$row7['chap_num']."' AND tag4 = '0'";
		if ( !($tempresult1 = mysql_db_query( $DB.$course_id, $T1 )) ) {
			echo ( "資料庫讀取錯誤T1!!" );
			return;
		}
 		$tempcount = 0;
		while( $temprow1 = mysql_fetch_array( $tempresult1 ))
			$tempcount += $temprow1['tag3'];

		$T2 = "Select tag3 From log Where event_id = '11' AND user_id = '$a_id' AND tag1 = '".$row7['chap_num']."' AND tag4 = '0'";
		if ( !($tempresult2 = mysql_db_query( $DB.$course_id, $T2 )) ) {
			echo ( "資料庫讀取錯誤T21!!" );
			return;
		}
		if( $temprow2 = mysql_fetch_array( $tempresult2 )) {
			$minutes = (int)($temprow2['tag3']/60);
			$seconds = $temprow2['tag3']%60;
		}
		else {
			$minutes = 0;
			$seconds = 0;		
		}

		$S1 ="Select * From chap_title Where chap_num = '".$row7['chap_num']."' AND sect_num != '0'";
		if ( !($sectresult1 = mysql_db_query( $DB.$course_id, $S1 )) ) {
			echo ( "資料庫讀取錯誤S1!!" );
			return;
		}
		if( mysql_num_rows($sectresult1) != 0)
		{
			$parent = "window.JTree".$row7['chap_num']." = new Tree(\"".$row7['chap_title']."　( $tempcount | $minutes:$seconds )\");";
			$tpl->assign(PARENT, $parent);
			if($first_dir)
			{
				$firstroot = $row7['chap_num'];
				$image = "JTree".$row7['chap_num'].".folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
					"JTree".$row7['chap_num'].".itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");";
				$tpl->assign(IMAGE, $image);
				$first_dir = false;
			}
			else
				$tpl->assign(IMAGE, NULL);
			$Q8 = "Select * From chap_title Where chap_num='".$row7['chap_num']."' AND sect_num != '0' Order By sect_num ASC";
      			if ( !($resultOBJ8 = mysql_db_query( $DB.$course_id, $Q8 )) ) {
				echo ( "資料庫讀取錯誤8!!" );
				return;
			}
			$children = "";
			while($row8 = mysql_fetch_array($resultOBJ8))
			{
				$Q9 = "Select tag3 From log Where event_id = '3' AND user_id = '$a_id' AND tag1='".$row8['chap_num']."' AND tag4='".$row8['sect_num']."'";
        			if ( !($resultOBJ9 = mysql_db_query( $DB.$course_id, $Q9 )) ) {
					echo ( "資料庫讀取錯誤9!!" );
					return;
				}
				if(mysql_num_rows( $resultOBJ9 ) != 0) {
					if ( !($row9 = mysql_fetch_array ( $resultOBJ9 )) ) {
						echo ( "資料庫讀取錯誤92!!" );
						exit;
					}
					$count = $row9['tag3'];
				}
				else
					$count = 0;

				$Q10 = "Select tag3 From log Where event_id = '11' AND user_id = '$a_id' AND tag1='".$row8['chap_num']."' AND tag4='".$row8['sect_num']."'";
        			if ( !($resultOBJ10 = mysql_db_query( $DB.$course_id, $Q10 )) ) {
					echo ( "資料庫讀取錯誤10!!" );
					return;
				}
				if(mysql_num_rows( $resultOBJ10 ) != 0) {
					if ( !($row10 = mysql_fetch_array ( $resultOBJ10 )) ) {
						echo ( "資料庫讀取錯誤102!!" );
						exit;
					}
					$period = $row10['tag3'];
					$minutes = (int)($period/60);
					$seconds = $period%60;
				}
				else {
					$period = 0;
					$minutes = 0;
					$seconds = 0;
				}


				$children .= "JTree".$row7['chap_num'].".addTreeItem( \"".$row8['sect_title']."　( $count | $minutes:$seconds )\" );\n";
			}
			$children .= "JTree".$row7['chap_num'].".protoTree = JTree$firstroot;\n";
			$tpl->assign(CHILDREN, $children);
			$tpl->parse(ROW, ".row");
		}
	}
	$showtree = "window.tRoot = new Tree(\"tRoot\");\n";
	if($first_dir)
	{
		$image = "tRoot.folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
			"tRoot.itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");\n";
		$showtree .= $image;
		$base = "tRoot.protoTree = tRoot;\n";
		$first_dir = false;
		$tpl->assign(PARENT, NULL);
		$tpl->assign(IMAGE, NULL);
		$tpl->assign(CHILDREN, NULL);
		$tpl->parse(ROW, ".row");
	}
	else
		$base = "tRoot.protoTree = JTree$firstroot;\n";

	$T1 = "Select tag3 From log Where event_id='3' AND tag1 = '0' AND tag4='0' and user_id='$a_id'";
	$count = 0;
	if( !($tempresult = mysql_db_query( $DB.$course_id, $T1 )) ) {
		echo ( "資料庫讀取錯誤T1!!" );
		return;
	}
	if( $temprow = mysql_fetch_array( $tempresult ) ) {
		$count = $temprow[0];
	}

	$T2 = "Select tag3 From log Where event_id='11' AND tag1 = '0' AND tag4='0' and user_id='$a_id'";
	$minutes = 0;
	$seconds = 0;
	if ( !($tempresult = mysql_db_query( $DB.$course_id, $T2 )) ) {
		echo ( "資料庫讀取錯誤T22!!" );
		return;
	}
	if( $temprow = mysql_fetch_array( $tempresult ) ) {
		$period = $temprow[0];
		$minutes = (int)($period/60);
		$seconds = $period%60;
	}

	if( $version == "C" ) {
		$showtree .= "tRoot.addTreeItem( \" 課程導論 ( $count | $minutes:$seconds )\" );\n";
	}
	else {
		$showtree .= "tRoot.addTreeItem( \" Introduce ( $count | $minutes:$seconds ) \" );\n";				
	}

	if ( !($resultOBJ7 = mysql_db_query( $DB.$course_id, $Q7 )) ) {
		echo ( "資料庫讀取錯誤73!!" );
		return;
	}
	while($row7 = mysql_fetch_array ( $resultOBJ7 ))
	{
		$S2 = "Select * From chap_title Where chap_num = '".$row7['chap_num']."' AND sect_num != '0'";
		if ( !($sectresult2 = mysql_db_query( $DB.$course_id, $S2 )) ) {
			echo ( "資料庫讀取錯誤S2!!" );
			return;
		}
		if( mysql_num_rows($sectresult2) != 0)
			$showtree .= "tRoot.addTreeItem( JTree".$row7['chap_num']." );\n";
		else
		{
			$C1 = "Select chap_title From chap_title Where chap_num = '".$row7['chap_num']."' AND sect_num = '0'";
			if ( !($chapname1 = mysql_db_query( $DB.$course_id, $C1 )) ) {
				echo ( "資料庫讀取錯誤C1!!" );
				return;
			}
			if ( !($chapnamerow1 = mysql_fetch_array ( $chapname1 )) ) {
				echo ( "教材資料錯誤C12!!" );
				exit;
			}
			$T2 = "Select tag3 From log Where event_id = '3' AND user_id = '$a_id' AND tag1='".$row7['chap_num']."' AND tag4='0'";
    			if ( !($tempresult2 = mysql_db_query( $DB.$course_id, $T2)) ) {
				echo ( "資料庫讀取錯誤T23!!" );
				exit;
			}
			$tempcount = 0;
			while($temprow2 = mysql_fetch_array($tempresult2))
				$tempcount += $temprow2['tag3'];

			$T3 = "Select tag3 From log Where event_id = '11' AND user_id = '$a_id' AND tag1='".$row7['chap_num']."' AND tag4='0'";
    			if ( !($tempresult3 = mysql_db_query( $DB.$course_id, $T3)) ) {
				echo ( "資料庫讀取錯誤T3!!" );
				exit;
			}
			if ($temprow3= mysql_fetch_array($tempresult3) ) {
				$minutes = (int)($temprow3['tag3']/60);
				$seconds = $temprow3['tag3']%60;
			}

			$showtree .= "tRoot.addTreeItem( \"".$chapnamerow1['chap_title']."　( $tempcount | $minutes:$seconds )\" );\n";
		}
	}
	$showtree .= $base."showTree(window.tRoot,-50,300);\n";
	$tpl->assign(SHOWTREE, $showtree);
	$tpl->assign(MESSAGE, NULL);
	$tpl->assign(HAVEORNOT, "");

}
else
{
	$tpl->assign(PARENT, NULL);
	$tpl->assign(IMAGE, NULL);
	$tpl->assign(CHILDREN, NULL);
	$tpl->parse(ROW, ".row");
	$tpl->assign(SHOWTREE, NULL);
	$tpl->assign(HAVEORNOT, "//");
	$Q7 = "Select tag3 From log Where event_id = '3' AND user_id = '$a_id' AND tag1='0' AND tag4='0'";
	if ( !($resultOBJ7 = mysql_db_query( $DB.$course_id, $Q7 )) ) {
		echo ( "資料庫讀取錯誤74!!" );
		return;
	}
	$row7 = mysql_fetch_array ( $resultOBJ7 );
	if( mysql_num_rows( $resultOBJ7 ) != 0 )
		$count = $row7['tag3'];
	else
		$count = 0;

	if($version=="C")
		$tpl->assign(MESSAGE, "<P><FONT color=red><B>教材被瀏覽次數　( $count )</B></FONT>");
	else
		$tpl->assign(MESSAGE, "<P><FONT color=red><B>No. of teaching contents browsed　( $count )</B></FONT>");
}

$tpl->assign("#!STUAID!#",$a_id);

// 顯示線上作業總數 by carlyle (20071216)
// -----------------------------------------------------------------
$Q_hw_count = "SELECT COUNT(*) FROM homework WHERE public='1' OR public='3' ORDER BY chap_num, a_id";
if (!($result_hw_count = mysql_db_query($DB.$course_id,$Q_hw_count))) {
        show_page("not_access.tpl","資料庫讀取錯誤!!");
}

$row_hw_count = mysql_fetch_array($result_hw_count);
$hw_count = $row_hw_count[0];
$tpl->assign("#!HOMEWORKCOUNTS!#",$hw_count);
// -----------------------------------------------------------------

// 顯示線上測驗總數 by carlyle (20071216)
// -----------------------------------------------------------------
$Q_exam_count = "SELECT COUNT(*) FROM exam e,take_exam te WHERE te.student_id = '".$a_id."' and e.a_id=te.exam_id AND e.is_online='1' AND ( e.public='1' ||  e.end_time != '00000000000000' ) and e.beg_time <= ".date("YmdHis")." ORDER BY e.name";
if (!($result_exam_count = mysql_db_query($DB.$course_id,$Q_exam_count))) {
        show_page("not_access.tpl","資料庫讀取錯誤!!");
}

$row_exam_count = mysql_fetch_array($result_exam_count);
$exam_count = $row_exam_count[0];
$tpl->assign("#!EXAMCOUNTS!#",$exam_count);
// -----------------------------------------------------------------

$tpl->parse(BODY, "student_info");
$tpl->FastPrint("BODY");

//----function -----

function posted_article_num($a_id){
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;

if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
        echo ( "資料庫連結錯誤!!" );
        return;
}
	$count =0 ;
	$Q1 = 'SELECT a_id,discuss_name FROM discuss_info';
	$result1 = mysql_db_query($DB.$course_id , $Q1);
	$discuss_num = mysql_num_rows($result1);//這門課有多少個討論區
	$user_id = getUser_idFromA_id($a_id);

	for($i=1 ; $i <= $discuss_num ; $i++){//找出每個討論區該學生所發的文章
		$row1 = mysql_fetch_array($result1);//討論區標題
		$discuss_name = $row1['discuss_name'];
		$discuss_aid = $row1['a_id'];

		$tablename = "discuss_$discuss_aid";
		$Q2 = "SELECT * FROM $tablename WHERE poster='$user_id' ";//從各討論區找出發文者符合的文章
		$result2 = mysql_db_query($DB.$course_id ,$Q2);

		if($result2!=null)
		$count += mysql_num_rows($result2);
		
	}

	return $count;
}

function getUser_idFromA_id($user_aid){

global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
        echo ( "資料庫連結錯誤!!" );
        return;
}

        $Q0 = "Select id, authorization From user Where a_id='$user_aid'";
        if ( !($resultOBJ0 = mysql_db_query( $DB, $Q0 ) ) ) {
                show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
                exit;
        }
        if ( !($row0 = mysql_fetch_array ( $resultOBJ0 )) ) {
                show_page( "not_access.tpl" ,"使用者資料錯誤!!" );
                exit;
        }
        if($row0['authorization'] == "9")
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
        $a_id = $row0['id'];
        return $a_id;
}


?>
