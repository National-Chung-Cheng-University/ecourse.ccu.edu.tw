<?
//  以時間 作為判斷此使用者是否在線上的依據
//  每分鐘此php會reload一次, 此時會將超過6分未回應的使用者消掉
//  檔案格式為   $user_id, date("U") ,$course_id
//	8/5 加入log
//  會使用到session中的$time.

	require 'common.php';
	if ( !(isset($PHPSESSID) && $user_type = session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
		exit;
	}

	$ip = getenv ( "REMOTE_ADDR" );
	if ( $ip == "" )
		$ip = $HTTP_X_FORWARDED_FOR;
	if ( $ip == "" )
		$ip = $REMOTE_ADDR;

	//$refreshmin = 1.5;
	$refreshmin = 6;   //heater 0622
	$countnow = 0;
	$countcourse = 0;

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	$Q1 = "delete from online where (".date("U")." - time) > ($refreshmin * 60)";
	$Q2 = "select a_id, idle from online where user_id = '$user_id' and host = '$ip'";
	$Q3 = "select count(a_id) as sum from online";
	$Q4 = "select count(a_id) as sum from online where course_id = '$course_id' group by course_id";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo( "資料庫連結錯誤!!" );
		exit;
	}
//	echo $Q1;
//	mysql_db_query( $DB, $Q1 );
	if ( $result2 = mysql_db_query( $DB, $Q2 ) ) {
		if ( mysql_num_rows( $result2 ) == 0 ) {
			$Q22 = "insert into online ( course_id, user_id, host, time, idle ) values ('$course_id', '$user_id', '$ip', '".date("U")."', '".date("U")."')";
			$stime = date("U");
		}
		else {
			$row_online = mysql_fetch_array( $result2 );
			if ( $move == 1 ) {
				$Q22 = "update online set course_id='$course_id', time='".date("U")."', host='$ip', idle='".date("U")."' where a_id = '".$row_online['a_id']."'";
				$stime = date("U");
			}
			else {
				$Q22 = "update online set course_id='$course_id', time='".date("U")."', host='$ip' where a_id = '".$row_online['a_id']."'";
				$stime = $row_online['idle'];
			}
		}
		mysql_db_query( $DB, $Q22 );
	}
	if ( $user_id != "guest" ) {
		$Q24 = "select a_id ,forbear from user where id = '$user_id'";
		if ( $result24 = mysql_db_query( $DB, $Q24 ) )
			$row24 = mysql_fetch_array( $result24 );
		if ( $move == 1 ) {
			if ( (date("U") - $respone) > 5 ) {
				
				if ( $row24['forbear'] <= 180 )
					$forb = 180;
				else
					$forb = $row24['forbear'] - 30;
			}
			else if( (date("U") - $respone) > 300 ) {
				session_destroy($PHPSESSID);
				exit;
			}
			else
				$forb = $row24['forbear'] + 30;
			$Q23 = "update user set forbear = $forb where id = '$user_id'";
			mysql_db_query( $DB, $Q23 );
			$row24['forbear'] = $forb;
		}

		// 先查出user的a_id
		$sql = "select a_id from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql);
		$row = mysql_fetch_array($result);
		$aid = $row["a_id"];
	
		// 寫入log.
		$usedtime = date("U") - $time;
		$time = date("U");
		add_log ( 7, $user_id, "" , $course_id, $usedtime );
		$receive = show_message ( $aid, 1 );
	}
	if ( $result3 = mysql_db_query( $DB, $Q3 ) ) {
		$row3 = mysql_fetch_array( $result3 );
		$countnow = $row3['sum'];
	}
	if ( $result4 = mysql_db_query( $DB, $Q4 ) ) {
		$row4 = mysql_fetch_array( $result4 );
		$countcourse = $row4['sum'];
	}

	//閒置通告

	if ( $user_type == 1 ) {
		$sysmeg[0] = "還在用功嗎?\\n加油呀!!";
		$sysmeg[1] = "螢幕看久了要休息呦!!\\n對眼睛比較好^^";
		$sysmeg[2] = "真好!你還在!!\\n努力用功吧";
		$sysmeg[3] = "你已經上站很久了!!\\n真是好學生!!";
		$sysmeg[4] = "螢幕看久了!!\\n記得起來走走呀!!";
		$sysmeg[5] = "坐久了!!\\n記得起來做做體操呀!!";
		$sysmeg[6] = "天氣冷!!\\n記得多加些衣服呀!!";
		$sysmeg[7] = "唸書會不會很寂寞呀!!\\n沒關係我們與你同在!!";
		$sysmeg[8] = "你看有流星 ☆☆☆!!\\n騙你的^^ 只是看看你有沒有專心!!";
		$sysmeg[9] = "今天太陽暖暖的\\n有出去曬曬太陽嗎!!";
		$sysmeg[10] = "再坐下去要發霉啦!!\\n快起來動動!!";
		$sysmeg[11] = "人不是鐵打的呀!!\\n書要唸 身體也要顧好呦!!";
		$sysmeg[12] = "專心度測試!!\\n請在五秒內回應訊息!!";
	}
	else {
		$sysmeg[0] = "還在工作嗎?\\n加油呀!!";
		$sysmeg[1] = "工作久了要休息呀!!\\n對眼睛比較好^^";
		$sysmeg[2] = "真好!你還在!!\\n努力吧";
		$sysmeg[3] = "你已經上站很久了!!\\n真是好老師!!";
		$sysmeg[4] = "螢幕看久了!!\\n記得起來走走呀!!";
		$sysmeg[5] = "坐久了!!\\n記得起來做做體操呀!!";
		$sysmeg[6] = "天氣冷!!\\n記得多加些衣服呀!!";
		$sysmeg[7] = "工作會不會很寂寞呀!!\\n沒關係我們與你同在!!";
		$sysmeg[8] = "今天太陽暖暖的\\n有出去曬曬太陽嗎!!";
		$sysmeg[9] = "再坐下去要發霉啦!!\\n快起來動動!!";
		$sysmeg[10] = "你看有流星 ☆☆☆!!\\n騙你的^^ 只是看看你有沒有專心!!";
		$sysmeg[11] = "人不是鐵打的呀!!\\n工作要做 身體也要顧好呦!!";
		$sysmeg[12] = "專心度測試!!\\n請在五秒內回應訊息!!";
	}

	if ( $row24['forbear'] != NULL )
		$forbear = $row24['forbear'];
	else
		$forbear = 1800;
		
	/*專心度測試
	if ( ( (date("U") - $stime) >= $forbear ) && $receive != 1 ) {
		if ( $version == "C" ) {
			$name = "From 系統訊息:";
			$message = $sysmeg[12];
			//$message = $sysmeg[(date("U") - $stime)%13];
		}
		else {
			$name = "From System Messager:";
			$message = $sysmeg[12];
			//$message = $sysmeg[(date("U") - $stime)%13];
		}
		$receive = 1;
		$system = 1;
		$move = 1;
	}
	*/
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
        
	if($version == "C") {
	   	$tpl->define(array(main => "online.tpl"));
	}
	else {
	   	$tpl->define(array(main => "online_E.tpl"));
	}   	
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign("COUNTNOW", $countnow);
	$tpl->assign("COUNTCOURSE", $countcourse);
	$tpl->assign("PHPID", $PHPSESSID);
	if ( $close == 1 )
		$tpl->assign("CLOSE", "");
	else
		$tpl->assign("CLOSE", "//");

	if ( $receive == 1 ) {
		if ( $system != 1 ) {
			$tpl->assign("SYS", "//");
			$tpl->assign("MOVE", "//");
			$tpl->assign("HAVE", "");
			$tpl->assign("USER", $id);
			$tpl->assign("MID", $a_id);
			$tpl->assign("TIME", $posttime);
			$tpl->assign("MULTI", $multi);
			$tpl->assign("MESSAGE", $message);
		}
		else {
			$tpl->assign("SYS", "");
			if ( $move == 1 )
				$tpl->assign("MOVE", "");
			else
				$tpl->assign("MOVE", "//");
			$tpl->assign("HAVE", "//");
			$tpl->assign("RESPONE", date("U"));
			$tpl->assign("SYM", $name."\\n".$message."\\n".$posttime);
		}	
	}
	else {
		$tpl->assign("SYS", "//");
		$tpl->assign("HAVE", "//");
		$tpl->assign("MOVE", "//");
		$tpl->assign("MESSAGE", "");
	}

    $tpl->parse(BODY, "main");
    $tpl->FastPrint(BODY);
?>
