<?php
/***************************************/
/*  2008.10.22 新增功能 by w60292      */
/*  新增電子點名功能                   */
/*  上傳檔案為 .txt 檔                 */
/*  紀錄檔存於該課程資料夾的roll/底下  */
/***************************************/
require 'fadmin.php';
update_status ("學生完整使用記錄");

if( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) && !(session_is_registered("admin") && $admin == 1) )
{
        show_page( "not_access.tpl" ,"權限錯誤");
        exit;
}
if($check < 2 )
{
        if( $version=="C" ) {
                show_page( "not_access.tpl" ,"你沒有權限使用此功能");
                exit;
        }
        else {
                show_page( "not_access.tpl" ,"You have No Permission!!");
                exit;
        }
}

global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum, $course_year, $course_term;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
        echo ( "資料庫連結錯誤!!" );
        return;
}
$Q1 = "Select student_id From take_course Where course_id='$course_id' and year='$course_year' and term = '$course_term' Order By student_id ASC";
if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) )
{
        echo ("資料庫讀取錯誤!!");
        return;
}
else
{
        if( mysql_num_rows ( $resultOBJ ) == 0 )
        {
                if( $version=="C" )
                        show_page( "not_access.tpl" ,"此課程尚未有任何學生!");
                else
                        show_page( "not_access.tpl" ,"There is no Student in this Class!!");
        }
        else
        {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");

		if($version == 'C') {
			$tpl->define(array(student_list => "ElectionRoll.tpl"));
		}
		else {
			$tpl->define(array(student_list => "ElectionRoll_En.tpl"));
		}
		$tpl->define_dynamic("row", "student_list");
		$tpl->assign( SKINNUM , $skinnum );
		$color = "#000066";
		$tpl->assign( COLOR , $color );

		if($action=="upload") {
			$success = 0;
	                if($userfile != "none") {
				if(!is_dir("../../$course_id/roll")) {
					mkdir("../../$course_id/roll", 0771);
					chmod("../../$course_id/roll", 0771);
				}
    				$filename = "$date.txt";
				$location = "../../$course_id/roll/";
				fileupload ( $userfile, $location, $filename);

				$Qroll_id = "Select MAX(roll_id) From roll_book";
				$result_roll_id = mysql_db_query( $DB.$course_id, $Qroll_id );
                                $row_roll_id = mysql_fetch_array($result_roll_id);
                                $roll_id =  $row_roll_id['MAX(roll_id)']+1;

				while($row = mysql_fetch_array ($resultOBJ)) {
					$stuid = $row['student_id'];
					$Q0 = "insert into roll_book(roll_id, user_id, roll_date, state, note) values('$roll_id', '$stuid', '$date', '1', '')";
					if ( !($result0 = mysql_db_query( $DB.$course_id, $Q0 ) ) )
					{
						echo ("資料庫寫入錯誤");
						return;
					}
				}

				$handle = fopen("../../$course_id/roll/$date.txt", "r");
				if ($handle) {
					while (!feof($handle)) {
						$buffer = fgets($handle, 4096);
						$Q1 = "select a_id from user where id=$buffer";
						$result1 = mysql_db_query( $DB,$Q1 );
						$row1 = mysql_fetch_array($result1);

						$Q2 = "select student_id from take_course where course_id='".$course_id."' and student_id='".$row1['a_id']."'";
						$result2 = mysql_db_query( $DB,$Q2 );
						$row2 = mysql_fetch_array($result2);
						$stuid = $row2['student_id'];
						if($row2['student_id'] != "") {
							$Q3 = "update roll_book set state='0' where roll_id='$roll_id' and user_id='$stuid'";
							 mysql_db_query( $DB.$course_id, $Q3 );
						}
					}
				}
				fclose($handle);
				header("Location: ./RollBook.php");
 			} 
			else 
			{
    				echo "沒有檔案";
			}
		}

		$tpl->parse(BODY, "student_list");
                $tpl->FastPrint("BODY");
	}
}
?>
