<?php
	require 'fadmin.php';
	
	if ($_SESSION['verifySso'] == "Y" ){
                ssoLogOut();
				/*
                  session_destroy();
                  echo ( "<script Language = \"JavaScript\">" );
                                            echo ( "parent.location = \"http://portal.ccu.edu.tw/sso_index.php\"" );
                                             echo ( "</script>" );
				*/
        }
        else

	{
		if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
			echo ( "<script Language = \"JavaScript\">" );
			echo ( "parent.location = \"http://$SERVER_NAME/\"" );
			echo ( "</script>" );
		}
		else {
/*			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
			if ( $user_id == "guest" ) {
				$ip = getenv ( "REMOTE_ADDR" );
				if ( $ip == "" )
					$ip = $HTTP_X_FORWARDED_FOR;
				if ( $ip == "" )
					$ip = $REMOTE_ADDR;
				$Q1 = "delete from online where user_id = '$user_id' and host='$ip'";
			}
			else
				$Q1 = "delete from online where user_id = '$user_id'";
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$error = "��Ʈw�s�����~!!";
				show_page ( "not_access.tpl", $error );
			}
			else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
				$error = "��ƮwŪ�����~!!";
				show_page ( "not_access.tpl", $error );
			}*/
			session_unregister("time");
			session_unregister("course_id");
			session_unregister("doc_root");
			session_unregister("work_dir");
			session_unregister("guest");
			session_unregister("admin");
			session_unregister("admin_with_auth_5");
			session_unregister("teacher");
			session_unregister("user_id");
			session_unregister("version");
			session_unregister("texttime");
			session_unregister("prevchapter");
			session_unregister("prevsection");
			session_unregister("is_hist"); //���v�Ϸs�W
			session_unregister("hist_year"); //���v�Ϸs�W
			session_unregister("hist_term"); //���v�Ϸs�W
			session_unregister("course_year"); //�s�W�i�J�ҵ{���Ǧ~��
			session_unregister("course_term"); //�s�W�i�J�ҵ{���Ǧ~��
			session_destroy();
			echo ( "<script Language = \"JavaScript\">" );
			echo ( "parent.location = \"http://$SERVER_NAME/\"" );
			echo ( "</script>" );
		}
	}


?>
