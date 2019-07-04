<?
//jimmykuo @ 20101202 功能是更新學生的發表文章紀錄
require 'fadmin.php';

mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
update_article_num("20331");

//modified by jimmykuo @ 20101127 更新發表文章次數
function update_article_num($course_id){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
	$sql = "SELECT student_id FROM take_course WHERE course_id=$course_id";
	$result = mysql_db_query($DB, $sql);
	while($row=mysql_fetch_array($result)){
		$a_id = $row['student_id'];
		$user_id = getUser_idFromA_id($a_id);
		$count = posted_article_num($user_id);
		$q7 = "UPDATE log SET tag3=$count WHERE user_id=$a_id AND event_id='6'";
		mysql_db_query($DB.$course_id, $q7) or die("資料庫查詢錯誤, $q7");
		echo "\"".$user_id."\"在course_id=\"".$course_id."\"的發表文章紀錄更新完成<br>";
	}
}

//added by jimmykuo @ 20101127 計算發表文章次數
function posted_article_num($user_id){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
        	echo ( "資料庫連結錯誤!!" );
        	return;
	}
        $count =0 ;
        $Q1 = 'SELECT a_id,discuss_name FROM discuss_info';
        $result1 = mysql_db_query($DB.$course_id , $Q1);
        $discuss_num = mysql_num_rows($result1);//這門課有多少個討論區

        for($i=1 ; $i <= $discuss_num ; $i++){//找出每個討論區該學生所發的文章
                $row1 = mysql_fetch_array($result1);//討論區標題
                $discuss_name = $row1['discuss_name'];
                $discuss_aid = $row1['a_id'];

                $tablename = "discuss_$discuss_aid";
                $Q2 = "SELECT * FROM $tablename WHERE poster='$user_id' ";//從各討論區找出發文者符合的文章
                $result2 = mysql_db_query($DB.$course_id ,$Q2);

                if($result2!=null)
                $count += mysql_num_rows($result2);

        }

        return $count;
}
function getUser_idFromA_id($user_aid){

global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
        echo ( "資料庫連結錯誤!!" );
        return;
}

        $Q0 = "Select id, authorization From user Where a_id='$user_aid'";
        if ( !($resultOBJ0 = mysql_db_query( $DB, $Q0 ) ) ) {
                show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
                exit;
        }
        if ( !($row0 = mysql_fetch_array ( $resultOBJ0 )) ) {
                show_page( "not_access.tpl" ,"使用者資料錯誤!!" );
                exit;
        }
        if($row0['authorization'] == "9")
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
        $a_id = $row0['id'];
        return $a_id;
}

?>
