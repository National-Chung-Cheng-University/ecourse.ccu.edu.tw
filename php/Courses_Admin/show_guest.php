<?PHP
	require 'fadmin.php';
	if (!isset($ver) && isset($PHPSESSID) && session_check_stu($PHPSESSID)) {
		session_unregister("teacher");
		session_unregister("admin");
		session_register("guest");
		$guest = 1;
	}
	else {
		session_start();
		session_unregister("teacher");
		session_unregister("admin");
		session_unregister("course_id");
		//�p��ϥήɶ���
		session_unregister("time");
		session_register("time");
		session_register("user_id");
		session_register("version");
		session_register("guest");
		$version = $ver;
		$user_id = $id;
		$guest = 1;
		$time = date("U");
		add_log ( 1, $user_id );
		unset($ver);
		header( "Location: http://$SERVER_NAME/php/Courses_Admin/show_guest.php?PHPSESSID=".session_id());
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "show_guest.tpl" ) );
	$tpl->define_dynamic ( "college_list" , "body" );
	$tpl->define_dynamic ( "department_list" , "body" );
	$tpl->define_dynamic ( "td_list" , "body" );
	
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $groupid;
	//�d�ߥX�Ҧ��ǰ|���W�١A���սҵ{(98)��@�ҥ~
	$Q0 = "select a_id , name from course_group where level = '1' AND a_id != 98 order by a_id";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "$message - ��Ʈw�s�����~!!";
	}
		
	if ( !($result0 = mysql_db_query( $DB, $Q0 ) ) ) {
		$message = "$message - ��ƮwŪ�����~!!";
	}
	else if ( mysql_num_rows( $result0 ) != 0 ) {
		while ( $row0 = mysql_fetch_array( $result0 ) ) {
			$college_group[] = $row0['a_id'];
			$tpl->assign( COL_NAME , $row0['name'] );
			$tpl->parse ( COLLEGELIST, ".college_list" );
		}
	}
	//�d�ߥX�U�Ӿǰ|�U���t�ҦW�١A�@�P��(92)��@�ҥ~
	foreach( $college_group as $college_id)
	{
		$Q1 = "select a_id , name from course_group where is_leaf  = '1' AND a_id != 92 AND is_use = 'Y' AND parent_id = ".$college_id." order by a_id";
		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else if ( mysql_num_rows( $result1 ) != 0 ) {
			$i=0;
			while ( $row1 = mysql_fetch_array( $result1 ) ) {
				$i++;
				$tpl->assign( DEP_URL , "http://$SERVER_NAME/php/Courses_Admin/guest.php?groupid=".$row1['a_id']."&PHPSESSID=".session_id() );
				$tpl->assign( DEP_NAME , $row1['name'] );
				//����@�ӷs���ǰ|�ɡA�Ndepartment_list�л\���A�_�h�Ϊ��[��
				if($i==1){
					$tpl->parse ( DEPARTMENTLIST, "department_list" );
				}
				else{
					$tpl->parse ( DEPARTMENTLIST, ".department_list" );
				}
			}
		}
		$tpl->parse ( TDLIST, ".td_list" );
	}
	$tpl->assign( INDEX , "http://$SERVER_NAME" );	
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
?>
