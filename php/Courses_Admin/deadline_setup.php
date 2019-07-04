<?PHP
	require 'fadmin.php';

	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		
		if($action == "submit"){
			$sdl = $year1."-".$month1."-".$day1;
			$gdl = $year2."-".$month2."-".$day2;
			$start = $year3."-".$month3."-".$day3;
			$end = $year4."-".$month4."-".$day4;
			$Qs = "update extra set s_deadline='$sdl', g_deadline='$gdl', year='$year', term='$term', start_date='$start', end_date='$end'";
			if (!($result = mysql_db_query($DB,$Qs))){
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Qs<br>";
			}
			echo "<body background = \"../../images/img/bg.gif\"><br><center><a href=../check_admin.php>更新完成，回系統管理介面</a></center></body>";
		}
		else{
			include("class.FastTemplate.php3");
			$tpl=new FastTemplate("./templates");
			$tpl->define(array(main=>"deadline_setup.tpl"));
			// 取出成績送交截止日
			$Qs = "select s_deadline, g_deadline, year, term, start_date, end_date from extra";
			if ($result = mysql_db_query($DB,$Qs)){
				if(($row = mysql_fetch_array($result))!=0){
					$sd = explode('-', $row['s_deadline']);
					$gd = explode('-', $row['g_deadline']);
					$st = explode('-', $row['start_date']);
					$ed = explode('-', $row['end_date']);
					$tpl->assign( YEAR, $row['year'] );
					$tpl->assign( TERM, $row['term'] );
					$tpl->assign( SY, $sd[0] );
					$tpl->assign( SM, $sd[1] );
					$tpl->assign( SD, $sd[2] );
					$tpl->assign( GY, $gd[0] );
					$tpl->assign( GM, $gd[1] );
					$tpl->assign( GD, $gd[2] );
					$tpl->assign( YS, $st[0] );
					$tpl->assign( MS, $st[1] );
					$tpl->assign( DS, $st[2] );
					$tpl->assign( YE, $ed[0] );
					$tpl->assign( ME, $ed[1] );
					$tpl->assign( DE, $ed[2] );
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Qs<br>";
			}
			$tpl->parse(BODY,"main");
			$tpl->FastPrint("BODY");
		}
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
	
?>