<?php
	require 'fadmin.php';
	update_status ("編輯個人資料");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	if ( $user_id == "guest" ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error 你沒有權限使用此功能!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	$Q1 = "SELECT * FROM user where id = '$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "資料庫讀取錯誤!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	else if ( !($row = mysql_fetch_array($result)) ) {
		$error = "使用者不存在!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	else if ( $row["authorization"] == 9 ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error 你沒有權限使用此功能!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}
	
	// new added by kof9x at 2001/12/04.
	switch((int)$row['color']) {
		case 1:
			$color = "<font color=orange>橘色</font>";
			break;
		case 2:
			$color = "<font color=gold>金色</font>";
			break;
		case 3:
			$color = "<font color=blue>藍色</font>";
			break;
		case 4:
			$color = "<font color=green>綠色</font>";
			break;
		default:
			$color = "彩虹 (還沒做過測驗)";
			break;
	}

	if ( ($btn == "上傳檔案" || $btn == "UPLOAD") ) {
		if( file_exists("../../studentPage".$pic) )
		{
			$error = "照片檔案已存在，請先刪除後再上傳!!";
		}else {
			if( $pic_file != "none" && fileupload ( $pic_file, "../../studentPage", $pic, "0774" ) ) {
				if ( $version == "C" )
					$error = "上傳成功\";
				else
					$error = "Upload Success";
			}
   			else {
   				if ( $version == "C" )
					$error = "上傳失敗";
				else
					$error = "Upload Abort";
			}
		}
	}
	else if ( ($btn == "刪除檔案" || $btn == "Delete") ) {
		if( unlink( "../../studentPage/$user_id.gif" ) ) {
			if ( $version == "C" )
				$error = "刪除成功\";
			else
				$error = "Delete Success";
		}
   		else {
   			if ( $version == "C" )
				$error = "刪除失敗";
			else
				$error = "Delete Abort";
		}
	}

	if ( isset($name) && isset($year) && isset($tel) && isset($email) ) {
		$ip = getenv("SERVER_NAME");
		
		if ( $ip == "" )
			$ip = $SERVER_NAME;
		$error = "請填妥";
		if ( $year == "" )
			$error = "$error 出生年份";
		if ( $tel == "" )
			$error = "$error 聯絡電話";
		if ( $email == "" )
				$error = "$error 電子郵件網址";
		if ( $pageKind == 1 ) {
			if( $version == "C" )
	    			$uurl = "http://".$ip."/studentPage/". $user_id .".html";
    			else
	    			$uurl = "http://".$ip."/studentPage/". $user_id .".html";
		}

		if ( $error == "請填妥" ) {
			if ( $btn == "網頁預覽" || $btn == "PREVIEW") {
				show_homepage ( );
				exit;
			}
			else if ( $btn == "確定" || $btn =="Submit" ) {
				$add = add_page ( );
				if ( $add == 1 ) {
					$error = "資料加入成功\";
					$Q2 = "update user set sex = '$sex', birthday = '$year-$month-$day', tel = '$tel',
						addr = '$addr', email = '$email', php = '$uurl', job = '$job', introduction = '$intro',
				  		interest = '$interest', skill = '$skill', nickname = '$nickname' where id = '$user_id'";
 					if ( !($result = mysql_db_query( $DB, $Q2  )) )
						$error = "資料庫寫入錯誤!!";
				}
				else
					$error = "網頁建立失敗";
			}
			else
				$error = "";
		}
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if( $version == "C" )
			$tpl->define ( array ( body => "SSModifyFrame1.tpl") );
		else
			$tpl->define ( array ( body => "SSModifyFrame1_E.tpl") );
		$tpl->assign ( ID, $row['id'] );
		$tpl->assign( SKINNUM , $skinnum );
		$name = stripslashes( $name );
		$nickname = stripslashes( $nickname );
		$interest = stripslashes( $interest );
		$skill = stripslashes( $skill );
		$intro = stripslashes( $intro );
		$tel = stripslashes( $tel );
		$addr = stripslashes( $addr );
		$email = stripslashes( $email );
		
		$tpl->assign ( NAME, $name );
		$tpl->assign ( NICK, $nickname );
		$tpl->assign ( "BL$blood", "selected" );
		$tpl->assign ( "ST$star", "selected" );
		$tpl->assign ( INTEREST, $interest );
		$tpl->assign ( "SEX$sex", "selected" );

		//學生不能改變 改為跟sybase同步
		$row['job'] = iconv('utf-8','big5',$row['job']);
		$tpl->assign ( JOB,  $row['job'] );
/*
		if ( $job == "資工系" )
			$tpl->assign ( JOB01, "selected" );
		else if ( $job == "資工所" )
			$tpl->assign ( JOB02, "selected" );
		else if ( $job == "電機系" )
			$tpl->assign ( JOB03, "selected" );
		else if ( $job == "電機所" )
			$tpl->assign ( JOB04, "selected" );
		else if ( $job == "機械系" )
			$tpl->assign ( JOB05, "selected" );
		else if ( $job == "機械所" )
			$tpl->assign ( JOB06, "selected" );
		else if ( $job == "化工系" )
			$tpl->assign ( JOB07, "selected" );
		else if ( $job == "化工所" )
			$tpl->assign ( JOB08, "selected" );
		else if ( $job == "通訊工程系" )
			$tpl->assign ( JOB67, "selected" );
		else if ( $job == "通訊工程所" )
			$tpl->assign ( JOB09, "selected" );
		else if ( $job == "機電所" )
			$tpl->assign ( JOB10, "selected" );
		else if ( $job == "數學系" )
			$tpl->assign ( JOB11, "selected" );
		else if ( $job == "應數所" )
			$tpl->assign ( JOB12, "selected" );
		else if ( $job == "地震所" )
			$tpl->assign ( JOB13, "selected" );
		else if ( $job == "物理系" )
			$tpl->assign ( JOB14, "selected" );
		else if ( $job == "物理所" )
			$tpl->assign ( JOB15, "selected" );
		else if ( $job == "化學系" )
			$tpl->assign ( JOB16, "selected" );
		else if ( $job == "化學所" )
			$tpl->assign ( JOB17, "selected" );
		else if ( $job == "數統所" )
			$tpl->assign ( JOB18, "selected" );
		else if ( $job == "統科所" )
			$tpl->assign ( JOB19, "selected" );
		else if ( $job == "應用地物所" )
			$tpl->assign ( JOB20, "selected" );
		else if ( $job == "數學所" )
			$tpl->assign ( JOB21, "selected" );
		else if ( $job == "分子生物所" )
			$tpl->assign ( JOB22, "selected" );
		else if ( $job == "經濟學系" )
			$tpl->assign ( JOB23, "selected" );
		else if ( $job == "國經所" )
			$tpl->assign ( JOB24, "selected" );
		else if ( $job == "財金系" )
			$tpl->assign ( JOB25, "selected" );
		else if ( $job == "財金所" )
			$tpl->assign ( JOB26, "selected" );
		else if ( $job == "企管系" )
			$tpl->assign ( JOB27, "selected" );
		else if ( $job == "企管所" )
			$tpl->assign ( JOB28, "selected" );
		else if ( $job == "會計系" )
			$tpl->assign ( JOB29, "selected" );
		else if ( $job == "會計所" )
			$tpl->assign ( JOB30, "selected" );
		else if ( $job == "資管系" )
			$tpl->assign ( JOB31, "selected" );
		else if ( $job == "資管所" )
			$tpl->assign ( JOB32, "selected" );
		else if ( $job == "社福系" )
			$tpl->assign ( JOB33, "selected" );
		else if ( $job == "社福所" )
			$tpl->assign ( JOB34, "selected" );
		else if ( $job == "心理系" )
			$tpl->assign ( JOB35, "selected" );
		else if ( $job == "心理所" )
			$tpl->assign ( JOB36, "selected" );
		else if ( $job == "勞工系" )
			$tpl->assign ( JOB37, "selected" );
		else if ( $job == "勞工所" )
			$tpl->assign ( JOB38, "selected" );
		else if ( $job == "政治系" )
			$tpl->assign ( JOB39, "selected" );
		else if ( $job == "政治所" )
			$tpl->assign ( JOB40, "selected" );
		else if ( $job == "傳播系" )
			$tpl->assign ( JOB41, "selected" );
		else if ( $job == "電傳所" )
			$tpl->assign ( JOB42, "selected" );
		else if ( $job == "學程中心" )
			$tpl->assign ( JOB43, "selected" );
		else if ( $job == "中文系" )
			$tpl->assign ( JOB44, "selected" );
		else if ( $job == "中文所" )
			$tpl->assign ( JOB45, "selected" );
		else if ( $job == "外文系" )
			$tpl->assign ( JOB46, "selected" );
		else if ( $job == "外文所" )
			$tpl->assign ( JOB47, "selected" );
		else if ( $job == "歷史系" )
			$tpl->assign ( JOB48, "selected" );
		else if ( $job == "歷史所" )
			$tpl->assign ( JOB49, "selected" );
		else if ( $job == "哲學系" )
			$tpl->assign ( JOB50, "selected" );
		else if ( $job == "哲學所" )
			$tpl->assign ( JOB51, "selected" );
		else if ( $job == "語言所" )
			$tpl->assign ( JOB52, "selected" );
		else if ( $job == "比較文學所" )
			$tpl->assign ( JOB53, "selected" );
		else if ( $job == "法律系" )
			$tpl->assign ( JOB54, "selected" );
		else if ( $job == "法律所" )
			$tpl->assign ( JOB55, "selected" );
		else if ( $job == "法學組" )
			$tpl->assign ( JOB56, "selected" );
		else if ( $job == "財經法組" )
			$tpl->assign ( JOB57, "selected" );
		else if ( $job == "法制組" )
			$tpl->assign ( JOB58, "selected" );
		else if ( $job == "財經所" )
			$tpl->assign ( JOB59, "selected" );
		else if ( $job == "成教系" )
			$tpl->assign ( JOB60, "selected" );
		else if ( $job == "成教所" )
			$tpl->assign ( JOB61, "selected" );
		else if ( $job == "教育所" )
			$tpl->assign ( JOB62, "selected" );
		else if ( $job == "犯防系" )
			$tpl->assign ( JOB63, "selected" );
		else if ( $job == "犯防所" )
			$tpl->assign ( JOB64, "selected" );
		else if ( $job == "休閒教育研究所" )
			$tpl->assign ( JOB65, "selected" );
		else
			$tpl->assign ( JOB66, "selected" );
*/			
		$tpl->assign ( YEAR, $year );
		$tpl->assign ( "M$month", "selected" );
		$tpl->assign ( "D$day", "selected" );
		$tpl->assign ( SKILL, $skill );
		$tpl->assign ( INTRO, $intro );
		$tpl->assign ( TEL, $tel );
		$tpl->assign ( ADDR, $addr );
		$tpl->assign ( EMAIL, $email );
		$tpl->assign ( "P$pageKind", "checked" );
		$tpl->assign ( URL, $uurl );
	}	
	else {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if( $version == "C" )
			$tpl->define ( array ( body => "SSModifyFrame1.tpl") );
		else
			$tpl->define ( array ( body => "SSModifyFrame1_E.tpl") );
		$tpl->assign ( ID, $row['id'] );
		$tpl->assign( SKINNUM , $skinnum );
		$ip = getenv("SERVER_NAME");
		if ( $ip == "" )
			$ip = $SERVER_NAME;

		$path = "../../studentPage/". $user_id .".html";
		if ( is_file ( $path ) ) {
			$fp=fopen( $path, "r");
			$tmp = fread($fp, filesize($path) );				
			fclose($fp);
			if ( ($buf = stristr($tmp,"血型") ) != NULL) {
				$i = 0;
				while($buf{$i}!='%')
					$i++;
				$i += 3;
				if ( $buf{$i} == "A" && $buf{$i + 1 } == "B" )
					$tpl->assign ( "BL4", "selected" );
				else if ( $buf{$i} == "A" )
					$tpl->assign ( "BL2", "selected" );
				else if ( $buf{$i} == "B" )
					$tpl->assign ( "BL3", "selected" );
				else
					$tpl->assign ( "BL1", "selected" );
			}
			if ( ($buf = stristr($tmp,"星座") ) != NULL) {
				$i = 0;
				while($buf{$i}!='%')
					$i++;
				$i += 3;
				for ( $j = 0; $j <= 5; $j ++, $i ++ )
					$tmp2{$j} = $buf{$i};
				$tmp2 = implode("", $tmp2);
				if( $tmp2 == "牡羊座" )
					$tpl->assign ( "ST01", "selected" );
				else if($tmp2 == "金牛座")
					$tpl->assign ( "ST02", "selected" );
				else if($tmp2 == "雙子座")
					$tpl->assign ( "ST03", "selected" );
				else if($tmp2 == "巨蟹座")
					$tpl->assign ( "ST04", "selected" );
				else if($tmp2 == "獅子座")
					$tpl->assign ( "ST05", "selected" );
				else if($tmp2 == "處女座")
					$tpl->assign ( "ST06", "selected" );
				else if($tmp2 == "天秤座")
					$tpl->assign ( "ST07", "selected" );
				else if($tmp2 == "天蠍座")
					$tpl->assign ( "ST08", "selected" );
				else if($tmp2 == "射手座")
					$tpl->assign ( "ST09", "selected" );
				else if($tmp2 == "魔羯座")
					$tpl->assign ( "ST10", "selected" );
				else if($tmp2 == "水瓶座")
					$tpl->assign ( "ST11", "selected" );
				else
					$tpl->assign ( "ST12", "selected" );
			}
		}

		$tpl->assign ( NAME, $row['name'] );
		$tpl->assign ( NICK, $row['nickname'] );
		$tpl->assign ( INTEREST, $row['interest'] );
		$tpl->assign ( "SEX".$row['sex'], "selected" );

		//學生不能改變 改為跟sybase同步
		$row['job'] = iconv('utf-8','big5',$row['job']);
		$tpl->assign ( JOB,  $row['job'] );
/*		
		if ( $row['job'] == "資工系" )
			$tpl->assign ( JOB01, "selected" );
		else if ( $row['job'] == "資工所" )
			$tpl->assign ( JOB02, "selected" );
		else if ( $row['job'] == "電機系" )
			$tpl->assign ( JOB03, "selected" );
		else if ( $row['job'] == "電機所" )
			$tpl->assign ( JOB04, "selected" );
		else if ( $row['job'] == "機械系" )
			$tpl->assign ( JOB05, "selected" );
		else if ( $row['job'] == "機械所" )
			$tpl->assign ( JOB06, "selected" );
		else if ( $row['job'] == "化工系" )
			$tpl->assign ( JOB07, "selected" );
		else if ( $row['job'] == "化工所" )
			$tpl->assign ( JOB08, "selected" );
		else if ( $row['job'] == "通訊工程所" )
			$tpl->assign ( JOB09, "selected" );
		else if ( $row['job'] == "機電所" )
			$tpl->assign ( JOB10, "selected" );
		else if ( $row['job'] == "數學系" )
			$tpl->assign ( JOB11, "selected" );
		else if ( $row['job'] == "應數所" )
			$tpl->assign ( JOB12, "selected" );
		else if ( $row['job'] == "地震所" )
			$tpl->assign ( JOB13, "selected" );
		else if ( $row['job'] == "物理系" )
			$tpl->assign ( JOB14, "selected" );
		else if ( $row['job'] == "物理所" )
			$tpl->assign ( JOB15, "selected" );
		else if ( $row['job'] == "化學系" )
			$tpl->assign ( JOB16, "selected" );
		else if ( $row['job'] == "化學所" )
			$tpl->assign ( JOB17, "selected" );
		else if ( $row['job'] == "數統所" )
			$tpl->assign ( JOB18, "selected" );
		else if ( $row['job'] == "統科所" )
			$tpl->assign ( JOB19, "selected" );
		else if ( $row['job'] == "應用地物所" )
			$tpl->assign ( JOB20, "selected" );
		else if ( $row['job'] == "數學所" )
			$tpl->assign ( JOB21, "selected" );
		else if ( $row['job'] == "分子生物所" )
			$tpl->assign ( JOB22, "selected" );
		else if ( $row['job'] == "經濟學系" )
			$tpl->assign ( JOB23, "selected" );
		else if ( $row['job'] == "國經所" )
			$tpl->assign ( JOB24, "selected" );
		else if ( $row['job'] == "財金系" )
			$tpl->assign ( JOB25, "selected" );
		else if ( $row['job'] == "財金所" )
			$tpl->assign ( JOB26, "selected" );
		else if ( $row['job'] == "企管系" )
			$tpl->assign ( JOB27, "selected" );
		else if ( $row['job'] == "企管所" )
			$tpl->assign ( JOB28, "selected" );
		else if ( $row['job'] == "會計系" )
			$tpl->assign ( JOB29, "selected" );
		else if ( $row['job'] == "會計所" )
			$tpl->assign ( JOB30, "selected" );
		else if ( $row['job'] == "資管系" )
			$tpl->assign ( JOB31, "selected" );
		else if ( $row['job'] == "資管所" )
			$tpl->assign ( JOB32, "selected" );
		else if ( $row['job'] == "社福系" )
			$tpl->assign ( JOB33, "selected" );
		else if ( $row['job'] == "社福所" )
			$tpl->assign ( JOB34, "selected" );
		else if ( $row['job'] == "心理系" )
			$tpl->assign ( JOB35, "selected" );
		else if ( $row['job'] == "心理所" )
			$tpl->assign ( JOB36, "selected" );
		else if ( $row['job'] == "勞工系" )
			$tpl->assign ( JOB37, "selected" );
		else if ( $row['job'] == "勞工所" )
			$tpl->assign ( JOB38, "selected" );
		else if ( $row['job'] == "政治系" )
			$tpl->assign ( JOB39, "selected" );
		else if ( $row['job'] == "政治所" )
			$tpl->assign ( JOB40, "selected" );
		else if ( $row['job'] == "傳播系" )
			$tpl->assign ( JOB41, "selected" );
		else if ( $row['job'] == "電傳所" )
			$tpl->assign ( JOB42, "selected" );
		else if ( $row['job'] == "學程中心" )
			$tpl->assign ( JOB43, "selected" );
		else if ( $row['job'] == "中文系" )
			$tpl->assign ( JOB44, "selected" );
		else if ( $row['job'] == "中文所" )
			$tpl->assign ( JOB45, "selected" );
		else if ( $row['job'] == "外文系" )
			$tpl->assign ( JOB46, "selected" );
		else if ( $row['job'] == "外文所" )
			$tpl->assign ( JOB47, "selected" );
		else if ( $row['job'] == "歷史系" )
			$tpl->assign ( JOB48, "selected" );
		else if ( $row['job'] == "歷史所" )
			$tpl->assign ( JOB49, "selected" );
		else if ( $row['job'] == "哲學系" )
			$tpl->assign ( JOB50, "selected" );
		else if ( $row['job'] == "哲學所" )
			$tpl->assign ( JOB51, "selected" );
		else if ( $row['job'] == "語言所" )
			$tpl->assign ( JOB52, "selected" );
		else if ( $row['job'] == "比較文學所" )
			$tpl->assign ( JOB53, "selected" );
		else if ( $row['job'] == "法律系" )
			$tpl->assign ( JOB54, "selected" );
		else if ( $row['job'] == "法律所" )
			$tpl->assign ( JOB55, "selected" );
		else if ( $row['job'] == "法學組" )
			$tpl->assign ( JOB56, "selected" );
		else if ( $row['job'] == "財經法組" )
			$tpl->assign ( JOB57, "selected" );
		else if ( $row['job'] == "法制組" )
			$tpl->assign ( JOB58, "selected" );
		else if ( $row['job'] == "財經所" )
			$tpl->assign ( JOB59, "selected" );
		else if ( $row['job'] == "成教系" )
			$tpl->assign ( JOB60, "selected" );
		else if ( $row['job'] == "成教所" )
			$tpl->assign ( JOB61, "selected" );
		else if ( $row['job'] == "教育所" )
			$tpl->assign ( JOB62, "selected" );
		else if ( $row['job'] == "犯防系" )
			$tpl->assign ( JOB63, "selected" );
		else if ( $row['job'] == "犯防所" )
			$tpl->assign ( JOB64, "selected" );
		else if ( $row['job'] == "休閒教育研究所" )
			$tpl->assign ( JOB65, "selected" );
		else
			$tpl->assign ( JOB66, "selected" );
*/
		$tpl->assign ( YEAR, $row['birthday'][0].$row['birthday'][1].$row['birthday'][2].$row['birthday'][3] );
		$month = $row['birthday'][5].$row['birthday'][6];
		if ( $month == "" )
			$month = "01";
		$tpl->assign ( "M$month", "selected" );
		$day = $row['birthday'][8].$row['birthday'][9];
		if ( $day == "" )
			$day = "01";
		$tpl->assign ( "D$day", "selected" );
		$tpl->assign ( SKILL, $row['skill'] );
		$tpl->assign ( INTRO, $row['introduction'] );
		$tpl->assign ( TEL, $row['tel'] );
		$tpl->assign ( ADDR, $row['addr'] );
		$tpl->assign ( EMAIL, $row['email'] );
		if ( $row['php'] == "" ) {
			$tpl->assign ( "P1", "checked" );
			$tpl->assign ( URL, "http://" );
		}
		else {
			$tpl->assign ( "P2", "checked" );
			$tpl->assign ( URL, $row['php'] );
		}
	}

	$tpl->assign ( VERSION, $version );
	$tpl->assign ( MES, $error );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
	
	function add_page ( ) {
		global $course_id, $user_id, $name, $sex, $job, $email, $blood, $star, $interest, $skill, $intro, $color;
		$path = "../../studentPage/". $user_id .".html";
		$name2 = stripslashes( $name );
		$interest2 = stripslashes( $interest );
		$skill2 = stripslashes( $skill );
		$intro2 = stripslashes( $intro );
		$email2 = stripslashes( $email );
		$job2 = stripslashes( $job );
		if ($fs = fopen( $path , "w")) {
			fwrite($fs, "<html>\n");
			fwrite($fs, "<head>\n");
			fwrite($fs,"<META HTTP-EQUIV=\"Expires\" CONTENT=0>\n");
			fwrite($fs, "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=big5\">\n");
			fwrite($fs, "<title>".$user_id."的網頁</title>\n");
			fwrite($fs, "</head>\n");
			fwrite($fs, "<body background=\"/images/img/bg.gif\">\n");
			fwrite($fs, "<div align=\"center\">\n");
			fwrite($fs, "<center>\n");
			fwrite($fs, "<table border=\"2\" cellspacing=\"4\" width=\"90%\">\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"20%\" rowspan=\"10\" valign=\"top\" align=\"center\"><img border=\"0\" src=\"/studentPage/".$user_id.".gif\" width=\"100%\"></td>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">姓名 :&nbsp;</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">". $name2 ."</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><font color=\"#FFFF00\"><b>性別 :&nbsp;</b></font></td>\n");
			if($sex == 0)
				fwrite($fs, "<td width=\"64%\">女</td>\n");
			else
				fwrite($fs, "<td width=\"64%\">男</td>\n");	
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">系所 :&nbsp;</font></b></td>\n");

			if($job == NULL)
				fwrite($fs, "<td width=\"64%\">N/A</td>\n");
			else
				fwrite($fs, "<td width=\"64%\">$job2</td>\n");

			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#33CC33\">E-mail :&nbsp;</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">".$email2." </td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">血型 :&nbsp;</font></b></td>\n");
			if($blood == 0)
				fwrite($fs, "<td width=\"64%\">N/A</td>\n");
			else if($blood ==1)
				fwrite($fs, "<td width=\"64%\">O型</td>\n");
			else if($blood ==2)
				fwrite($fs, "<td width=\"64%\">A型</td>\n");
			else if($blood ==3)
				fwrite($fs, "<td width=\"64%\">B型</td>\n");
			else if($blood ==4)
				fwrite($fs, "<td width=\"64%\">AB型</td>\n");
			else
				fwrite($fs, "<td width=\"64%\">Error</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#FFFF00\">星座 :&nbsp;</font></b></td>\n");
			if($star == "00")
				fwrite($fs, "<td width=\"64%\">N/A</td>\n");
			else if($star == "01")
				fwrite($fs, "<td width=\"64%\">牡羊座</td>\n");
			else if($star == "02")
				fwrite($fs, "<td width=\"64%\">金牛座</td>\n");
			else if($star == "03")
				fwrite($fs, "<td width=\"64%\">雙子座</td>\n");
			else if($star == "04")
				fwrite($fs, "<td width=\"64%\">巨蟹座</td>\n");
			else if($star == "05")
				fwrite($fs, "<td width=\"64%\">獅子座</td>\n");
			else if($star == "06")
				fwrite($fs, "<td width=\"64%\">處女座</td>\n");
			else if($star == "07")
				fwrite($fs, "<td width=\"64%\">天秤座</td>\n");
			else if($star == "08")
				fwrite($fs, "<td width=\"64%\">天蠍座</td>\n");
			else if($star == "09")
				fwrite($fs, "<td width=\"64%\">射手座</td>\n");
			else if($star == "10")
				fwrite($fs, "<td width=\"64%\">魔羯座</td>\n");
			else if($star == "11")
				fwrite($fs, "<td width=\"64%\">水瓶座</td>\n");
			else if($star == "12")
				fwrite($fs, "<td width=\"64%\">雙魚座</td>\n");
			else
				fwrite($fs, "<td width=\"64%\">Error</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");

			$content = $interest2;
			$content = str_replace ( "\n", "<BR>", $content );
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">興趣 :</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">". $content ."</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");

			$content = $skill2;
			$content = str_replace ( "\n", "<BR>", $content );
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#33CC33\">專長 :</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">".$content."</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");

			fwrite($fs, "<td width=\"16%\"><b><font color=\"#33CC33\">個性顏色 :</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\"><!--begin-->".$color."<!--end--></td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "<tr>\n");

			$content = $intro2;
			$content = str_replace ( "\n", "<BR>", $content );
			fwrite($fs, "<td width=\"16%\"><b><font color=\"#0000FF\">個人簡介 :</font></b></td>\n");
			fwrite($fs, "<td width=\"64%\">".$content."</td>\n");
			fwrite($fs, "</tr>\n");
			fwrite($fs, "</table>\n");
			fwrite($fs, "</center>\n");
			fwrite($fs, "</div>\n");
			fwrite($fs, "</body>\n");
			fwrite($fs, "</html>\n");
			fclose($fs);
		}
		else
			return 0;
		return 1;
	}
	
	function show_homepage( ) {
		global $course_id, $user_id, $name, $sex, $job, $email, $blood, $star, $interest, $skill, $intro, $color, $nickname, $year, $month, $day, $tel, $addr, $pageKind, $uurl;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "stu_page.tpl" ) );
		$name2 = stripslashes( $name );
		$nickname2 = stripslashes( $nickname );
		$interest2 = stripslashes( $interest );
		$skill2 = stripslashes( $skill );
		$intro2 = stripslashes( $intro );
		$email2 = stripslashes( $email );
		$job2 = stripslashes( $job );
		$tpl->assign( NAME, $name2 );
		$tpl->assign( NICK, $nickname2 );
		$tpl->assign( USER, $user_id );
		$tpl->assign( YEAR, $year );
		$tpl->assign( MONTH, $month );
		$tpl->assign( TEL, $tel );
		$tpl->assign( ADDR, $addr );
		$tpl->assign( DAY, $day );
		$tpl->assign( PAGEKIND, $pageKind );
		$tpl->assign( UURL, $uurl );
		$tpl->assign( SEN , $sex );
		if ( $sex == 1 )
			$tpl->assign( SEX , "男" );
		else
			$tpl->assign( SEX , "女" );

		if($job == NULL)
			$tpl->assign( JOB , "N/A" );
		else
			$tpl->assign( JOB , $job2 );

		$tpl->assign( EMAIL , $email2 );
		$tpl->assign( BLOON , $blood );
		if($blood == 0)
			$tpl->assign( BLOOD , "N/A" );
		else if($blood ==1)
			$tpl->assign( BLOOD , "O型" );
		else if($blood ==2)
			$tpl->assign( BLOOD , "A型" );
		else if($blood ==3)
			$tpl->assign( BLOOD , "B型" );
		else if($blood ==4)
			$tpl->assign( BLOOD , "AB型" );
		else
			$tpl->assign( BLOOD , "Error" );
		$tpl->assign( STAN , $star );
		if($star == "00")
			$tpl->assign( STAR , "N/A" );
		else if($star == "01")
			$tpl->assign( STAR , "牡羊座" );
		else if($star == "02")
			$tpl->assign( STAR , "金牛座" );
		else if($star == "03")
			$tpl->assign( STAR , "雙子座" );
		else if($star == "04")
			$tpl->assign( STAR , "巨蟹座" );
		else if($star == "05")
			$tpl->assign( STAR , "獅子座" );
		else if($star == "06")
			$tpl->assign( STAR , "處女座" );
		else if($star == "07")
			$tpl->assign( STAR , "天秤座" );
		else if($star == "08")
			$tpl->assign( STAR , "天蠍座" );
		else if($star == "09")
			$tpl->assign( STAR , "射手座" );
		else if($star == "10")
			$tpl->assign( STAR , "魔羯座" );
		else if($star == "11")
			$tpl->assign( STAR , "水瓶座" );
		else if($star == "12")
			$tpl->assign( STAR , "雙魚座" );
		else
			$tpl->assign( STAR , "Error" );

		$tpl->assign( CLR , $color);

		$content = $interest2;
		$tpl->assign( INTERESN , $interest2 );
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign( INTEREST , $content );
		$content = $skill2;
		$tpl->assign( SKILN , $skill2 );
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign( SKILL , $content );
		$content = $intro2;
		$tpl->assign( INTRN , $intro2 );
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign( INTRO , $content );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
