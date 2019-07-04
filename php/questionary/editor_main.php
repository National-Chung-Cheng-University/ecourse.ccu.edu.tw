<?
	require 'fadmin.php';

	// 檢查使用權限.
	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}
	if ( $action == "newblock" ) {
		$reload = 1;
		newblock ();
	}
	else if ( $action == "updateblock" ) {
		$reload = 1;
		updateblock ();
	}
	else if ( $action == "delblock" ){
		$reload = 1;
		delblock();
	}
	else if ( $action == "insertitem" ) {
		$reload = 1;
		additem();
	}
	else if ( $action == "updateitem" ) {
		$reload = 1;
		updateitem();
	}
	else if ( $action == "delitem" ) {
		$reload = 1;
		delitem ();
	}
	else
		show_page_d();

	function delitem () {
		global $version, $course_id, $item_id, $q_id;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "delete from qtiku where a_id = '$item_id'";
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫刪除錯誤!!" );
		}
		$Q2 = "alter table questionary_".$q_id." drop q$item_id";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		show_page_d( "5" );
	}

	function updateitem() {
		global $version, $course_id, $selection1, $selection2, $selection3, $selection4, $selection5, $qgrade, $qtext, $cho, $rownum, $q_id, $block_id, $type, $item;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		if( $qtext == "" ) {
			show_page_d( "6" );
			exit;
		}
		if ( $type == 1 ) {
			$Q1 = "update qtiku set question = '$qtext', selection1 = '$selection1',  selection2 = '$selection2', selection3 = '$selection3', selection4 = '$selection4', selection5 = '$selection5', ismultiple = '$cho', grade = '$qgrade', note='$rownum' where a_id = '$item'";
		}
		else if ( $type == 2 ) {
			$Q1 = "update qtiku set question = '$qtext', grade = '$qgrade' where a_id = '$item'";
		}
		else {
			show_page( "not_access.tpl" ,"題型錯誤!!" );
			exit;
		}
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		show_page_d( "4" );
	}

	function additem () {
		global $version, $course_id, $selection1, $selection2, $selection3, $selection4, $selection5, $qgrade, $qtext, $cho, $rownum, $q_id, $block_id, $type;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		if( $qtext == "" ) {
			show_page_d( "6" );
			exit;
		}
		if ( $type == 1 ) {
			$Q1 = "Insert into qtiku ( q_id, block_id, type, question, selection1, selection2, selection3, selection4, selection5, ismultiple, grade, note ) values ( '$q_id', '$block_id', '$type', '$qtext','$selection1','$selection2','$selection3','$selection4','$selection5', '$cho', '$qgrade', '$rownum')";
		}
		else if ( $type == 2 ) {
			$Q1 = "Insert into qtiku ( q_id, block_id, type, question, grade ) values ( '$q_id', '$block_id', '$type', '$qtext', '$qgrade')";
		}
		else {
			show_page( "not_access.tpl" ,"題型錯誤!!" );
			exit;
		}
		if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
			show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
		}
		$qtikuid = mysql_insert_id();
		if ( $type == 1 ) {
			$Q2 = "alter table questionary_".$q_id." add q$qtikuid tinytext after student_id";
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			//	show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
		}
		else if ( $type == 2) {
			$Q2 = "alter table questionary_".$q_id." add q$qtikuid text after student_id";
			if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			//	show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
			}
		}
		show_page_d( "3" );
	}

	function newblock () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		global $q_id, $course_id, $block_title;
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
		$Q1 = "Insert into qtiku ( q_id, type, question ) values ( '$q_id', '3', '$block_title')";
		$result = mysql_db_query($DB.$course_id, $Q1) or die("資料庫寫入錯誤, $Q1");
		show_page_d( "0" );
	}

	function updateblock () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		global $q_id, $course_id, $block_title, $block_id;
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
		$Q1 = "update qtiku set question='$block_title' where q_id= '$q_id' and a_id ='$block_id'";
		$result = mysql_db_query($DB.$course_id, $Q1) or die("資料庫更新錯誤, $Q1");
		show_page_d( "2" );
	}

	function delblock () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		global $q_id, $course_id, $block_id;
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
		$Q1 = "delete from qtiku where a_id = '$block_id'";
		$result = mysql_db_query($DB.$course_id, $Q1) or die("資料庫寫入錯誤, $Q1");
		show_page_d( "1" );
	}

	function show_page_d ( $errno="" ) {

		include("class.FastTemplate.php3");
		global $block_id, $item, $q_id, $version, $course_id, $reload, $bno, $type, $rownum, $qno, $qgrade;
		if($version == "C") {
			$error_msg[0] = "新增 &lt;主題&gt; <font color='blue'>成功</font>";
			$error_msg[1] = "刪除 &lt;主題&gt; <font color='blue'>成功</font>";
			$error_msg[2] = "更新 &lt;主題&gt; <font color='blue'>成功</font>";;
			$error_msg[3] = "新增 &lt;問題&gt; <font color='blue'>成功</font>";
			$error_msg[4] = "更新 &lt;問題&gt; <font color='blue'>成功</font>";
			$error_msg[5] = "刪除 &lt;問題&gt; <font color='blue'>成功</font>";
			$error_msg[6] = "請輸入題目";
		}
		else {
			$error_msg[0] = "Chapter title Insert ok.";
			$error_msg[1] = "Chapter title Update ok.";
			$error_msg[2] = "Chapter title Insert/Update error.";
			$error_msg[3] = "Section title Insert ok.";
			$error_msg[4] = "Section title Update ok.";
			$error_msg[5] = "Section title Insert/Update error.";	
		}
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	
		$tpl = new FastTemplate("./templates");
	
		// 判斷使用何種template. 注意中英文.
		if(isset($block_id) && empty($item)) {       // editor_chap.tpl   章編輯網頁
			if($version == "C") {
				$tpl->define(array(main => "editor_block.tpl"));
			}
			else {
				$tpl->define(array(main => "editor_block_E.tpl"));
			}
		}
		else if(isset($block_id) && isset($item)) {   // editor_sect.tpl   節編輯網頁
			if($version == "C") {
				$tpl->define(array(main => "editor_item.tpl"));	  
			}
			else {
				$tpl->define(array(main => "editor_item_E.tpl"));	  
			}
		}
		else {                                          // editor_root.tpl  課程/導論編輯網頁
			if($version == "C") {
				$tpl->define(array(main => "editor_root.tpl"));	  
			}
			else {
				$tpl->define(array(main => "editor_root_E.tpl"));	  
			}
		}
	
	 
		// 輸出錯誤訊息.
		if(isset($errno) && $errno != "") 
			$tpl->assign("ERROR_MSG", $error_msg[$errno]);
		else
			$tpl->assign("ERROR_MSG", "");
	
	
		// 關於新增後畫面更新的控制.
		if(isset($reload)) 
			$tpl->assign("RELOAD_CTRL", " onLoad=\"parent.left.location.reload();\"");
		else
			$tpl->assign("RELOAD_CTRL", "");

		$tpl->assign("PHP_ID", $PHPSESSID);
		$tpl->assign("QUESID", $q_id);
		// 關於各種輸出的處理程式.
		if(isset($block_id) && empty($item)) {         
			// editor_block.tpl  各主題編輯畫面
			$tpl->define_dynamic("item_list", "main");
	
			$tpl->assign("BLOCK_NUM", $bno);
			$tpl->assign("BLOCK_ID" , $block_id );
			$sql = "select * from qtiku where block_id='$block_id' and type != '3' and q_id = '$q_id' order by a_id";
			$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	
			// color control.
			$i = false;
			$qno = 0;
			$tpl->assign("ITEM_NUM", "<font color=#ffffff>題號</font>");
			$tpl->assign("ITEM_TYPE", "<font color=#ffffff>類型</font>");
			$tpl->assign("ITEM_BUTTOM", "<font color=#ffffff>刪除</font>" );
			$tpl->assign("ED_COLOR", "#4d6be2");
			$tpl->parse(ROWS, ".item_list");
			while($row = mysql_fetch_array($result)) {
				$qno ++;
				$tpl->assign("ITEM_NUM", $qno);
				if ( $row['type'] == 1 && $row['ismultiple'] == 1 ) {
					if ( $version == "C" )
						$item_title = "選擇題(複選)";
					else
						$item_title = "Choise(multi)";
				}
				else if ( $row['type'] == 1 && $row['ismultiple'] == 0 ) {
					if ( $version == "C" )
						$item_title = "選擇題(單選)";
					else
						$item_title = "Choise(one)";
				}
				else {
					if ( $version == "C" )
						$item_title = "問答題";
					else
						$item_title = "Q&A";
				}
				$tpl->assign("ITEM_TYPE", $item_title);
				$tpl->assign("ITEM_BUTTOM", "<input type=\"button\" value=\"刪除\" OnClick=\"return check_del_item( '".$row['a_id']."' );\"");
	    
				if($i) 
					$tpl->assign("ED_COLOR", "#ffffff");
				else
					$tpl->assign("ED_COLOR", "#edf3fa");
	
				$i = !$i;
				$tpl->parse(ROWS, ".item_list");
			}

			//題型新增
			$Q1 = "SELECT * FROM qtiku WHERE q_id = '$q_id' and block_id = '$block_id' and type != '3'";
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
			}
			$num = mysql_num_rows($result);
			
			$tpl->assign(QNO,$num + 1);
			$tpl->assign(TP.$type,"selected");
		
			if ( $type == 0 || $type == NULL )
				$tpl->assign(ENDLINE, "</center></BODY></HTML>" );
			else if ( $type == 2 )
				$tpl->assign(ENDLINE, "</center>");
			else
				$tpl->assign(ENDLINE, "" );
			$tpl->parse(BODY, "main");	
			$tpl->FastPrint(BODY);
			if ( $type != 0 ) {
				$tpl->assign(ANS_LINK,$ans_link);
				$tpl->assign(TYPE,$type);
				$tpl->assign("ITEM" , $item );
				if ( $type == 1 ) {
					if($version == "C")
						$tpl->assign(KIND,"選擇題");
					else
						$tpl->assign(KIND,"Selection");
					$tpl->define(array(setrow=>"edit_questionaryct.tpl"));
					if($version == "C") {
						$tpl->assign(ROWNUM, "請選擇選項數");
						$tpl->assign( TITLE, "選項數" );
					}
					else {
						$tpl->assign(ROWNUM, "Num of Choise");
						$tpl->assign( TITLE, "Set Num of Choise" );
					}
					$tpl->assign(RO.$rownum, "selected");
					if ( $rownum == 0 || $rownum == NULL )
						$tpl->assign(ENDLINE, "</center></body></html>");
					else
						$tpl->assign(ENDLINE, "</center>");
					$tpl->parse(SETROW,"setrow");
					$tpl->FastPrint("SETROW");
					
					if($version == "C")
						$tpl->define(array(choice=>"edit_questionaryc.tpl"));
					else
						$tpl->define(array(choice=>"edit_questionaryc_E.tpl"));
					$tpl->define_dynamic("row","choice");
					for ( $i = 1 ; $i <= $rownum; $i++ ) {
						$sele = "selection".$i;
						$tpl->assign(NUM, $i);
						$tpl->assign(ORDER, $i);
						$tpl->assign(VALUE, $$sele);
						$tpl->parse(INPUT,".row");
					}
					if($version == "C")
						$tpl->assign(KIND,"選擇題");
					else
						$tpl->assign(KIND,"Selection");
					if($version == "C")
						$tpl->define(array(choice=>"edit_questionaryc.tpl"));
					else
						$tpl->define(array(choice=>"edit_questionaryc_E.tpl"));
					$tpl->assign(SEL1, $selection1);
					$tpl->assign(SEL2, $selection2);
					$tpl->assign(SEL3, $selection3);
					$tpl->assign(SEL4, $selection4);
					$tpl->assign(SEL5, $selection5);
		
					$tpl->assign(CHO.$cho,"selected");
				}
				else if ( $type == 2 ) {
					if($version == "C")
						$tpl->assign(KIND,"簡答題");
					else
						$tpl->assign(KIND,"Q&A");
					if($version == "C")
						$tpl->define(array(choice=>"edit_questionaryf.tpl"));
					else
						$tpl->define(array(choice=>"edit_questionaryf_E.tpl"));
				}
				
				if ( $version == "C" ) {
					$tpl->assign(SUBMIT,"完成");
				}
				else {
					$tpl->assign(SUBMIT,"ADD & Edit Next Quextion");
				}
				
				if ( !($type == "1" && ($rownum == "0" || $rownum == "") ) ) {
					//tail
					if($version == "C")
						$tpl->define(array(tail=>"edit_questionaryb.tpl"));
					else
						$tpl->define(array(tail=>"edit_questionaryb_E.tpl"));
					$tpl->assign(QGRADE,$qgrade);
					$tpl->assign(QTEXT,$qtext);
					$tpl->assign(ROW,$rownum);
					$tpl->assign("ITEM" , $item );
					$tpl->assign(ACT2,"insertitem");
					$tpl->parse(TAIL,"tail");
					$tpl->FastPrint("TAIL");
					//choice
					$tpl->parse(CHI,"choice");
					$tpl->FastPrint("CHI");
				}
			}
		}
		else if(isset($block_id) && isset($item)) {    
			// editor_item.tpl  各問題畫面

			$sql = "select * from qtiku where a_id = '$item'";
			$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
			$rows = mysql_fetch_array($result);
			//傳入的變數
			if ( isset($rownum) && $rownum != "" && item != "" )
				$rows['note'] = $rownum;
			//給editor_item.tpl的變數
			$tpl->assign("BLOCK_ID", $rows['block_id'] );
			$tpl->assign("QUESID", $rows['q_id'] );
			$tpl->assign("TYPE", $rows['type'] );
			$tpl->assign("ITEM", $rows['a_id'] );

			$tpl->assign(ANS_LINK,$rows['answer_desc']);
			if ( $rows['type'] == 1 ) {
				if($version == "C")
					$tpl->assign(KIND,"選擇題");
				else
					$tpl->assign(KIND,"Selection");
				$tpl->parse(MAIN, "main");
				$tpl->FastPrint("MAIN");
				//選擇題 題數
				$tpl->define(array(setrow=>"edit_questionaryct.tpl"));
				if($version == "C") {
					$tpl->assign(ROWNUM, "請選擇選項數");
					$tpl->assign( TITLE, "選項數" );
				}
				else {
					$tpl->assign(ROWNUM, "Num of Choise");
					$tpl->assign( TITLE, "Set Num of Choise" );
				}

				$tpl->assign(RO.$rows['note'], "selected");
				//沒有題數則網頁直接結尾
				if ( $rows['note'] == 0 || $rows['note'] == NULL )
					$tpl->assign(ENDLINE, "</body></html>");
				else
					$tpl->assign(ENDLINE, "");

				$tpl->parse(SETROW,"setrow");
				$tpl->FastPrint("SETROW");
				
				//有題數
				if($version == "C")
					$tpl->define(array(tail=>"edit_questionaryc.tpl"));
				else
					$tpl->define(array(tail=>"edit_questionaryc_E.tpl"));
				$tpl->define_dynamic("row","tail");
				for ( $i = 1 ; $i <= $rows['note']; $i++ ) {
					$sele = "selection".$i;
					$tpl->assign(NUM, $i);
					$tpl->assign(ORDER, $i);
					$tpl->assign(VALUE, $rows["$sele"]);
					$tpl->parse(INPUT,".row");
				}
				$tpl->assign(CHO.$rows['ismultiple'],"selected");
			}
			else {
				if ( $version == "C" ) {
					$tpl->assign("KIND", "問答題" );
				}
				else {
					$tpl->assign("KIND", "A&Q" );
				}
				$tpl->assign(ENDLINE, "" );
				$tpl->parse(MAIN, "main");
				$tpl->FastPrint("MAIN");
				if($version == "C")
					$tpl->define(array(tail=>"edit_questionaryf.tpl"));
				else
					$tpl->define(array(tail=>"edit_questionaryf_E.tpl"));
			}

			if ( $version == "C" ) {			
				$tpl->assign(SUBMIT,"修改");
			}
			else {
				$tpl->assign(SUBMIT,"Modify");
			}

			if ( !($type == "1" && ($rows['note'] == "0" || $rows['note'] == "") ) ) {
				//body
				if($version == "C") {
					$tpl->define(array(body=>"edit_questionaryb.tpl"));
				}
				else {
					$tpl->define(array(body=>"edit_questionaryb_E.tpl"));
				}
				$tpl->assign("QGRADE", $rows['grade'] );
				$tpl->assign("ROW", $rows['note'] );
				$tpl->assign("QTEXT", $rows['question'] );
				$tpl->assign(ACT2,"updateitem");
				$tpl->parse(BODY,"body");
				$tpl->FastPrint("BODY");
				//tail
				$tpl->parse(TAIL,"tail");
				$tpl->FastPrint("TAIL");
			}
		}
		else {                                           
			// editor_root.tpl  基本畫面
			$tpl->define_dynamic("block_list", "main");		
	
			$sql = "select * from qtiku where type='3' and q_id = '$q_id' order by a_id";
			$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	
			$i = false;
			$tpl->assign("BLOCK_TITLE", "<font color=#ffffff>主題標題</font>");
			$tpl->assign("BLOCK_BUTTOM", "<font color=#ffffff>刪除主題</font>" );
			$tpl->assign("ED_COLOR", "#4d6be2");
			$tpl->parse(ROWC, ".block_list");
			while($row = mysql_fetch_array($result)) {
				$tpl->assign("BLOCK_TITLE", $row["question"]);
	
				if($i) 
					$tpl->assign("ED_COLOR", "#ffffff");
				else
					$tpl->assign("ED_COLOR", "#edf3fa");
				$tpl->assign("BLOCK_BUTTOM", "<input type=\"button\" value=\"刪除\" OnClick=\"return check_del_block('".$row["a_id"]."');\">" );
				$i = !$i;
	
				$tpl->parse(ROWC, ".block_list");
			}
			$tpl->parse(BODY, "main");	
			$tpl->FastPrint(BODY);
		}
	}
?>
