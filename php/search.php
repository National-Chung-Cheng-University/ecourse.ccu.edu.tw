<?php
	require 'fadmin.php';
	update_status ("�j�M�\��");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) )
		show_page( "not_access.tpl" ,"�v�����~");
	
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "SELECT validated FROM course where a_id = '$course_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
	}
	else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "��ƮwŪ�����~!!";
	}
	else if ( mysql_num_rows( $result ) == 0 ) {
		$error = "��Ʈw���~!!";
		show_page ( "not_access.tpl", $error );
	}
	else
		$row = mysql_fetch_array($result);
	

	$result="";
	if ( isset($keyword) && $keyword != NULL ) {
		
		if ( $row['validated'] != '2' && $row['validated'] != '3' ) {
			$path = "../$course_id/homework";
			$result = search ( $path );
		}
		else if ( $row['validated'] != '1' && $row['validated'] != '3' ) {
			$path = "../$course_id/textbook";
			$result2 = search ( $path );
		}
		
		        $flag = $row['validated'] ;
			$message = 0;
			if($flag == '0')
			{
				$message = "";
			}
			else if($flag == '1'){
                        	$message = "�ثe�Ч������},�z�L�k�j�M��Ч����e</br>";
			}
			else if($flag == '2')
			{
				$message = "�ثe�@�~�����},�z�L�k�j�M��@�~���e</br>";
			}
			else{
				$message = "�ثe�Ч��M�@�~�������},�z�L�k�j�M����󵲪G</br>";
			}
			
	
		if ( $result )
			$content = $result;
		if ( $result2 )
			$content = $content."\n".$result2;

			
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	
	if( $version == "C" ) {
		$tpl->define ( array ( body => "search.tpl") );
	}
	else {
		$tpl->define ( array ( body => "search_E.tpl") );
	}
	$tpl->assign( COURSEID, $course_id);
	$tpl->assign( VERSION, $version);
	$content = $message.$content;
	
	$tpl->assign( CONTENT, $content);

	$tpl->parse( BODY, "body");
	$tpl->FastPrint("BODY");
	
	
	function search ( $path ) {
		global $keyword;
		
		if ( substr( $path,-1 ) <> "/" )
			$path = $path."/";
		$all = opendir( $path );
		while ( $file = readdir ($all) ) {
			if ( is_dir( $path.$file ) && $file <> ".." && $file <> "." ) {
				$result .= search( $path.$file );
			} 
			elseif ( !is_dir( $path.$file ) ) {
			  if(strstr($file,".htm") || strstr($file,".zip") || strstr($file,".txt") || strstr($file,".doc")){
				$fp=fopen( $path.$file, "r");
				$tmp = fread($fp, filesize($path.$file) );				
				fclose($fp);
				
				if( ($buf = stristr($tmp,"<title>")) == NULL ){
					$find_title = 0;
				}
				else {
					$find_title = 1;
					$title = get_title($buf);
				}
			  	
								
				//var_dump($title);
				//echo "<br>\n";
				
				// Found.
				if(strstr($tmp, $keyword)) {
					if($find_title==1) 
						$result .= "��&nbsp;&nbsp;<a href=$path".$file.">$title</a><br>\n";
					else 
						$result .= "��&nbsp;&nbsp;<a href=$path".$file.">$path".$file."</a><br>\n";
					
					if ( ($buf2 = stristr($tmp,"<body") ) != NULL) {
						$result .= show_digest($buf2);
					}
				}
                          }
			}
                
		}
		return $result;	
	}
	
	function get_title( $buf ) {
		// ���L <title> �o��tag.
		$buf = substr($buf, 7);
		
		// ��X </title> ���_�l��m.
		$pos = strpos($buf, "<");
		
		// ��<title> </title>�����F��Ǧ^�h.
		return substr($buf, 0, $pos);
	}

	function show_digest( $buf ) {
		$i = 0;
		$j = 0;
		
		// �ٲ��� <body �᭱�� �ݩ�.
		while($buf{$i} != '>'){
			$i++;
		}
		$i++;
		
		for($i = $i ; $i < strlen($buf) && $j < 80 ; $i++) {
			/*�p�G�I�쪺��r�O html �y�k����r�A�N���L���g�J buf ��*/
			if($buf{$i}=='<') {
				while($buf{$i}!='>')
					$i++;
			}
			else { 
				$tmp{$j}=$buf{$i};
				$j++;
			}
		}
		$tmp = implode("", $tmp);
		return "�K�n�G $tmp .......<br><br>\n";
	}
?>
