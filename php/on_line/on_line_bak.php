<?php
	require 'fadmin.php';
	require_once ("../Mmc/db_meeting.php");
	update_status ("觀看視訊");
	if ( !(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	try_add_column();//新增影片開關欄位 by intree

	// check guest read textbook permission.
	// ok when validated = 0 or 2.
	if($guest == "1") {
		$Q1 = "SELECT validated FROM course where a_id = '$course_id'";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) == 0 ) {
			$error = "資料庫錯誤!!";
			show_page ( "not_access.tpl", $error );
		}
		else {
			$row = mysql_fetch_array($result);
		}

		if( ($row["validated"]%2 == 1) ) {
			if ( $version == "C" )
				show_page( "not_access.tpl" ,"教材不開放參觀" );
			else
				show_page( "not_access.tpl" ,"Access Denied.");
			exit();
		}
	}


	if ( isset($submit) && ( $submit == "刪除已選取課程" || $submit == "Del The Selected" )  ) {
		del_course();
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "發佈已選取課程" || $submit == "On Air The Selected" )  ) {
		on_air_modify(1);
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "不發佈已選取課程" || $submit == "Unpublished The Selected" )  ) {
		on_air_modify(0);
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "填寫" || $submit == "Fill Out" ) ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $player == 1 ) {
			if ( $version == "C" )
				$tpl->define ( array ( body => "add_on_line.tpl" ) );
			else
				$tpl->define ( array ( body => "add_on_line_E.tpl" ) );
		}
		else {
			if ( $version == "C" )
				$tpl->define ( array ( body => "add_on_linem.tpl" ) );
			else
				$tpl->define ( array ( body => "add_on_linem_E.tpl" ) );
		}
		$tpl->assign( DATE , date("Y-m-d") );
		$tpl->assign( SUBJ , "" );
		$tpl->assign( LINK , "" );
		$tpl->assign( PLAYER , "$player" );
		$tpl->assign( RF , "" );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
	else if ( isset($submit) && ( $submit == "送出視訊" || $submit == "Send" )) {
		if ( ($style=="upload" && $file == "none") || ( $style=="filelink" && $rfile == "" ) || ($style=="link" && $url == "") || $subject == "" || $style == "" ) {
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			if ( $player == 1 ) {
				if ( $version == "C" )
					$tpl->define ( array ( body => "add_on_line.tpl" ) );
				else
					$tpl->define ( array ( body => "add_on_line_E.tpl" ) );
			}
			else {
				if ( $version == "C" )
					$tpl->define ( array ( body => "add_on_linem.tpl" ) );
				else
					$tpl->define ( array ( body => "add_on_linem_E.tpl" ) );
			}
			if ( $date == "" )
				$date = date("Y-m-d");
			$tpl->assign( DATE , "$date" );
			$subject = stripslashes( $subject );
			$url = stripslashes( $url );
			$rfile = stripslashes( $rfile );
			$tpl->assign( SUBJ , "$subject" );
			$tpl->assign( LINK , "$url" );
			$tpl->assign( PLAYER , "$player" );
			$tpl->assign( RF , "$rfile" );
			$tpl->parse( BODY, "body" );
			$tpl->FastPrint("BODY");
		}
		else {
			add_course();
			show_main ();
		}
	}
	else if ( $showaudio == "1" ) {
		show_audio( $a_id );
	}
	else{
		show_main ();
	}

//------functions---------
	//修改影片的開放與否 2007/09/18 by intree-
	function on_air_modify($publish){
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
                $Q1 = "select a_id from on_line";
                if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                        $message = "$message - 資料庫連結錯誤!!";
                }
                else if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
                        $message = "$message - 資料庫讀取錯誤!!";
                }
                else if ( mysql_num_rows( $result ) != 0 ) {
                        while ( $row = mysql_fetch_array( $result ) ) {
                                global $$row['a_id'];
                                if ( $$row['a_id'] == "NO" ) {
					$Q2 = "UPDATE on_line SET on_air=$publish where a_id='".$row['a_id']."'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
                                                $message = "$message - 資料庫更新錯誤!!";
                                        }
                                }
                        }
                }
	}
	
	function del_course () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
		$Q1 = "select a_id, file, link, rfile  from on_line";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			while ( $row = mysql_fetch_array( $result ) ) {
				global $$row['a_id'];
				if ( $$row['a_id'] == "NO" ) {

					// modified by jfish 20110217
					$Q1_2 = "select link from on_line where a_id = '".$row['a_id']."' and (link like 'http://cih.elearning.ccu.edu.tw/php/Mmc/publishedRecording.php%')";
                                        if ( !($result1_2 = mysql_db_query( $DB.$course_id, $Q1_2 ) ) ) {
                                                $message = "$message - 資料庫讀取錯誤!!";
                                        }

					if ( ($row1 = mysql_fetch_array( $result1_2 ))) {
						// 有資料才會進來，可能是一般網頁或者MMC (不是很確定)
						$pubRecordingId = preg_replace("/^http\:\/\/cih\.elearning\.ccu\.edu\.tw\/php\/Mmc\/publishedRecording\.php\?id=/","",$row1['link']);
	
						$recordingId = GetRecordingIdByPubRecordingId($pubRecordingId);
						CancelPublishMeetingInDB($recordingId);

					}

					// modified end by jfish 20110217
					$Q2 = "delete from on_line where a_id = '".$row['a_id']."'";
					if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
						$message = "$message - 資料庫刪除錯誤!!";
					}
					if ( $row['file'] != "" || $row['rfile'] != "" )
						deldir ( "../../".$course_id."/on_line/".$row['a_id'] );

				}
			}
		}
	}
	
	function add_course () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $date, $subject, $url, $file, $message, $file_name, $rfile, $style, $player,$on_air;
		if ( $style == "link" )
			$Q1 = "insert into on_line ( date, subject, link, on_air ) values ( '$date', '$subject', '$url', '$on_air' )";
		else if ( $style == "upload" )
			$Q1 = "insert into on_line ( date, subject, file, on_air ) values ( '$date', '$subject', '$file_name', $on_air )";
		else 
			$Q1 = "insert into on_line ( date, subject, rfile, on_air ) values ( '$date', '$subject', '$rfile', '$on_air' )";

		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫寫入錯誤!!";
		}
		$ip = getenv ( "SERVER_NAME" );
		if ( $ip == "" )
			$ip = $SERVER_NAME;
		if ( $style != "link" ) {
			$aid = mysql_insert_id();	
			$dir = "../../".$course_id."/on_line/".$aid;                              
			mkdir ( $dir, 0771 );
			chmod ( $dir, 0771 );
			if( ($fp=fopen( "$dir/show.html", "w")) == NULL) {
				$message = "$message - 檔案show.html寫入錯誤!!";
			}else {
				fwrite($fp,"<html>\n");
				fwrite($fp,"<head>\n");
				fwrite($fp,"<title>遠距教學</title>\n");
				fwrite($fp,"</head>\n");
				fwrite($fp,"<frameset NAME=\"main\" BORDER=\"0\" frameborder=\"0\" cols=\"40%,60%\">\n");
				fwrite($fp,"<frame SRC=\"show_main.html\">\n");
				fwrite($fp,"<frameset BORDER=\"0\" frameborder=\"0\" rows=\"9%,91%\">\n");
				fwrite($fp,"<frame SRC=\"empty.html\" scrolling=\"no\">\n");
				fwrite($fp,"<frame SRC=\"empty.html\" NAME=\"show\">\n");
				fwrite($fp,"</frameset>\n");
				fwrite($fp,"<noframes>\n");
				fwrite($fp,"<body>\n");
				fwrite($fp,"</body>\n");
				fwrite($fp,"</noframes>\n");
				fwrite($fp,"</frameset>\n");
				fwrite($fp,"</html>\n");
				fclose($fp);
			}
			
			if ( $player == "1" ) {
				if( ($fp2=fopen( "$dir/show_main.html", "w")) == NULL) {
					$message = "$message - 檔案show_main.html寫入錯誤!!";
				}else {
					fwrite($fp2,"<html><head><title>教學系統--隨選視訊</title></head><body bgcolor=#ffffff background=/images/img/bg.gif>\n");
					fwrite($fp2,"<p align=\"center\"><! RealPublisher\n");
					fwrite($fp2,"-- Comment Text Created By RealPublisher Web Page Wizard - 10/24/98\n");
					fwrite($fp2,"-- Caution: Do not make changes to this comment section.  Any local file \n");
					fwrite($fp2,"references that appear here are automatically updated when uploaded to a \n");
					fwrite($fp2,"remote web server. Alterations to this section or any file references \n");
					fwrite($fp2,"listed below or contained in the associated RAM or RPM metafiles may cause\n");
					fwrite($fp2,"errors when publishing your web page to a remote server. These values should \n");
					fwrite($fp2,"not be altered.\n");
					fwrite($fp2,"-- pagelayout=\"embedded\"\n");
					fwrite($fp2,"/!> </p>\n");
					fwrite($fp2,"<h3 align=\"center\">教學系統--隨選視訊</h3>\n");
					fwrite($fp2,"<p align=\"center\">\n");
					fwrite($fp2,"<object ID=\"video1\" CLASSID=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\" HEIGHT=\"144\" WIDTH=\"176\">\n");
					fwrite($fp2,"<param name=\"controls\" value=\"ImageWindow\">\n");
					fwrite($fp2,"<param name=\"console\" value=\"Clip1\">\n");
					fwrite($fp2,"<param name=\"autostart\" value=\"true\">\n");
					fwrite($fp2,"<param name=\"src\" value=\"show.rpm\"><embed SRC=\"show.rpm\" type=\"audio/x-pn-realaudio-plugin\" CONSOLE=\"Clip1\" CONTROLS=\"ImageWindow\" HEIGHT=\"144\" WIDTH=\"176\" AUTOSTART=\"true\">\n");
					fwrite($fp2,"</object>\n</p>\n");
					fwrite($fp2,"<p align=\"center\">\n");
					fwrite($fp2,"<object ID=\"video1\" CLASSID=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\" HEIGHT=\"80\"  WIDTH=\"300\">\n");
			 		fwrite($fp2,"<param name=\"controls\" value=\"All\">\n");
					fwrite($fp2,"<param name=\"console\" value=\"Clip1\"><embed type=\"audio/x-pn-realaudio-plugin\" CONSOLE=\"Clip1\" CONTROLS=\"All\" HEIGHT=\"80\" WIDTH=\"300\" AUTOSTART=\"true\">\n");
					fwrite($fp2,"</object>\n</p>\n");
					fwrite($fp2,"</body>\n</html>\n");
					fclose($fp2);
				}

				if( ( $fp=fopen( "$dir/show.rpm", "w") ) == NULL ) {
					$message = "$message - 檔案show.rpm寫入錯誤!!";
				}
				else {
					
					if ( $style == "upload" )
						//fwrite( $fp,"pnm://$ip:7070/$course_id/on_line/".$aid."/$file_name");
						fwrite( $fp,"http://$ip/$course_id/on_line/".$aid."/$file_name");
					else
						fwrite( $fp,"$rfile");
					fclose($fp);
				}
			}
			else {
				if( ($fp2=fopen( "$dir/show_main.html", "w")) == NULL) {
					$message = "$message - 檔案show_main.html寫入錯誤!!";
				}else {
					fwrite($fp2,"<html><head><title>教學系統--隨選視訊</title>");
                    fwrite($fp2,"</head><body bgcolor=#ffffff background=/images/img/bg.gif>\n");
					fwrite($fp2,"<p align=\"center\"><! RealPublisher\n");
					fwrite($fp2,"-- Comment Text Created By RealPublisher Web Page Wizard - 10/24/98\n");
					fwrite($fp2,"-- Caution: Do not make changes to this comment section.  Any local file \n");
					fwrite($fp2,"references that appear here are automatically updated when uploaded to a \n");
					fwrite($fp2,"remote web server. Alterations to this section or any file references \n");
					fwrite($fp2,"listed below or contained in the associated RAM or RPM metafiles may cause\n");
					fwrite($fp2,"errors when publishing your web page to a remote server. These values should \n");
					fwrite($fp2,"not be altered.\n");
					fwrite($fp2,"-- pagelayout=\"embedded\"\n");
					fwrite($fp2,"/!> </p>\n");
					fwrite($fp2,"<h3 align=\"center\">教學系統--隨選視訊</h3>\n");
					fwrite($fp2,"<p align=\"center\">\n");
					fwrite($fp2,"<p align=\"center\">\n");
					fwrite($fp2,"<object ID=\"MediaPlayer\" name=\"msplayer\" classid=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" codebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701\" height=\"280\" style=\"LEFT: 0px; TOP: 0px\" type=\"application/x-oleobject\" width=\"240\" standby=\"Loading Microsoft Windows Media Player components...\" viewastext>\n");
					fwrite($fp2,"<param name=\"AudioStream\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"AutoSize\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"AutoStart\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"AnimationAtStart\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"AllowScan\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"AllowChangeDisplaySize\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"AutoRewind\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"Balance\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"BaseURL\" value>\n");
					fwrite($fp2,"<param name=\"BufferingTime\" value=\"5\">\n");
					fwrite($fp2,"<param name=\"CaptioningID\" value>\n");
					fwrite($fp2,"<param name=\"ClickToPlay\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"CursorType\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"CurrentPosition\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"CurrentMarker\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"DefaultFrame\" value>\n");
					fwrite($fp2,"<param name=\"DisplayBackColor\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"DisplayForeColor\" value=\"16777215\">\n");
					fwrite($fp2,"<param name=\"DisplayMode\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"DisplaySize\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"Enabled\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"EnableContextMenu\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"EnablePositionControls\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"EnableFullScreenControls\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"EnableTracker\" value=\"-1\">\n");
					if ( $style == "upload" )
						fwrite( $fp2,"<param name=\"Filename\" value=\"http://$ip/$course_id/on_line/".$aid."/$file_name\">\n");
					else
						fwrite($fp2,"<param name=\"Filename\" value=\"$rfile\">\n");
					fwrite($fp2,"<param name=\"InvokeURLs\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"Language\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"Mute\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"PlayCount\" value=\"1\">\n");
					fwrite($fp2,"<param name=\"PreviewMode\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"Rate\" value=\"1\">\n");
					fwrite($fp2,"<param name=\"SAMILang\" value>\n");
					fwrite($fp2,"<param name=\"SAMIStyle\" value>\n");
					fwrite($fp2,"<param name=\"SAMIFileName\" value>\n");
					fwrite($fp2,"<param name=\"SelectionStart\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"SelectionEnd\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"SendOpenStateChangeEvents\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"SendWarningEvents\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"SendErrorEvents\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"SendKeyboardEvents\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"SendMouseClickEvents\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"SendMouseMoveEvents\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"SendPlayStateChangeEvents\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"ShowCaptioning\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"ShowControls\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"ShowAudioControls\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"ShowDisplay\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"ShowGotoBar\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"ShowPositionControls\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"ShowStatusBar\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"ShowTracker\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"TransparentAtStart\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"VideoBorderWidth\" value=\"1\">\n");
					fwrite($fp2,"<param name=\"VideoBorderColor\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"VideoBorder3D\" value=\"-1\">\n");
					fwrite($fp2,"<param name=\"Volume\" value=\"0\">\n");
					fwrite($fp2,"<param name=\"WindowlessVideo\" value=\"0\">\n");
				//	fwrite($fp2,"<embed TYPE=\"application/x-mplayer2\" pluginspage=\"http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/\" src=\"http://www.ttv.com.tw/videoweb/windowsmedia/ttvasx-3.asp?ID=20010803v00 \" Name=\"NSPlay\" showcontrols=\"-1\" showdisplay=\"0\" showstatusbar=\"-1\" autosize=\"0\" width=\"240\" height=\"280\"></embed>\n");
					fwrite($fp2,"</object>\n");
					fwrite($fp2,"</body>\n</html>\n");
					fclose($fp2);
				}
//					if( ( $fp=fopen( "$dir/show.asx", "w") ) == NULL ) {
//						$message = "$message - 檔案show.asx寫入錯誤!!";
//					}
//					else {
//						fwrite( $fp,"<ASX VERSION=\"3.0\" previewmode = \"no\">");
//						fwrite( $fp,"<Entry>");
//						fwrite( $fp,"<StartTime Value = \"0:0:0\"/>");
//						fwrite( $fp,"<Duration Value = \"1:0:0\"/>");
//						$ip = getenv ( "SERVER_NAME" );
//						if ( $file != "none" )
//							fwrite( $fp,"<Ref href =\"mms://$ip:7070/$course_id/on_line/".$aid."/$file_name\"/>");
//						else
//							fwrite( $fp,"<Ref href =\"$rfile\"/>");
//						fwrite( $fp,"</Entry>");
//						fwrite( $fp,"</ASX>");
//					}
			}

			if( ($fp3=fopen( "$dir/empty.html", "w")) == NULL) {
				echo ("檔案empty.html寫入錯誤!!");
			}else {
				fwrite($fp3,"<html>\n<head>\n<title>教學系統--隨選視訊</title>\n</head>\n");
				fwrite($fp3,"<body bgcolor=#ffffff background=/images/img/bg.gif>\n");
				fwrite($fp3,"</body>\n</html>\n");
				fclose($fp3);
			}

			if ( $style == "upload" ) {
				if( is_uploaded_file($file) ) {
					move_uploaded_file($file, "$dir/$file_name");
				}
				else {	 
					echo ("檔案上傳錯誤!");
					return;
				}
			}
		}
	}

	function show_main () {
		global $version, $check, $skinnum;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->assign( SKINNUM , $skinnum );
		if ( $version == "C" )
			$tpl->define ( array ( body => "on_line_list.tpl" ) );
		else
			$tpl->define ( array ( body => "on_line_list_E.tpl" ) );

		$tpl->define_dynamic ( "course_list" , "body" );
		
		$color = "#4d6eb2";
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			if ( $check == 1 || $check == 3 )
				$tpl->assign( CHECK , "<font color=#FFFFFF><b>編號</b></font>" );
			else
				$tpl->assign( CHECK , "<font color=#FFFFFF><b>選取</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>課程日期</b></font>" );
			$tpl->assign( SUBJECT , "<font color=#FFFFFF><b>課程摘要</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>影片</b></font>" );
		}
		else {
			if ( $check == 1 || $check == 3 )
				$tpl->assign( CHECK , "<font color=#FFFFFF><b>No.</b></font>" );
			else
				$tpl->assign( CHECK , "<font color=#FFFFFF><b>Check</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>Date</b></font>" );
			$tpl->assign( SUBJECT , "<font color=#FFFFFF><b>Subject</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>Playback</b></font>" );
		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
                //changed 2 line by jfish marked include 20110217
		//changed 1 line by rja
                //include ('../my_on_line_video_syn.php');

		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $message, $PHPSESSID;
		$Q1 = "select * from on_line Order By subject,date DESC";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			if ( $version == "C" )
				$message = "<font size=5 color=#0000ff>目前已上線之視訊檔案列表</font>";
			else
				$message = "<font size=5 color=#0000ff>The Video Material List</font>";
			$i = 0;
			$color = "#BFCEBD";
			while ( $row = mysql_fetch_array( $result ) ) {
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else
					$color = "#BFCEBD";
				$tpl->assign( COLOR , $color );
				$i ++;
				if ( $check == 1 || $check == 3 )
					$tpl->assign( CHECK , "$i" );
				else
					$tpl->assign( CHECK , "<input type=checkbox name=". $row['a_id'] ." value=NO>" );
				$tpl->assign( DATE , $row['date'] );
				$tpl->assign( SUBJECT , $row['subject'] );
				if ( $version == "C" ){
					if($row['on_air']==1)
						$video_str="<a href=\"#\" onClick=\"window.open('./on_line.php?a_id=".$row['a_id']."&showaudio=1&PHPSESSID=$PHPSESSID','','resizable=1,scrollbars=1,width=800,height=600');\" >播放</a>" ;
					else
						$video_str='未公佈影片';
					
				}
				else{
					if($row['on_air']==1)
						$video_str="<a href=\"#\" onClick=\"window.open('./on_line.php?a_id=".$row['a_id']."&showaudio=1&PHPSESSID=$PHPSESSID','','resizable=1,scrollbars=1,width=800,height=600');\" >Video</a>" ;
					else
						$video_str='UNPUBLISHED';
				}
				$tpl->assign( LINK , $video_str);
				$tpl->parse ( COURSE_LIST, ".course_list" );
			}
		}
		else if ( $version == "C" )
			$message = "<font size=4 color=#00aa00><b>目前本門課尚未提供任何隨選視訊檔案</b></font><br><br>";
		else
			$message = "<font size=4 color=#00aa00><b>There is no realplayer file for this course</b></font><br><br>";

		$tpl->assign( MES , $message );
		if ( $check == 1 || $check == 3 ) {
			$tpl->assign( ENDLINE , "</form></center></body></html>" );
			$tpl->parse( BODY, "body" );
			$tpl->FastPrint("BODY");
		}else {
			$tpl->assign( ENDLINE , "" );
			if ( $version == "C" )
				$tpl->define ( array ( tial => "on_line.tpl" ) );
			else
				$tpl->define ( array ( tial => "on_line_E.tpl" ) );
			$tpl->parse( BODY, "body" );
			$tpl->FastPrint("BODY");
			$tpl->parse( TIAL, "tial" );
			$tpl->FastPrint("TIAL");
		}
	}
	
	function show_audio ( $a_id ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $show_audio, $course_id;
		$Q1 = "select * from on_line where a_id = '$a_id'";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
		}
		if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
		}
		else if ( mysql_num_rows( $result1 ) != 0 ) {
			$row1 = mysql_fetch_array( $result1 );
			if ( $row1['link'] != "" )
				header( "Location: ".$row1['link']);
			else if ( $row1['rfile'] != "" && $row1['rfile'] != NULL) {
				$ext = strrchr( $row1['rfile'], '.' );
				if ( $ext == ".rm" || $ext == ".rpm" ) {
					echo("<html><head><title>教學系統--隨選視訊</title></head><body bgcolor=#ffffff background=/images/img/bg.gif>\n");
					echo("<p align=\"center\"><! RealPublisher\n");
					echo("-- Comment Text Created By RealPublisher Web Page Wizard - 10/24/98\n");
					echo("-- Caution: Do not make changes to this comment section.  Any local file \n");
					echo("references that appear here are automatically updated when uploaded to a \n");
					echo("remote web server. Alterations to this section or any file references \n");
					echo("listed below or contained in the associated RAM or RPM metafiles may cause\n");
					echo("errors when publishing your web page to a remote server. These values should \n");
					echo("not be altered.\n");
					echo("-- pagelayout=\"embedded\"\n");
					echo("/!> </p>\n");
					echo("<h3 align=\"center\">教學系統--隨選視訊</h3>\n");
					echo("<p align=\"center\">\n");
					echo("<object ID=\"video1\" CLASSID=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\" HEIGHT=\"288\" WIDTH=\"352\">\n");
					echo("<param name=\"controls\" value=\"ImageWindow\">\n");
					echo("<param name=\"console\" value=\"Clip1\">\n");
					echo("<param name=\"autostart\" value=\"true\">\n");
					echo("<param name=\"src\" value=\"".$row1["rfile"]."\"><embed SRC=\"".$row1["rfile"]."\" type=\"audio/x-pn-realaudio-plugin\" CONSOLE=\"Clip1\" CONTROLS=\"ImageWindow\" HEIGHT=\"144\" WIDTH=\"176\" AUTOSTART=\"true\">\n");
					echo("</object>\n</p>\n");
					echo("<p align=\"center\">\n");
					echo("<object ID=\"video1\" CLASSID=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\" HEIGHT=\"80\"  WIDTH=\"300\">\n");
			 		echo("<param name=\"controls\" value=\"All\">\n");
					echo("<param name=\"console\" value=\"Clip1\"><embed type=\"audio/x-pn-realaudio-plugin\" CONSOLE=\"Clip1\" CONTROLS=\"All\" HEIGHT=\"80\" WIDTH=\"300\" AUTOSTART=\"true\">\n");
					echo("</object>\n</p>\n");
					echo("</body>\n</html>\n");
				}
				else {
					echo("<html><head><title>教學系統--隨選視訊</title>");
					echo("<SCRIPT SRC=\"./full_screen.js\"></SCRIPT>");
                    echo("</head><body bgcolor=#ffffff background=/images/img/bg.gif>\n");
                    echo("<p align=\"center\"><! RealPublisher\n");
					echo("-- Comment Text Created By RealPublisher Web Page Wizard - 10/24/98\n");
					echo("-- Caution: Do not make changes to this comment section.  Any local file \n");
					echo("references that appear here are automatically updated when uploaded to a \n");
					echo("remote web server. Alterations to this section or any file references \n");
					echo("listed below or contained in the associated RAM or RPM metafiles may cause\n");
					echo("errors when publishing your web page to a remote server. These values should \n");
					echo("not be altered.\n");
					echo("-- pagelayout=\"embedded\"\n");
					echo("/!> </p>\n");
					echo("<h3 align=\"center\">教學系統--隨選視訊</h3>\n");
					echo("<p align=\"center\">\n");
					echo("<p align=\"center\">\n");
					echo("<object ID=\"MediaPlayer\" name=\"msplayer\" classid=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" codebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701\" height=\"280\" style=\"LEFT: 0px; TOP: 0px\" type=\"application/x-oleobject\" width=\"240\" standby=\"Loading Microsoft Windows Media Player components...\" viewastext>\n");
					echo("<param name=\"AudioStream\" value=\"-1\">\n");
					echo("<param name=\"AutoSize\" value=\"0\">\n");
					echo("<param name=\"AutoStart\" value=\"-1\">\n");
					echo("<param name=\"AnimationAtStart\" value=\"-1\">\n");
					echo("<param name=\"AllowScan\" value=\"-1\">\n");
					echo("<param name=\"AllowChangeDisplaySize\" value=\"-1\">\n");
					echo("<param name=\"AutoRewind\" value=\"0\">\n");
					echo("<param name=\"Balance\" value=\"0\">\n");
					echo("<param name=\"BaseURL\" value>\n");
					echo("<param name=\"BufferingTime\" value=\"5\">\n");
					echo("<param name=\"CaptioningID\" value>\n");
					echo("<param name=\"ClickToPlay\" value=\"-1\">\n");
					echo("<param name=\"CursorType\" value=\"0\">\n");
					echo("<param name=\"CurrentPosition\" value=\"-1\">\n");
					echo("<param name=\"CurrentMarker\" value=\"0\">\n");
					echo("<param name=\"DefaultFrame\" value>\n");
					echo("<param name=\"DisplayBackColor\" value=\"0\">\n");
					echo("<param name=\"DisplayForeColor\" value=\"16777215\">\n");
					echo("<param name=\"DisplayMode\" value=\"0\">\n");
					echo("<param name=\"DisplaySize\" value=\"0\">\n");
					echo("<param name=\"Enabled\" value=\"-1\">\n");
					echo("<param name=\"EnableContextMenu\" value=\"-1\">\n");
					echo("<param name=\"EnablePositionControls\" value=\"-1\">\n");
					echo("<param name=\"EnableFullScreenControls\" value=\"-1\">\n");
					echo("<param name=\"EnableTracker\" value=\"-1\">\n");
					echo("<param name=\"Filename\" value=\"".$row1["rfile"]."\">\n");
					echo("<param name=\"InvokeURLs\" value=\"-1\">\n");
					echo("<param name=\"Language\" value=\"-1\">\n");
					echo("<param name=\"Mute\" value=\"0\">\n");
					echo("<param name=\"PlayCount\" value=\"1\">\n");
					echo("<param name=\"PreviewMode\" value=\"0\">\n");
					echo("<param name=\"Rate\" value=\"1\">\n");
					echo("<param name=\"SAMILang\" value>\n");
					echo("<param name=\"SAMIStyle\" value>\n");
					echo("<param name=\"SAMIFileName\" value>\n");
					echo("<param name=\"SelectionStart\" value=\"-1\">\n");
					echo("<param name=\"SelectionEnd\" value=\"-1\">\n");
					echo("<param name=\"SendOpenStateChangeEvents\" value=\"-1\">\n");
					echo("<param name=\"SendWarningEvents\" value=\"-1\">\n");
					echo("<param name=\"SendErrorEvents\" value=\"-1\">\n");
					echo("<param name=\"SendKeyboardEvents\" value=\"0\">\n");
					echo("<param name=\"SendMouseClickEvents\" value=\"0\">\n");
					echo("<param name=\"SendMouseMoveEvents\" value=\"0\">\n");
					echo("<param name=\"SendPlayStateChangeEvents\" value=\"-1\">\n");
					echo("<param name=\"ShowCaptioning\" value=\"0\">\n");
					echo("<param name=\"ShowControls\" value=\"-1\">\n");
					echo("<param name=\"ShowAudioControls\" value=\"-1\">\n");
					echo("<param name=\"ShowDisplay\" value=\"0\">\n");
					echo("<param name=\"ShowGotoBar\" value=\"-1\">\n");
					echo("<param name=\"ShowPositionControls\" value=\"-1\">\n");
					echo("<param name=\"ShowStatusBar\" value=\"-1\">\n");
					echo("<param name=\"ShowTracker\" value=\"-1\">\n");
					echo("<param name=\"TransparentAtStart\" value=\"0\">\n");
					echo("<param name=\"VideoBorderWidth\" value=\"1\">\n");
					echo("<param name=\"VideoBorderColor\" value=\"0\">\n");
					echo("<param name=\"VideoBorder3D\" value=\"-1\">\n");
					echo("<param name=\"Volume\" value=\"0\">\n");
					echo("<param name=\"WindowlessVideo\" value=\"0\">\n");
					echo("</object>\n");
					echo("<div align=\"center\"><font color=\"#FFFFFF\" size=\"2\"><a href=\"#\" onClick=\"fullscreen64();return false;\">|全螢幕|</a></font>");
					echo("<font color=\"#FFFFFF\" size=\"2\"><a href=\"#\" OnClick=\"DoubleSize();return false;\">|放大兩倍|</a>");
					echo("<font color=\"#FFFFFF\" size=\"2\"><a href=\"#\" OnClick=\"DefaultSize();return false;\">|原來大小|</a></div>");
					echo("</body>\n</html>\n");
				}
				//header( "Location: ".$row1["rfile"] );
				//exit;
			}
	//		else if ( $row1['rfile'] != "" )
	//			header( "Location: /$course_id/on_line/".$row1['a_id']."/show.html");
	//			header( "Location: ".$row1['rfile']);
			else
				header( "Location: /$course_id/on_line/".$row1['a_id']."/show.html");
		}
	} 

//新增資料欄來判斷是否開放影片 2007/09/18 byintree
	function try_add_column(){
		global $DB,$DB_SERVER,$DB_LOGIN,$DB_PASSWORD,$course_id;
		 if( !($link=mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ){
                        $error = "資料庫連結錯誤!!";
                        show_page ( "not_access.tpl", $error );
                }
                $Q2="ALTER TABLE on_line ADD on_air TINYINT DEFAULT '1' NOT NULL";

                if(! mysql_db_query( $DB.$course_id, $Q2 ) ){
                        $error = mysql_error();

                        if($error=="Duplicate column name 'on_air'") ;

                        else{
                                 show_page ( "not_access.tpl", $error );
                        }
                }
	}
?>
