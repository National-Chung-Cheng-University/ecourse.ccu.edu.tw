<?
//jimmykuo @ 20101202 �\��O��s�ǥͪ��o��峹����
require 'fadmin.php';

mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");
update_article_num("20331");

//modified by jimmykuo @ 20101127 ��s�o��峹����
function update_article_num($course_id){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
	$sql = "SELECT student_id FROM take_course WHERE course_id=$course_id";
	$result = mysql_db_query($DB, $sql);
	while($row=mysql_fetch_array($result)){
		$a_id = $row['student_id'];
		$user_id = getUser_idFromA_id($a_id);
		$count = posted_article_num($user_id);
		$q7 = "UPDATE log SET tag3=$count WHERE user_id=$a_id AND event_id='6'";
		mysql_db_query($DB.$course_id, $q7) or die("��Ʈw�d�߿��~, $q7");
		echo "\"".$user_id."\"�bcourse_id=\"".$course_id."\"���o��峹������s����<br>";
	}
}

//added by jimmykuo @ 20101127 �p��o��峹����
function posted_article_num($user_id){
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;

	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
        	echo ( "��Ʈw�s�����~!!" );
        	return;
	}
        $count =0 ;
        $Q1 = 'SELECT a_id,discuss_name FROM discuss_info';
        $result1 = mysql_db_query($DB.$course_id , $Q1);
        $discuss_num = mysql_num_rows($result1);//�o���Ҧ��h�֭ӰQ�װ�

        for($i=1 ; $i <= $discuss_num ; $i++){//��X�C�ӰQ�װϸӾǥͩҵo���峹
                $row1 = mysql_fetch_array($result1);//�Q�װϼ��D
                $discuss_name = $row1['discuss_name'];
                $discuss_aid = $row1['a_id'];

                $tablename = "discuss_$discuss_aid";
                $Q2 = "SELECT * FROM $tablename WHERE poster='$user_id' ";//�q�U�Q�װϧ�X�o��̲ŦX���峹
                $result2 = mysql_db_query($DB.$course_id ,$Q2);

                if($result2!=null)
                $count += mysql_num_rows($result2);

        }

        return $count;
}
function getUser_idFromA_id($user_aid){

global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
        echo ( "��Ʈw�s�����~!!" );
        return;
}

        $Q0 = "Select id, authorization From user Where a_id='$user_aid'";
        if ( !($resultOBJ0 = mysql_db_query( $DB, $Q0 ) ) ) {
                show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
                exit;
        }
        if ( !($row0 = mysql_fetch_array ( $resultOBJ0 )) ) {
                show_page( "not_access.tpl" ,"�ϥΪ̸�ƿ��~!!" );
                exit;
        }
        if($row0['authorization'] == "9")
        {
                if( $version=="C" ) {
                        show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
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
