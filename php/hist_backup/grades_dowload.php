<?php
require 'fadmin.php';
update_status ("�d�ݾ��v�Ϧ��Z");

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	if($is_hist==1){
		$file_path = "../../echistory/$hist_year/$hist_term/$course_id/grade/score_$course_id.xls";
		include("class.FastTemplate.php3");
		$tpl=new FastTemplate("./templates");
		if ( $version == "C" )
			$tpl->define(array(main=>"grades_dowload.tpl"));
		else
			$tpl->define(array(main=>"grades_dowload_E.tpl"));
		
		if(is_file($file_path)){
			if ( $version == "C" ){
				$tpl->assign(FILE,"<a href=\"$file_path\"><font color=\"#000099\">���v���Z�ɮפU��</font></a>");
			}
			else{
				$tpl->assign(FILE,"<a href=\"$file_path\"><font color=\"#000099\">Download History Grades</font></a>");
			}
		}
		else{
			if ( $version == "C" ){
				$tpl->assign(FILE,"�S�����v���Z�ɮ�");
			}
			else{
				$tpl->assign(FILE,"No History Grades");
			}
		}
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
}
else
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
?>
