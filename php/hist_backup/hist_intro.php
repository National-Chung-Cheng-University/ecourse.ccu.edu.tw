<?php
	require 'fadmin.php';
	update_status ("���v�Ͻҵ{�j��");
	if ( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID))) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}

	show_page_d ();	
	
	function show_page_d ( $error="" ) {
		global $course_id, $check, $teacher, $version, $skinnum, $PHPSESSID, $is_hist, $hist_year, $hist_term;	
		if($is_hist ="1")
		{	
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate ( "./templates" );
			$content = "";
			$tpl->define ( array ( body => "intro.tpl") );
			$tpl->assign ( HEAD, "");
			$tpl->assign( SKINNUM , $skinnum );

			echo "<div align=\"center\" class=\"style1\"><a href=# onClick=\"window.open('./hist_intro_content.php?PHPSESSID=$PHPSESSID&courseid=$course_id&showintro=1', '', 'width=720,height=540,resizable=1,scrollbars=1');\">�w���½Ҥj��</a></div>";
					
			if ( $version == "C" )
				$tpl->define ( array ( tail => "introi.tpl") );
			else
				$tpl->define ( array ( tail => "introi_E.tpl") );
								
			$tpl->define_dynamic("file_list", "tail");
				
			$work_dir = "../../echistory/$hist_year/$hist_term/$course_id/intro/";
			$handle = dir($work_dir);
			$i=false;
			while (( $file = $handle->read() ) ) {
				if(strcmp($file,".") !=0 && strcmp($file,"..")) {   
				// ���F '.' '..'���~���ɮ׿�X
					$tpl->assign("FILE_N", $file);
					$tpl->assign("FILE_LINK", $work_dir."/".$file);
					$tpl->assign("FILE_SIZE", filesize($work_dir."/".stripslashes($file)));
					$tpl->assign("FILE_DATE", date("Y-m-d H:i:s",filemtime($work_dir."/".$file)));						

				// �C�ⱱ��.
					if($i)
						$tpl->assign("F_COLOR", "#ffffff");
					else
						$tpl->assign("F_COLOR", "#edf3fa");
		
					$i=!$i;
					
					$tpl->parse(ROWF, ".file_list");
					$set_file = 1;
				}
			}
			$handle->close();

			// �S�������ɮשΥؿ��ɪ��ҥ~�B�z
			if($set_file==0) {
				$tpl->assign("FILE_N", "");
				$tpl->assign("FILE_SIZE", "");
				$tpl->assign("FILE_DATE", "");
				$tpl->assign("DELETE", "");
				$tpl->assign("F_COLOR", "#edf3fa");
			}
			$tpl->parse( TAIL, "tail");
			$tpl->FastPrint("TAIL");
		}				
	}
?>