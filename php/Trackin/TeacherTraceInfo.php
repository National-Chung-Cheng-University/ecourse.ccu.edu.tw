<?
// For administrator.
// List teacher's system usage.
// param: teacher_aid  / teacher's user.a_id   passed from ShowTeacherListForTrace.php

	require 'fadmin.php';
	include("class.FastTemplate.php3");

	// MYSQL timestamp to date.
	function mysql_to_date ($datestr) {
		$tempDate=array(substr($datestr,0,4),substr($datestr,4,2),substr($datestr,6,2));
		$tempTime=array(substr($datestr,8,2),substr($datestr,10,2),substr($datestr,12,2));
		$datestr=implode("-",$tempDate)." ".implode(":",$tempTime);
		return $datestr;
	}

	// security check.
	if(!isset($PHPSESSID) || (session_check_teach($PHPSESSID)) == 2)
	{
		show_page("not_access.tpl", "你沒有權限使用此功能");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die( "資料庫鏈結錯誤!!" );
	mysql_select_db($DB) or die( "資料庫選擇錯誤1!!" );

	$tpl = new FastTemplate("./templates");

	$tpl->define(array(main => "TeacherTraceInfo.tpl"));

	$tpl->define_dynamic("courseinfo_list","main");

	// Error Handleing.
	$tpl->assign("TEACHER_NAME", "(NULL)");		
	$tpl->assign("SYSTEM_LOGIN", "(NULL)");
	$tpl->assign("LASTLOGIN_TIME", "(NULL)");
	$tpl->assign("STAY_TOTAL", "0:0");

	$Q1 = "select id,name from user where a_id='$teacher_aid'";
	$result1 = mysql_query($Q1);
	$row1 = mysql_fetch_array($result1);
	$tpl->assign("TEACHER_ID", $row1[0]);
	if(strcmp($row1[1],"") != 0) {
		$tpl->assign("TEACHER_NAME", $row1[1]);
	}

	$Q2 = "select tag2,tag3 from log where user_id='$teacher_aid' and event_id = '1'";
	$result2 = mysql_query($Q2);
	if($row2 = mysql_fetch_array($result2)) {
		$tpl->assign("SYSTEM_LOGIN", $row2[1]);
		$tpl->assign("LASTLOGIN_TIME", mysql_to_date($row2[0]));
	}
	else {
		// No login log == No other log. 
		$tpl->parse(BODY, "main");
		$tpl->FastPrint("BODY");
		exit();
	}
	
	// Get Teacher's Course a_id.
	$Q3 = "select course_id from teach_course where teacher_id = '$teacher_aid'";
	$result3 = mysql_query($Q3);
	if(mysql_num_rows($result3) > 0) {
		$counter = 1;
		$stay_total = 0;
		while($row3 = mysql_fetch_array($result3)) {
			$course_id = $row3[0];
			
			// Get this course's information.
			$Q31 = "select course.name,course_group.a_id,course_group.name from course,course_group where course.a_id='$course_id' and course.group_id=course_group.a_id";
			if ( !($result31 = mysql_db_query( $DB, $Q31 )) ) {
				echo ("資料庫讀取錯誤31!!");
				exit;
			}
			$row31 = mysql_fetch_array($result31);

			// Make Tree structure.
			// Parent / Image part.
			$parent = "window.JTree".$counter." = new Tree(\"(".$row31[1].")".$row31[2]." / (".$course_id.")".$row31[0]."\");";
			$tpl->assign("PARENT", $parent);
			if($counter == 1) {
				$image = "JTree1.folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
				"JTree1.itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");";
				$tpl->assign("IMAGE", $image);
			}
			else {
				$tpl->assign("IMAGE", "");				
			}

			// Children part.
			mysql_select_db( $DB.$course_id ) or die( "資料庫選擇錯誤2!!" );
			$Q32 = "select tag3,tag4 from log where user_id='$teacher_aid' and event_id='2'";
			$result32 = mysql_query($Q32);
			if($row32 = mysql_fetch_array($result32)) {
				$course_login = $row32[0]." / ".mysql_to_date($row32[1]);
			}
			else {
				$course_login = "NULL / NULL";
			}
			$children = "JTree".$counter.".addTreeItem( \"課程登入次數 [$course_login]\" );\n";

			$Q33 = "select sum(tag3) as tag3 from log where user_id='$teacher_aid' and event_id='4'";
			$result33 = mysql_query($Q33);
			if($row33 = mysql_fetch_array($result33)) {
				$chat_times = $row33[0];
			}
			else {
				$chat_times = "NULL";
			}
			$children .= "JTree".$counter.".addTreeItem( \"參與聊天次數 [$chat_times]\" );\n";

			$Q34 = "select tag3 from log where user_id='$teacher_aid' and event_id='5'";
			$result34 = mysql_query($Q34);
			if($row34 = mysql_fetch_array($result34)) {
				$usediscuss_times = $row34[0];
			}
			else {
				$usediscuss_times = "NULL";
			}
			$children .= "JTree".$counter.".addTreeItem( \"進入討論區次數 [$usediscuss_times]\" );\n";

			$Q35 = "select tag3 from log where user_id='$teacher_aid' and event_id='6'";
			$result35 = mysql_query($Q35);
			if($row35 = mysql_fetch_array($result35)) {
				$post_times = $row35[0];
			}
			else {
				$post_times = "NULL";
			}
			$children .= "JTree".$counter.".addTreeItem( \"發表文章次數 [$post_times]\" );\n";

			$Q36 = "select tag3 from log where user_id='$teacher_aid' and event_id='7'";
			$result36 = mysql_query($Q36);
			if($row36 = mysql_fetch_array($result36)) {
				$stay_time = (int)$row36[0];
				$stay_total += $stay_time;
			}
			else {
				$stay_time = 0;
			}
			$children .= "JTree".$counter.".addTreeItem( \"使用時間 [".(int)($stay_time/60).":".($stay_time%60)."]\" );\n";

			$children .= "JTree".$counter.".protoTree = JTree1;\n";
			$tpl->assign("CHILDREN", $children);

			$tpl->parse(ROW, ".courseinfo_list");
			$counter++;
		}

		$tpl->assign("STAY_TOTAL", (int)($stay_total/60).":".($stay_total%60));

		// ShowTree part.
		$showtree = "window.tRoot = new Tree(\"tRoot\");\n";
		if($counter==1)	{
			$image = "tRoot.folderIcons = new Array(\"/images/coursefolder.gif\",\"/images/coursefolder_h.gif\",\"/images/coursefolder_s.gif\",\"/images/coursefolder_s.gif\");\n".
			"tRoot.itemIcons = new Array(\"/images/courseitem.gif\",\"/images/courseitem_h.gif\",\"/images/courseitem.gif\",\"/images/courseitem_h.gif\");\n";
			$showtree .= $image;
			$base = "tRoot.protoTree = tRoot;\n";
			$counter++;
			$tpl->assign(PARENT, NULL);
			$tpl->assign(IMAGE, NULL);
			$tpl->assign(CHILDREN, NULL);
		}
		else {
			for($i=1;$i<$counter;$i++){
				$showtree .= "tRoot.addTreeItem( JTree".$i." );\n";
			}
			$base = "tRoot.protoTree = JTree1;\n";
		}

		$showtree .= $base."showTree(window.tRoot,170,240);\n";
		$tpl->assign(SHOWTREE, $showtree);
		$tpl->assign(MESSAGE, NULL);
		$tpl->assign(HAVEORNOT, "");
	}
	else {
		$tpl->assign(PARENT, NULL);
		$tpl->assign(IMAGE, NULL);
		$tpl->assign(CHILDREN, NULL);
		$tpl->assign(SHOWTREE, NULL);
		$tpl->assign(HAVEORNOT, "//");
	}


	$tpl->parse(BODY, "main");
	$tpl->FastPrint("BODY");
?>
