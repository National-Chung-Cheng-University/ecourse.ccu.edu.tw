<?
	require 'fadmin.php';

	if(session_check_teach($PHPSESSID) != 2) {
		if ( $version == "C" )
			show_page("not_access.tpl", "�A�S���v�����榹�\��");
		else
			show_page("not_access.tpl", "You have no permission to perform this function.");
		exit();
	}
	$location="../../$course_id/textbook";
	/*
	�j�v�S�s�W
	*/
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");
	
	$Q1 = "select * from chap_title";
	$result = mysql_db_query($DB.$course_id,$Q1) or die("��Ʈw�d�߿��~,".$Q1);
	dumpChap_TitleTable($result,$location);

	exec( "cd $location;tar -zcvf ../textbook$course_id.tar.gz *");	

	if ( $action == "import_file" ) {
		if ( empty($importfile) || $importfile == "none" ) {
			show_page_d ();
		}
		else {
			import2();
		}
	}
	else if ( $action == "reset" ) {
		show_page_d ( del() );
	}else {			
		show_page_d ();
	} 

	function show_page_d ( $message = "" ) {
		global $version, $course_id;
		$location="../../$course_id/textbook";
		if(file_exists($location."/TextBookDumped.sql")){
			unlink($location."/TextBookDumped.sql");
		}
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "import.tpl" ) );
		if ( $version == "C" ) {
			$tpl->assign( IMG , "img" );
			$tpl->assign( IMPROT_DATA, "�п�ܤw�s�@�������Ч�<br>\n(�榡��zip,tar,tar.gz,gz)" );
			$tpl->assign( IMPORT_DISC, "�W�ǶפJ��" );
			$tpl->assign( RESET_DATA, "���m��Ʈw" );
		}
		else {
			$tpl->assign( IMG , "img_E" );
			$tpl->assign( IMPROT_DATA, "Please Choise the Completed Textbook<br>\n(Type:zip,tar,tar.gz,gz)" );
			$tpl->assign( IMPORT_DISC, "Upload Import File" );
			$tpl->assign( RESET_DATA, "Reset Database" );
		}
		$location="../../$course_id/textbook$course_id.tar.gz";
		if ( is_file ( "$location" ) )
			$tpl->assign( LINK , "<a href=\"$location\" >�Ч��ץX</a>" );
		else
			$tpl->assign( LINK , "" );
		$tpl->assign( ACT1 , "import2.php" );
		$tpl->assign( MSG , $message );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
	
	function import2 () {
		global $version, $user_id, $course_id, $importfile_name, $importfile;
		$ext = strrchr( $importfile_name, '.' );
		if ( $ext == ".zip" || $ext == ".tar" || $ext == ".gz" ) {
			$location="../../$course_id/textbook";
			if ( fileupload ( $importfile, $location, $importfile_name ) ) {
				if ( $ext == ".zip" ) {
					exec( "unzip -x $location/$importfile_name -d $location/;rm -f $location/$importfile_name");
				}
				else if ( $ext == ".tar" ) {
					exec( "tar -xvf $location/$importfile_name -C $location/;rm -f $location/$importfile_name");
				}
				else if ( $ext == ".gz" ){
					$pre = $importfile_name;
					while ( $ext2 = strchr( $pre, '.' ) ) {
						$i ++;
						if ( !strchr( substr($ext2, 1, strlen($ext2)), '.' ) ) {
							if ( $pre == "tar.gz" ) {
								exec( "tar -zxvf $location/$importfile_name -C $location/;rm -f $location/$importfile_name");
								break;
							}
							else {
								exec( "gzip -d $location/$importfile_name -C $location/;rm -f $location/$importfile_name");
								break;
							}
						}
						else {
							$pre = substr($ext2, 1, strlen($ext2));
						}
					}
				}
				
				if ( ($error = parse()) == "" ) {
					if ( $version == "C" )
						$message = " �פJ���\ ";
					else
						$message = "Import Completed";
				}
				else {
					//del();
					if ( $version == "C" )
						$message = " �פJ����$error";
					else
						$message = "Import Completed";
				}
				
			}
			else {
				if ( $version == "C" )
					$message = "�W�ǥ���";
				else
					$message = "Upload Faile";
			}
		}
		else {
			if ( $version == "C" )
				$message = "�ɮ׮榡���䴩";
			else
				$message = "Type Not Support";
		}
		importCourse($location);
		show_page_d ( $message );

	}
	
	function del() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $version;
		if ( $course_id != "") {
			deldir( "../../$course_id/textbook" );
			mkdir ( "../../$course_id/textbook", 0751 );
			chmod ( "../../$course_id/textbook", 0755 );
		}
		$Q1 = "delete from chap_title";
		$Q2 = "delete from log where event_id = '3'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
		}
		mysql_db_query( $DB.$course_id, $Q1 );
		mysql_db_query( $DB.$course_id, $Q2 );
		if ( $version == "C" )
			$error = "���m����";
		else
			$error = "Reset Completed";
		return $error;
	}
	
	function parse() {
		global $course_id;
		$location = "../../$course_id/textbook";
		return handle_dir ( $location, 1, "textbook");
	}
	
	function handle_dir ( $path, $level, $parent ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			return "��Ʈw�s�����~!!";
		}
		if ( $level < 2 ) {
			$handle = dir($path);
			while ( ($file = $handle->read()) ) {
				if( is_dir($path."/".$file) && (strcmp($file,"..") != 0) && (strcmp($file,".") != 0) ) {
					if ( is_file ( $path."/".$file."/index.html" ) ) {
						if ( !($chap_title = get_title ( $path."/".$file."/index.html" )) ) {
							$chap_title = $file;
						}
							
					}
					else if ( is_file ( $path."/".$file."/index.htm" ) ) {
						if ( !($chap_title = get_title ( $path."/".$file."/index.htm" )) ) {
							$chap_title = $file;
						}
					}
					else {
						continue;
					}
					$Q1 = "insert into chap_title ( chap_num, chap_title, sect_num, sect_title ) values ( '$file', '$chap_title', '', '' )";
					if ( !mysql_db_query( $DB.$course_id, $Q1 ) ) {
						return "��ƮwŪ�����~1!!";
					}
					if ( ($error = handle_dir($path."/".$file, $level+1, $file )) != "" ) {
						return $error;
					}
				}
			}
		}
		else {
			$handle = dir($path);
			while ( ($file = $handle->read()) ) {
				if( is_dir($path."/".$file) && (strcmp($file,"..") != 0) && (strcmp($file,".") != 0) ) {
					if ( is_file ( $path."/".$file."/index.html" ) ) {
						if ( !($sec_title = get_title ( $path."/".$file."/index.html" )) ) {
							$sec_title = $file;
						}
							
					}
					else if ( is_file ( $path."/".$file."/index.htm" ) ) {
						if ( !($sec_title = get_title ( $path."/".$file."/index.htm" )) ) {
							$sec_title = $file;
						}
					}
					else {
						continue;
					}
					$Q1 = "insert into chap_title ( chap_num, chap_title, sect_num, sect_title ) values ( '$parent', '', '$file', '$sec_title' )";
					if ( !mysql_db_query( $DB.$course_id, $Q1 ) ) {
						return "��ƮwŪ�����~1!!";
					}
				}
			}
			
		}
		return;
	}
	
	function get_title ( $full_path ) {
		$fp=fopen( $full_path, "r");
		$tmp = fread($fp, filesize($full_path) );				
		fclose($fp);
		if( ($buf = stristr($tmp,"<title>")) == NULL ){
			return false;
		}
		else {
			$buf = substr($buf, 7);
		
			// ��X </title> ���_�l��m.
			$pos = strpos($buf, "<");
		
			// ��<title> </title>�����F��Ǧ^�h.
			return substr($buf, 0, $pos);
		}
	}
/*
written by �j�v�S
*/
function dumpChap_TitleTable($result,$location){
	$file = fopen($location."/TextBookDumped.sql","w");
	$Q3 = "insert into chap_title values";
	fwrite($file,$Q1);
	fwrite($file,$Q2);
	fwrite($file,$Q3);

	$num = mysql_num_rows($result);
	for($i=0;$i<$num;$i++){
	 $row = mysql_fetch_array($result);
	

	 if($i < $num-1){
	    fwrite($file,"(".$row['a_id'].",".$row['chap_num'].",'". $row['chap_title'] ."',".$row['sect_num'].",'".$row['sect_title']."'),");
	 }
	 else{
	    fwrite($file,"(".$row['a_id'].",".$row['chap_num'].",'".$row['chap_title'] ."',".$row['sect_num'].",'".$row['sect_title']."')");

	 }

	}
	fclose($file);	
}
/*
 written by �j�v�S
*/
function importCourse($location){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $version;
	$file = fopen($location."/TextBookDumped.sql","r") or die("TextBookDumped.sql not found");
	/*
	delete���ª����
	*/
 	$sql = "drop table chap_title";
	mysql_db_query($DB.$course_id,$sql);
	/*
	�٭�drop����table
	*/
	$sql = "CREATE TABLE chap_title ( a_id int(10) unsigned NOT NULL auto_increment, chap_num tinyint(3) unsigned DEFAULT '0' NOT NULL, chap_title varchar(128) NOT NULL, sect_num tinyint(3) unsigned DEFAULT '0' NOT NULL, sect_title varchar(128) NOT NULL, PRIMARY KEY (a_id))";
	mysql_db_query($DB.$course_id,$sql);

	$sql = fread($file,filesize($location."/TextBookDumped.sql"));
	/*
	�Ndump�X�Ӫ���Ʀs�^�h
	*/
	mysql_db_query($DB.$course_id,$sql);
	
}



?>
