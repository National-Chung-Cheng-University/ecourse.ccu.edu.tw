<?php
	require 'fadmin.php';
	
	show_page_d ();
	
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "system_news.tpl" ) );
		
			$tpl->define_dynamic ( "news_list" , "body" );
			$count = 0;
			$Q1 = "select a_id, begin_day, subject, important, content FROM news where system = '1' and begin_day <= '".date("Y-m-d")."' and end_day >= '".date("Y-m-d")."' order by begin_day DESC ";
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$error = "資料庫連結錯誤!!";
			}else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$error = "資料庫讀取錯誤!!";
			}
			else {

				while ( $row = mysql_fetch_array( $result ) ) {
					
					$count ++;
					
					$tpl->assign( DATE , $row["begin_day"] );
					$tpl->assign( CONTENT , nl2br( $row["content"] ));
					$tpl->assign( SUJ , "【".$row["subject"]."】" );

					if ( $row["important"] == 2 )
						$tpl->assign( FONT , "#FF0000" );
					else if ( $row["important"] == 1 )
						$tpl->assign( FONT , "#0000FF" );
					else
						$tpl->assign( FONT , "#FFFFFF" );

					$tpl->parse ( N_LIST, ".news_list" );
				}
	
			}
			for ( $i = $count ; $i < 5 ; $i ++ ) {
				$tpl->assign( DATE , "" );
				$tpl->assign( CONTENT , "" );
				$tpl->assign( SUJ , "" );
				if ( $row["important"] == 2 )
					$tpl->assign( FONT , "#FF0000" );
				else if ( $row["important"] == 1 )
					$tpl->assign( FONT , "#0000FF" );
				else
					$tpl->assign( FONT , "#FFFFFF" );
				$tpl->parse ( N_LIST, ".news_list" );
			}
			$tpl->parse( BODY, "body" );
			$tpl->FastPrint("BODY");
		
	}	
	


	
?>