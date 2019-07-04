<?
	require 'fadmin.php';

	if(session_check_teach($PHPSESSID) != 2) {
		if ( $version == "C" )
			show_page("not_access.tpl", "你沒有權限執行此功能");
		else
			show_page("not_access.tpl", "You have no permission to perform this function.");
		exit();
	}
//	$location="../../$course_id/textbook";
//	exec( "cd $location;tar -zcvf ../textbook$course_id.tar.gz *");
	if ( $action == "import_file" ) {
		if ( empty($importfile) || $importfile == "none" ) {
			show_page_d ();
		}
		else {
			import ();
		}
	}
	else if ( $action == "reset" ) {
		show_page_d ( del() );
	}else {
		show_page_d ();
	} 

	function show_page_d ( $message = "" ) {
		global $version, $course_id;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "import.tpl" ) );
		if ( $version == "C" ) {
			$tpl->assign( IMG , "img" );
			$tpl->assign( IMPROT_DATA, "請選擇已製作完成的SCORM教材<br>\n(格式為zip,tar,tar.gz,gz)" );
			$tpl->assign( IMPORT_DISC, "上傳匯入檔" );
			$tpl->assign( RESET_DATA, "重置資料庫" );
		}
		else {
			$tpl->assign( IMG , "img_E" );
			$tpl->assign( IMPROT_DATA, "Please Choise the Completed Scorm Textbook<br>\n(Type:zip,tar,tar.gz,gz)" );
			$tpl->assign( IMPORT_DISC, "Upload Import File" );
			$tpl->assign( RESET_DATA, "Reset Database" );
		}
		$location="../../$course_id/scorm/scorm.zip";
		$location1="../../$course_id/scorm/scorm.tar";
		$location2="../../$course_id/scorm/scorm.tar.gz";
		$location3="../../$course_id/scorm/scorm.gz";
		if ( is_file ( "$location1" ) )
			$tpl->assign( LINK , "<a href=\"$location1\" >教材匯出</a>" );
		else if ( is_file ( "$location2" ) )
			$tpl->assign( LINK , "<a href=\"$location2\" >教材匯出</a>" );
		else if ( is_file ( "$location3" ) )
			$tpl->assign( LINK , "<a href=\"$location3\" >教材匯出</a>" );
		else if ( is_file ( "$location" ) )
			$tpl->assign( LINK , "<a href=\"$location\" >教材匯出</a>" );
		else
			$tpl->assign( LINK , "" );
		$tpl->assign( ACT1 , "import.php" );
		$tpl->assign( MSG , $message );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
	
	function import () {
		global $version, $user_id, $course_id, $importfile_name, $importfile;
		$ext = strrchr( $importfile_name, '.' );
		if ( $ext == ".zip" || $ext == ".tar" || $ext == ".gz" ) {
			$location="../../$course_id/scorm";
			if ( fileupload ( $importfile, $location, $importfile_name ) ) {
				if ( $ext == ".zip" ) {
					exec( "unzip -x $location/$importfile_name -d $location/;mv $location/$importfile_name $location/scorm.zip");
				}
				else if ( $ext == ".tar" ) {
					exec( "tar -xvf $location/$importfile_name -C $location/;mv $location/$importfile_name  $location/scorm.tar");
				}
				else if ( $ext == ".gz" ){
					$pre = $importfile_name;
					while ( $ext2 = strchr( $pre, '.' ) ) {
						$i ++;
						if ( !strchr( substr($ext2, 1, strlen($ext2)), '.' ) ) {
							if ( $pre == "tar.gz" ) {
								exec( "tar -zxvf $location/$importfile_name -C $location/;mv $location/$importfile_name  $location/scorm.tar.gz");
								break;
							}
							else {
								exec( "gzip -d $location/$importfile_name -C $location/;mv $location/$importfile_name  $location/scorm.gz");
								break;
							}
						}
						else {
							$pre = substr($ext2, 1, strlen($ext2));
						}
					}
				}
				while ( !is_file( "$location/imsmanifest.xml" ) ){}
				$sequence = new Java('Sequence');
				if ( ($error = $sequence->begin("$course_id", "$location/imsmanifest.xml", 0)) == "" ) {
					if ( $version == "C" )
						$message = " 匯入成功 ";
					else
						$message = "Import Completed";
				}
				else {
					del();
					if ( $version == "C" )
						$message = " 匯入失敗$error";
					else
						$message = "Import Completed";
				}
				
			}
			else {
				if ( $version == "C" )
					$message = "上傳失敗";
				else
					$message = "Upload Faile";
			}
		}
		else {
			if ( $version == "C" )
				$message = "檔案格式不支援";
			else
				$message = "Type Not Support";
		}
		
		show_page_d ( $message );
	}
	
	function del() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $version;
		if ( $course_id != "") {
			deldir( "../../$course_id/scorm" );
			mkdir ( "../../$course_id/scorm", 0751 );
			chmod ( "../../$course_id/scorm", 0755 );
		}
		$Q1 = "select * from sco_register order by sequence";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DB.$course_id, $Q1  )) ) {
			$error = "資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) == 0 ) {
			if ( $version == "C" )
				$error = "重置完成";
			else
				$error = "Reset Completed";
			return $error;
		}
		else {
			while( $row = mysql_fetch_array($result) ) {
				$scoid = $row['a_id'];
				$q1 = "drop table sco_".$scoid."_core";
				$q2 = "drop table sco_".$scoid."_interaction";
				$q3 = "drop table sco_".$scoid."_objectives";
				
				mysql_db_query( $DB.$course_id, $q1 );
				mysql_db_query( $DB.$course_id, $q2 );
				mysql_db_query( $DB.$course_id, $q3 );
			}
			$Q2 = "delete from sco_register";
			$Q3 = "delete from lesson";
			mysql_db_query( $DB.$course_id, $Q2 );
			mysql_db_query( $DB.$course_id, $Q3 );
			if ( $version == "C" )
				$error = "重置完成";
			else
				$error = "Reset Completed";
		}
		return $error;
	}
?>