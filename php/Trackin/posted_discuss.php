<?php
/*******************
 *posted_discuss.php
 *�q�������Q�װϸ̭���X�Y��ǥ͵o���Ҧ��峹
 ******************/
require 'fadmin.php';

if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
        show_page( "not_access.tpl" ,"�v�����~");
        exit;
}
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
        echo ( "��Ʈw�s�����~!!" );
        return;
}


if($_GET['student_aid']!="" && $check>1 ){//�O�Юv����
 $_user_id=getUser_idFromA_id($_GET['student_aid']);
}
else{//�O�ǥ�
 $_user_id = $_SESSION['user_id'];
}


$Q1 = 'SELECT a_id,discuss_name FROM discuss_info';
$result1 = mysql_db_query($DB.$course_id , $Q1);
$discuss_num = mysql_num_rows($result1);//�o���Ҧ��h�֭ӰQ�װ�


include("class.FastTemplate.php3");
$tpl = new FastTemplate("./templates");
if($version == "C") {
	$tpl->define(array(main => 'posted_discuss.tpl') );
}
else{
	$tpl->define(array(main => 'posted_discuss_E.tpl') );
}

$tpl->define_dynamic("article_list", "main");
$bgcolor_counter = 0;

for($i=1 ; $i <= $discuss_num ; $i++){//��X�C�ӰQ�װϸӾǥͩҵo���峹
	$row1 = mysql_fetch_array($result1);//�Q�װϼ��D
	$discuss_name = $row1['discuss_name'];
	$discuss_aid = $row1['a_id'];
	
	$tablename = "discuss_$discuss_aid";
	$Q2 = "SELECT * FROM $tablename WHERE poster='$_user_id' ";//�q�U�Q�װϧ�X�o��̲ŦX���峹
	$result2 = mysql_db_query($DB.$course_id ,$Q2);
	
	if($result2!=null)
	while($row2 = mysql_fetch_array($result2)){
		$article_id = $row2['a_id'];
		$title = "<a href=../discuss/show_article.php?discuss_id=$discuss_aid&article_id=".$article_id."&PHPSESSID=$PHPSESSID >".$row2['title'].'</a><br>';
		$discuss_link = "<a href='../discuss/article_list.php?discuss_id=$discuss_aid&PHPSESSID=$PHPSESSID' >$discuss_name</a>";
		$tpl->assign('DISCUSSNAME',$discuss_link);
		$tpl->assign("ITEMA", $title);
                $tpl->assign("ITEMB", GetUserName($row2['poster']) );
                $tpl->assign("ITEMC", $row2['created']);
                $tpl->assign("ITEMD", $row2['replied']);
                $tpl->assign("ITEME", $row2['viewed']);

		if($bgcolor_counter%2 == 0)
			$tpl->assign("ARCOLOR", "#ffffff");
                else
                        $tpl->assign("ARCOLOR", "#edf3fa");

		$tpl->assign("CHILDS", getReplies($tablename,$article_id) );

		$tpl->parse(ROWA, ".article_list");
		$bgcolor_counter++;
	}
}

$tpl->parse(BODY, "main");

$tpl->FastPrint(BODY);


//-----------------FUNCTIONS----------------

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

function GetUserName($_user_id) {

                global $DB;

                $sql = "select name,nickname from user where id='$_user_id'";
                $result = mysql_db_query($DB, $sql) or die("��Ʈw�d�߿��~, $sql");

                // check name field. if exists, use it as poster name.
                if(mysql_num_rows($result) > 0) {
                        $row = mysql_fetch_array($result);
                        if( strcmp($row["nickname"], "" )!=0) {
                                $poster = $row["nickname"];
                        }
                        elseif(strcmp($row["name"], "" ) !=0 ) {
                                $poster = $row["name"];
                        }
                        else {
                                $poster = $_user_id;
                        }
                }
                else {
                        // Default.
                        $poster = $_user_id;
                }

                return $poster;
}

function getReplies($tablename , $article_id){
	global $DB,$course_id;
	// Total reply atircle number.
	$replies = 0;

	$sql2 = "select count(*) from $tablename where parent=$article_id";
	$result2 = mysql_db_query($DB.$course_id, $sql2) or die("��Ʈw�d�߿��~, $sql2");
	if( $row2 = mysql_fetch_array($result2) ) {
		$replies = $row2[0];
	}

	return $replies;

}


?>
