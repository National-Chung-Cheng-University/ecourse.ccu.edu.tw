<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>Untitled Document</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<!--<script type="text/javascript" src="thickbox.js"></script>-->
<!--<link rel="stylesheet" href="thickbox.css" type="text/css" media="screen" />-->

</head>
<body>

<?php
//modify by Autumn
//2002/5/5 PM 17:29
	require 'common.php';
	$refreshmin = 6;
	$time  = $refreshmin * 60;
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	$Q1 = "delete from online where (".date("U")." - time) > '$time'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!1";
		if ( $ver == "C" )
			show_page ( "index_login.tpl", $error, $id );
		else
			show_page ( "index_login_E.tpl", $error, $id );
		exit;
	}
	mysql_db_query( $DB, $Q1 );
	
	 
	//modified by linsy 2012-08-23
        //如果有接收到SSO 網址列的資料 就可以得到session參數
	
        if(isset($_GET['cid']) and (trim($_GET['cid']) != "") and isset($_GET['miXd']) and (trim($_GET['miXd']) != "")){



		//記錄由sso代簽入至cih的次數 by linsy
		$fp = fopen("counter_sso.txt","r+");	//開啟counter_sso.txt
		$counter = fgets($fp,80); //擷取 80 Byte 的資料
		$counter = doubleval($counter) + 1; //將數值加 1
		fseek($fp,0); //將指位器 (pointer) 指回初始位置
		fputs($fp,$counter); //將加完的數值存回 
		fclose($fp); //關閉連線
		//結束記錄程序



		//$test = md5(sso_getIP().substr($_GET['miXd'],-10)).substr($_GET['miXd'],10,6);	
		//echo "1:".$test." 2:".$_GET['cid'];
                if((md5(sso_getIP().substr($_GET['miXd'],-10)).substr($_GET['miXd'],10,6)==$_GET['cid'])){
                //驗證IP是否為同一個IP
			
                        list($status,$enter_ip,$user_id,$person_id)=chk_ssoRight($_GET['miXd']);
                        //接收SSO資料

                        if($status == 1){ //sso端登入成功

                                //登入成功的動作
                                //將資訊丟給子系統端的session去操作
                                $_SESSION['sso_enterip']=$enter_ip;             //使用者端登入IP
                                $_SESSION['sso_personid']=$person_id;           //身份證字號
                                $_SESSION['tokenSso']=$_GET['miXd'];            //sso token
                                $_SESSION['verifySso']="Y";                     //sso登入識別
                               // echo  $_SESSION['verifySso'];
                                //換頁至子系統端登入確認的程式處理各子系統端所需要的額外資訊
                                //header('Location: '.SYS_LOGIN_URL);




                        }
                }
		else
			die("h");
        }
        else if ( isset($_POST['choose'])){
                $_SESSION['verifySso']="Y";
        }
        //若沒有get到任何資料 則設定為直接來自子系統
        else{
                $_SESSION['verifySso']="N";
                $_SESSION['verifyChild']="Y";

        }
	
	$Q_Sso = "select * from user where id = '$_SESSION[sso_personid]'";
	
	//$Q_Sso = "select * from user where identity = 'L123965773'";
        $result_Sso = mysql_db_query( $DB, $Q_Sso );
        $row_Sso= mysql_fetch_array( $result_Sso ) ;
        /*while ( $row_Sso=mysql_fetch_array( $result_Sso ))
        {
                echo "123 ". $row_Sso['identity']."<br>";
        } */      
	//echo $_SESSION['verifySso'];
	
        if( $_SESSION['verifySso'] == "Y"){
                if(!(isset($_POST['choose']) ) ) {
			
                        if (  mysql_num_rows($result_Sso) == 2 )                         // 表示該使用者擁有兩種以上身用，進入chooseTorS.php做身分選擇
                        {
				            //echo "<script language=javascript> alert('您的帳號具有多重身分，請點選底下連結選擇身分別') </script>";
							echo "<br/><br/><br/><br/><center><font color='blue' size='5'>
								您的帳號具有多重身分，請點選底下連結選擇身分別</font><br/><br/>
								<a href='http://" . $SERVER_NAME . "/php/chooseTorS_test.php#TB_inline?height=100&width=150&inlineId=hiddenModalContent&modal=true' class='thickbox'>選擇身分</a></center>";
							
							echo "<div id='hiddenModalContent' style='display:none' >";
							echo "<form method=POST name='testForm_2' action=index_login.php>										
								<center><div>
								請選擇您的身分：<br/>
								教師<input type='radio' name='choose' value ='1'/>
								學生<input type='radio' name='choose' value ='2'/></div><br/>
								<input type='Image' Name='Submit' Src='images/submit.gif' Width='60' Align='Top' Alt='確認送出'></center>
								</form>";
							echo "</div>";							
							
                            exit(0);
                        }
                        else
                        {
				echo "B";
				echo $_SESSION[sso_personid];
                                $id = $row_Sso['id'];
                                $ver = "C";
                                $pass = passwd_decrypt($row_Sso['pass']);
                                $check[0] = $row_Sso['id'];
                                $check[1] = $row_Sso['authorization'];
                                echo  $check[0]." ". $check[1]."<BR>";
                                echo $id." ".$pass."<BR>end";
				
				
                        }
                }
                else
                {
                        echo "row_Sso = ".$row_Sso['identity']."<br>";
                        if($_POST[choose] == "1" )
                        {
                                $Q_Sso1 = "select * from user where identity = '$_SESSION[sso_personid]' and authorization = '1'";
                                $result_Sso1 = mysql_db_query( $DB, $Q_Sso1 );
                                $row_Sso1= mysql_fetch_array( $result_Sso1 ) ;

                                $id = $row_Sso1['identity'];
                                $check[0] = $id;
                                $check[1] = "1";
                                $pass = passwd_decrypt($row_Sso1['pass']);
                                $ver = "C";
                        }
                        else if ($_POST[choose] == "2" )
                        {
                                $Q_Sso3 = "select * from user where identity = '$_SESSION[sso_personid]' and authorization = '3'";
                                $result_Sso3 = mysql_db_query( $DB, $Q_Sso3 );
                                $row_Sso3= mysql_fetch_array( $result_Sso3 ) ;

                                $id = $row_Sso3['id'];
                                $pass = passwd_decrypt($row_Sso3['pass']);
                                $ver = "C";
                                $check[0] = $id;
                                $check[1] = "3";

                        }
                }

        }

	
	//2011.2.15 add by Jim 新增22行~34行
	//判斷帳號及密碼不可為空白
/*	if ( $id == "" )
	{
		//show_page(  "index_login.tpl", "帳號或密碼不可為空白，請重新輸入", $id );
		//echo "<script>alert('帳號或密碼不可為空白，請重新輸入')</script>";
		//exit;
		echo "<html>\n<head>\n";
    echo "<meta http-equiv=\"refresh\" content=\"2;url=http://" . $SERVER_NAME . "\" />\n";
    echo "<title>Ecourse課程平台</title>\n";
    echo "</head>\n<body>\n";
    echo "帳號不可為空白，請重新輸入......三秒後回到<a href=\"http://" . $SERVER_NAME . "\">首頁</a> ...";
    echo "</body>\n</html>";  
    exit; 
	}
	
*/	
	if ( $id != "" && $ver != "" )
	{
/*		if ( $user_id != "guest" )
		{
			$Q2 = "select a_id from online where user_id = '$id'";
			if ( !($result2 = mysql_db_query( $DB, $Q2 )) )
			{
				//exec("/home/study/sh/repairdb");
				$error = "資料庫讀取錯誤!!Online";
				if ( $ver == "C" )
					show_page ( "index_login.tpl", $error, $id );
				else
					show_page ( "index_login_E.tpl", $error, $id );
				exit;
			}
			else
			{
				if ( mysql_num_rows( $result2 ) != 0 && $scorm == 1)
				{
					if ( $ver == "C" )
						show_page ( "index_login.tpl", "你已重複登入 請稍後再登入!!", $id );
					else
						show_page ( "index_login_E.tpl", "You had login before, Please wait for login", $id );
					exit;
				}
			}
		}*/
		$check = auth();

		//重新assign一個session_id
                $second = time();
                $md5str = md5($id.$second);
                //session_id($md5str);

		session_start();
		session_unregister("teacher");
		session_unregister("admin");
		session_unregister("guest");
		session_unregister("user_id");
		session_unregister("version");
		session_unregister("course_id");
		//計算使用時間用
		session_unregister("time");
		session_register("time");
		session_register("user_id");
		session_register("version");
		$time = date("U");
		$user_id = $check[0];
		$version = $ver;
		
		if ( $check[1] == "4" || $check[1] == "2" || $check[1] == "1" ) {
			session_register("teacher");
			$teacher = 1;
		}
		//else if ( $check[1] == "3" ) {
			$Q1 = "select email, pass FROM user where id = '$id'";
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$message = "$message - 資料庫讀取錯誤!!3";
			}else
				$row = mysql_fetch_array( $result );
			
			if ( $row['pass'] == "" || $row['email'] == "") {
				header( "Location: ./Learner_Profile/chang_pass2.php?PHPSESSID=".session_id() );
				exit;
			}
		//}
		add_log ( 1, $id );
		add_log (12, $id );
		add_message ();
		unset($id);
		unset($ver);
		if ( $check[1] == "3") {
			header( "Location: http://$SERVER_NAME/php/Courses_Admin/take_course.php?PHPSESSID=".session_id());
		}
		else if ( $check[1] == "2" || $check[1] == "1" ) {
			header( "Location: http://$SERVER_NAME/php/Courses_Admin/teach_course.php?PHPSESSID=".session_id());
		}
		else if ( $check[1] == "4" ){
			header( "Location: http://$SERVER_NAME/php/Courses_Admin/upload_intro.php?PHPSESSID=".session_id());
		}			
		else {
			header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?PHPSESSID=".session_id());
		}
		exit;
	}
	else if ( !isset($ver) ) {
		if (isset($PHPSESSID) && session_check_stu($PHPSESSID) ) {
			if ( $user_id != "guest" ) {
				$Q2 = "select a_id from online where user_id = '$user_id'";
				if ( !($result2 = mysql_db_query( $DB, $Q2 )) ) {
					$error = "資料庫讀取錯誤!!4";
					if ( $version == "C" )
						show_page ( "index_login.tpl", $error, $id );
					else
						show_page ( "index_login_E.tpl", $error, $id );
					exit;
				}
				else {
					if ( mysql_num_rows( $result2 ) != 0 && $scorm == 1 ) {
						if ( $version == "C" )
							show_page ( "index_login.tpl", "你已重複登入 請稍後再登入!!", $id );
						else
							show_page ( "index_login_E.tpl", "You had login before, Please wait for login", $id );
						exit;
					}
				}
			}
			if ( $teacher == 1 ) {
				header( "Location: http://$SERVER_NAME/php/Courses_Admin/teach_course.php?PHPSESSID=".session_id());
				exit;
			}
			else {
				$ver = $version;
				$id = $user_id;
				$Q1 = "select email, authorization FROM user where id = '$id'";
				if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
					$message = "$message - 資料庫讀取錯誤!!5";
				}else
					$row = mysql_fetch_array( $result );
	
				if ( $row['email'] == "" && $row['authorization'] == "3" )
					header( "Location: ./Learner_Profile/chang_pass2.php?PHPSESSID=".session_id() );
				else {
					if ( $row['authorization'] == "3" ) {
						header( "Location: http://$SERVER_NAME/php/Courses_Admin/take_course.php?PHPSESSID=".session_id());
					}
					else {
						header( "Location: http://$SERVER_NAME/php/Courses_Admin/guest.php?PHPSESSID=".session_id());
					}
				}
				exit;
			}
		}
		else {
			header ( "Location: http://$SERVER_NAME/" );
//			show_page( "index_login.tpl", "登入版本錯誤!!!");
		}
	}
	else {
		if ( $ver == "C" ) {
			if ( isset($id) ) {
				echo "<html>\n<head>\n";
                                echo "<meta http-equiv=\"refresh\" content=\"2;url=http://" . $SERVER_NAME . "\" />\n";
                                echo "<title>Ecourse課程平台</title>\n";
                                echo "</head>\n<body>\n";
                                echo "帳號或密碼錯誤，三秒後回到<a href=\"http://" . $SERVER_NAME . "\">首頁</a> ...";
                                echo "</body>\n</html>";                                                                                     
                                //echo "<script>alert('帳號或密碼錯誤')</script><head><meta http-equiv='refresh' content='0;url=http://$SERVER_NAME/' /> </head>";
                                //header ( "Location: http://$SERVER_NAME/" );
//                              //show_page( "index_login.tpl", "請輸入你的帳號及密碼!!!", $id);
			}
			else {
				echo "<html>\n<head>\n";
                                echo "<meta http-equiv=\"refresh\" content=\"2;url=http://" . $SERVER_NAME . "\" />\n";
                                echo "<title>Ecourse課程平台</title>\n";
                                echo "</head>\n<body>\n";
                                echo "帳號或密碼錯誤，三秒後回到<a href=\"http://" . $SERVER_NAME . "\">首頁</a> ...";
                                echo "</body>\n</html>";

                                //echo "<script>alert('帳號或密碼錯誤')</script><head><meta http-equiv='refresh' content='0;url=http://$SERVER_NAME/' /> </head>";
                                //header ( "Location: http://$SERVER_NAME/" );
//                              //show_page( "index_login.tpl" );
			}
		}
		else {
			if ( isset($id) )
				show_page( "index_login_E.tpl", "Please Input Your ID and PASSWORD!!!", $id );
			else
				show_page( "index_login_E.tpl" );
		}
	}

	function auth() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass, $ver, $GLOBALID;
		global $SERVER_NAME;
		
		$Q1 = "SELECT pass, authorization, id FROM user where id = '$id'";
		$Q2 = "Select pass from user where id='$GLOBALID'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!6";
		}
		else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "資料庫讀取錯誤!!7";
		}
		else if ( !($result2 = mysql_db_query( $DB, $Q2  )) ) {
			$error = "資料庫讀取錯誤!!8";
		}

		$row = mysql_fetch_array($result);
		$row2 = mysql_fetch_array($result2);
		//2006-03-17 devon 學生登入時，如果發現輸入密碼與教學平台上不同
		//則去檢查sybase的選課密碼，再將教學平台上的密碼更新為選課密碼
		//start
		if( ( passwd_encrypt($pass) != $row['pass'] && passwd_encrypt($pass) != $row2['pass'] ) && $row["authorization"] == 3 )
		{
			//連結sybase
			//if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			//	Error_handler( "在 sybase_connect 有錯誤發生" , $cnx );  
			//}
			$pos = strpos($id, '5');
			if($pos != 0 || $pos === false)
				$csd = "academic";		//一般生db
			else
				$csd = "academic_gra";	//專班生db
			
			$conn_string = "host=140.123.30.12 dbname=".$csd." user=acauser password=!!acauser13";
			$cnx = pg_pconnect($conn_string) or die('資料庫沒有回應，請稍後再試');			
			
			//@sybase_select_db($csd, $cnx);
			//$cur = sybase_query("select ps from a11vstd_rec_tea where id='$id'", $cnx);
			$cur = pg_query($cnx, "select ps from a11vstd_rec_tea where id='$id'") or die('資料表不存在，請通知電算中心');
			//$resultsb = sybase_fetch_array($cur);
			$resultsb = pg_fetch_array($cur, null, PGSQL_ASSOC);
			
			//先將本段228~233註解起來, 使校際或免費選修生密碼輸入錯誤時,不會把密碼清空 2009.03.24 by JIM
			if($pass == $resultsb['ps'] && $pass <> '' )
			{
				$Q3 = "update user set pass='".passwd_encrypt($resultsb['ps'])."' where id='$id'";
				mysql_db_query($DB, $Q3);
				return array($row['id'], $row["authorization"]);
			}
		}
		//end
		else if( ( passwd_encrypt($pass) == $row['pass'] || passwd_encrypt($pass) == $row2["pass"] ) && ( $row["authorization"] == 1 || $row["authorization"] == 2 || $row["authorization"] == 4 || $row["authorization"] || $row["authorization"] == 9) )
			return array($row['id'],$row["authorization"]);
		/*
		else if ( ($row2 = mysql_fetch_array($result2)) && ($row = mysql_fetch_array($result)) && ($pass == $row["pass"] || $pass == $row2["pass"] || $pass == $resultsb["ps"] || $pass == $resultsb_gra["ps"]) && ($row["authorization"] == 3 || $row["authorization"] == 9 || $row["authorization"] == 4 || $row["authorization"] == 2 || $row["authorization"] == 1) )
			return array($row['id'],$row["authorization"]);
		*/
		else {
			if ( $ver == "C" )
				$error = "使用者帳號或密碼錯誤!!";
			else
				$error = "User ID and PASSWORD INCORRECT!!";
		}
		
		if ( $ver == "C" ) {
			echo "<html>\n<head>\n";
                        echo "<meta http-equiv=\"refresh\" content=\"2;url=http://" . $SERVER_NAME . "\" />\n";                                                      echo "<title>Ecourse課程平台</title>\n";
                        echo "</head>\n<body>\n";                                                                                                                    echo "帳號或密碼錯誤，三秒後回到<a href=\"http://" . $SERVER_NAME . "\">首頁</a> ...";
                        echo "</body>\n</html>";
                        //echo "<script>alert('帳號或密碼錯誤')</script><head><meta http-equiv='refresh' content='0;url=http://$SERVER_NAME/' /> </head>";
                        //header ( "Location: http://$SERVER_NAME/" );
			//show_page ( "index_login.tpl", $error, $id, "<br><a href=\"./Learner_Profile/lost_pass.php?version=C\">密碼遺失查詢</a>" );
		}
		else {
			echo "<html>\n<head>\n";
                        echo "<meta http-equiv=\"refresh\" content=\"1;url=http://" . $SERVER_NAME . "\" />\n";
                        echo "<title>Ecourse課程平台</title>\n";
                        echo "</head>\n<body>\n";                                                                                                                    echo "帳號或密碼錯誤，三秒後回到<a href=\"http://" . $SERVER_NAME . "\">首頁</a> ...";
                        echo "</body>\n</html>";
                        //echo "<script>alert('帳號或密碼錯誤')</script><head><meta http-equiv='refresh' content='0;url=http://$SERVER_NAME/' /> </head>";
                        //header ( "Location: http://$SERVER_NAME/" );
			//show_page ( "index_login_E.tpl", $error, $id, "<br><a href=\"./Learner_Profile/lost_pass.php?version=E\">Search for Lost Password</a>" );
		}
		exit;
	}

	function Error_Handler( $msg, $cnx ) 
	{
        	echo "$msg \n";
        	//sybase_close( $cnx); 
        	pg_close( $cnx);
		exit();
	}

?>
</body>
</html>
