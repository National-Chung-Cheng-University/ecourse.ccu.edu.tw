<?php
session_register("course_info");
session_register("report");
require 'fadmin.php';
if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2){
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $skinnum, $SDB, $course_year, $course_term;
	//echo $SDB;
	$course_info = NULL;
	$report = NULL;
	$uploaded_count=0;
	// �s��mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	// ���X�ҵ{�N�X
	$Qs = "select course_no from course where a_id=$course_id";
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_no = explode("_",$row['course_no']);
		$cour_cd = $course_no[0];
		$grp = $course_no[1];
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}
	//log ip�ΰʧ@
	add_log('12',$user_id,'',$course_id,'','�n���`���Z');
	$deptcd = get_deptcd();

	include("class.FastTemplate.php3");
	$tpl=new FastTemplate("./templates");
	$tpl->define(array(main=>"TGShowFrame.tpl"));
	$tpl->define_dynamic("grade", "main");
	$tpl->assign( SKINNUM, $skinnum );
	$course_info['cid'] = $cour_cd;
	$course_info['gid'] = $grp;

	//�@���X�ҵ{�W�٤Ψt�ҦW��
	$cno = $cour_cd."_".$grp;
	$Qs = "select course.name as cname, course_group.name as gname from course, course_group where course.group_id=course_group.a_id and course.course_no = '$cno'";
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_info['cname'] = $row['cname'];
		$course_info['gname'] = $row['gname'];
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}

	// ���X�}�ҾǦ~�פξǴ�
	/*$Qs = "select year,term from teach_course where course_id = '$course_id' order by year DESC, term DESC";
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_info['year'] = $row['year'];
		$course_info['term'] = $row['term'];
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}
	*/
	$course_info['year'] = $course_year;
	$course_info['term'] = $course_term;
	// ���X�Юv�m�W
	$Qs = "select u.name as name from user as u, teach_course as tc where u.authorization='1' and u.a_id=tc.teacher_id";
	$Qs .= " and tc.year=".$course_info['year']." and tc.term=".$course_info['term']." and tc.course_id=".$course_id;
	//$Qs = "select name from user where id='$user_id'";
	if ($result = mysql_db_query($DB,$Qs)){
		$row = mysql_fetch_array($result);
		$course_info['tch'] = $row['name'];
		while( $row = mysql_fetch_array($result) )
			$course_info['tch'] .= ','.$row['name'];
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}
 
	// ���X���Z�e��I���
	$Qs = "select s_deadline, g_deadline from extra where year = '$course_info[year]' and term = '$course_info[term]'";
	if ($result = mysql_db_query($DB,$Qs)){
		if(($row = mysql_fetch_array($result))!=0){
			$sd = explode('-', $row['s_deadline']);
			$gd = explode('-', $row['g_deadline']); 
			$course_info['s_deadline'] = $sd[0]."/".$sd[1]."/".$sd[2];
			$course_info['g_deadline'] = $gd[0]."/".$gd[1]."/".$gd[2];
		}
		else{
			//�p�G���O���~�צ��Z�W�ǥ\�� �N�����ɨ�ĵ�i����
			show_page( "not_access.tpl" ,"�ثe�|���}��$course_info[year]�Ǧ~$course_info[term]�Ǵ������Z�W�ǥ\��,�Щ�}���A��ϥΦ��\��!");
			exit;
			//$course_info['s_deadline'] = " / / ";
			//$course_info['g_deadline'] = " / / ";
		}
	}
	else{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs<br>";
	}	
	
	//**********2006-5-17
	//**********�s�@�ǥͦC����Excel �ɴ��ѤU�� ����http://ecourse.elearning.ccu.edu.tw/php/Trackin/TGQueryFrame1.php�i�H�����ɮ�
	//exec("wget  http://ecourse.elearning.ccu.edu.tw/php/Trackin/TGQueryFrame1.php");
	$file_name="../../$course_id/score_$course_id.xls";
	//�p�G�ɮצs�b�A�N���R��
	if(file_exists($file_name))
		unlink($file_name);
	//�}���ɮ�
	$file=fopen("$file_name","w");
	//�g�J���D
	if($version == "C")
		fwrite($file,"�Ǹ�\t�m�W");
	else
		fwrite($file,"Student ID\tName");
	if($version == "C")
		fwrite($file,"\t�`���Z");
	else
		fwrite($file,"\tTotal Score");
	//�g�J�ǥͩm�W�Ǹ�
	$Q = "SELECT u.a_id, u.id, u.name FROM user u, take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' ORDER BY u.id";
	if (!($result0 = mysql_db_query($DB, $Q) ) ) {
		show_page( "not_access.tpl" ,"��ƮwŪ�����~1!!" );
		exit;			
	}
	//���o�ǥͪ���Tid name
	while($rows = mysql_fetch_array($result0)) {
		fwrite($file,"\n".chop($rows['id'])."\t".chop($rows['name']));
	}		
	//����
	fclose($file);
	$tpl->assign(DOWNLOAD_F,"$course_id/score_$course_id.xls");
	
	//**********2006-4-23�s�W
	//*********�ѤW�Ǫ�Excel�ɮסA��s���Z
	//------action = uploadgrade---------------
	$do_upload_excel = 0; //�ΨӧP�O�O�_�n�ΤW�Ǫ��覡��s���Z
	if($action == "uploadgrade"){
		//�W���ɮ�
		if($uploadfile1 != "none")
		{
			$ext = strrchr( $uploadfile1_name, '.' );
			if($ext != ".xls"){
				show_page( "not_access.tpl" ,"�W�Ǫ��ɮפ��OExcel�ɮ�!!!<br> �Э��s��ܥ��T�����Z�ɮ�." );
			}			
			$filename="score_".$course_id.$ext;
			$location="../../$course_id/";
			if ( fileupload ( $uploadfile1, $location, $filename ) ) {
				$Q1 = "SELECT a_id FROM user WHERE id='$user_id'";
				if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
					show_page( "not_access.tpl" ,"��ƮwŪ�����~!!" );
				}
				$row1 = mysql_fetch_array( $result1 );
			}
			//Ū���ɮ�
			require_once 'reader.php';
			// ExcelFile($filename, $encoding);
			$data = new Spreadsheet_Excel_Reader();
			
			// Set output Encoding.
			$data->setOutputEncoding('Big5');
			$check = $data->read($location.$filename);
			//---�N�Ǹ����ZŪ�X�s�� $all_grade
			//��Execl
			if($check == "is_fake"){
			error_reporting(E_ALL ^ E_NOTICE);
				$fp = fopen($location.$filename,"r");
				$query_file = fread($fp, filesize($location.$filename)-1);
				fclose($fp);
				//��\n �Nquery_file�����ܦh��
				$query_list = explode("\n",$query_file);
				$list_count = count($query_list);
				//��\t �A�N�C������ܦh�Ӧr��
				for($i=0; $i < $list_count; $i++){
					$all_grade[$i] = explode("\t",$query_list[$i]);
				}
				//$student_num = $i-1;
			}
			//�OExecl
			else{
				error_reporting(E_ALL ^ E_NOTICE);
				//�A�N �Ǹ� �`���Z�s�J array
				//echo $data->sheets[0]['numRows'];
				for ($i = 0; $i < $data->sheets[0]['numRows']; $i++) {
					for ($j = 0; $j < $data->sheets[0]['numCols']; $j++) 
						$all_grade[$i][$j] = $data->sheets[0]['cells'][$i+1][$j+1];
				}
				//$student_num = $i-1;
			}
			$last = sizeof($all_grade[0]);
			
			//---�P�O�W�Ǫ��ɮ׬O�_�����~
			$Q0 = "SELECT u.a_id, u.id, u.name FROM user u, take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.credit = '1' and tc.year='$course_year' and tc.term = '$course_term' ORDER BY u.id";
			if (!($result0 = mysql_db_query($DB, $Q0) ) ) {
				show_page( "not_access.tpl" ,"��ƮwŪ�����~1!!" );
				exit;			
			}
			//���o�ǥͪ���Tid name
			for($i=0; $rows = mysql_fetch_array($result0) ;$i++) {
				//echo $rows[a_id]." ".$rows[id]." ".$rows[name]."<br>";
				$course_student[$i]['id'] = $rows['id'];
				$course_student[$i]['name'] = $rows['name'];
				//echo $course_student[$i]['name']." ".$course_student[$i]['id'];
			}
			
			$course_student_num = mysql_num_rows($result0);
			//echo $course_student_num;
			for($i=0, $k=0; $i < $course_student_num ;$i++ ){
				for($j=0 ; $j< $course_student_num;$j++ ){
					if( strcasecmp($course_student[$i]['id'], $all_grade[$j+1][0]) == 0){
						$course_student[$i]['score'] = $all_grade[$j+1][$last-1];
						//echo  $course_student[$i]['id']." ";
						break;
					}
				}
				//notfound student ID
				if($j >= $course_student_num){
					$notfound[$k]['name'] = $course_student[$i]['name'];
					$notfound[$k]['id'] = $course_stundent[$i]['id'];
					//echo $notfound[$k]['name']."__".$notfound[$k]['id']."<br>";
					$k++;
				}
			}
			//���S��쪺���
			if($k != 0){
			}
		}
			//$last = sizeof($all_grade[0]);
			$do_upload_excel = 1;	
			//echo $last;
	}//*********2006-4-23�s�W

	/* ------------------------------------------------------ */
	/* Modified by carlyle                                    */
	/* ------------------------------------------------------ */
	$loadtemp = $_GET['loadtemp'];

	//�ˬd�o���ҬO�_�w�g���Ȧs���Z
	$Q1 = "SELECT * FROM take_exam WHERE exam_id = '-2'";
	if (!($result = mysql_db_query($DB.$course_id,$Q1))) {
		show_page("not_access.tpl","��ƮwŪ�����~1!!");
		exit;
	} else
		$count = mysql_num_rows($result);

	if ($count != 0 && $loadtemp != '1') //���Ȧs���Z
		$tpl->assign("#!COMMAND!#","ShowHint();");
	else //�L�Ȧs���Z
		$tpl->assign("#!COMMAND!#","");
	/* ------------------------------------------------------ */

	// �s����Ҩt�θ�Ʈw
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
//	echo "cnx:".$cnx."<br>";
	$csd = @sybase_select_db($SDB, $cnx);

	// ���o�Ǥ���
	$Qs = "select credit from a30vcourse_tea where course_no = '$cour_cd'";
	$cur = sybase_query($Qs, $cnx);
	if(!$cur) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	if(($array=sybase_fetch_array($cur))!=0){
		$course_info["credit"] = $array['credit'];
	}else{
		$course_info["credit"] = "";
	}
	
	// ���X�Ҧ���Ҿǥ;Ǹ��Ωm�W
	// �]���� �N a31v_sel_class_summer_tea �קאּ a31v_sel_class_tea
	$Qs = "select cs.std_no, vstd.name  from a31v_sel_class_summer_tea cs, a11vstd_rec_tea vstd where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=vstd.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
	"union select cs.std_no, leave.name from a31v_sel_class_summer_tea cs, a11vleave_rec_tea leave where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=leave.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
	"union select cs.std_no, vstd.name  from a31vhis_sel_class_tea cs, a11vstd_rec_tea vstd where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=vstd.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' ".
	"union select cs.std_no, leave.name from a31vhis_sel_class_tea cs, a11vleave_rec_tea leave where cs.cour_cd = '$cour_cd' and cs.grp = '$grp' and cs.std_no=leave.id and cs.year = '$course_info[year]' and cs.term = '$course_info[term]' order by std_no";
	$cur = sybase_query($Qs, $cnx);
	if(!$cur) {
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	for($i=0;$i<sybase_num_rows($cur);$i++){
		$row = sybase_fetch_array($cur);
		$Qs1 = "select a_id from user where id = '$row[std_no]'";
		if ( !($result1 = mysql_db_query( $DB, $Qs1 )) ) {
			show_page( "not_access.tpl" ,"��ƮwŪ�����~!! $Qs1 <br>" );
			exit;
		}
		else{
			$row1 = mysql_fetch_array($result1);
		}
		$Qs2 = "select grade from take_exam where exam_id = '-1' and student_id = '$row1[a_id]' order by student_id";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Qs2 ) ) ) {
			show_page( "not_access.tpl" ,"��ƮwŪ�����~!! $Qs2 <br>" );
			exit;
		}
		else{
			if ($row2 = mysql_fetch_array($result2))
				$report[$i]['grade'] = $row2['grade'];
			else
				$report[$i]['grade'] = '';
			$report[$i]['std_no'] = $row['std_no'];
			$report[$i]['name'] = $row['name'];
/*********************************************************************************************************************************/
/*******devon 2006-02-09************/
/*******�P�_��ǡB�h�Ǫ����A********/
/*************************************************
		a11tstd_his_rec:�O�ثe�b�Ǫ����
        a11vleave_rec_tea:�O�h��or���~�����
*************************************************/
//98.08.12 �]���׸�ƭק�N year=97 term=2
			//$Qs5 = "select * from a11tstd_his_rec where std_no = '$row[std_no]' and year = '$course_info[year]' and term = '$course_info[term]'";
			$Qs5 = "select * from a11tstd_his_rec where std_no = '$row[std_no]' and year = '97' and term = '2'";
			$Qs6 = "select status from a11vleave_rec_tea where id = '$row[std_no]'";
			$cur5 = sybase_query($Qs5, $cnx);
			$cur6 = sybase_query($Qs6, $cnx);
			$nums = sybase_num_rows($cur5);
			$row5 = sybase_fetch_array($cur5);
			$row6 = sybase_fetch_array($cur6);
			//echo $row5[status]."_".$row6[status]."<br>";
			if($nums == 0)
			{
				if($row6[status]=="�h��")
					$row5[status] = $row6[status];
				else
					$row5[status] ="���";
			}
      
/********************************************************************************************************************************/
/*

			$Qs5 = "select status from a11vstd_rec_tea where id = '$row[std_no]' union select status from a11vleave_rec_tea where id = '$row[std_no]'";
			$cur5 = sybase_query($Qs5, $cnx);
			if(!$cur5) {  
				Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
			}
			else{
				$row5 = sybase_fetch_array($cur5);
			}
*/
			// �b�ǥ;��Ǹ�ƥHa11tstd_his_rec����,��ǥͥHa11vstd_rec_tea����
			//if($row5[status]=="���/�h��")// || $row5[status]=="�h��")
			/*if($row5[status]=="���" || $row5[status]=="�h��")
				$Qs3 = "select deptcd now_dept, grade now_grade, class now_class, abbrev from a11vstd_rec_tea vstd, h0rtunit_a_ unit where vstd.id='$row[std_no]' and vstd.deptcd=unit.cd";
			else
				$Qs3 = "select now_dept, now_grade, now_class, abbrev from a11tstd_his_rec tstd, h0rtunit_a_ unit where tstd.std_no='$row[std_no]' and tstd.now_dept=unit.cd and tstd.year='$course_info[year]' and tstd.term='$course_info[term]'";
			*/
			// 952�ק�:�b�ǥ;��Ǹ�ƥHa11tstd_his_rec����,��ǥͥHa11vstd_rec_tea����,�h�ǥͥHa11vleave_rec_tea����,���X�ǥͤ��t�үZ�ŵ����y���
			//98.08.12 �]���׸�ƭק�N year=97 term=2
			if($row5[status]=="���")
				$Qs3 = "select deptcd now_dept, grade now_grade, class now_class, abbrev from a11vstd_rec_tea vstd, h0rtunit_a_ unit where vstd.id='$row[std_no]' and vstd.deptcd=unit.cd";
			else if($row5[status]=="�h��")
				$Qs3 = "select deptcd now_dept, grade now_grade, class now_class, abbrev from a11vleave_rec_tea vstd, h0rtunit_a_ unit where vstd.id='$row[std_no]' and vstd.deptcd=unit.cd";
			else
				//$Qs3 = "select now_dept, now_grade, now_class, abbrev from a11tstd_his_rec tstd, h0rtunit_a_ unit where tstd.std_no='$row[std_no]' and tstd.now_dept=unit.cd and tstd.year='$course_info[year]' and tstd.term='$course_info[term]'";
				$Qs3 = "select now_dept, now_grade, now_class, abbrev from a11tstd_his_rec tstd, h0rtunit_a_ unit where tstd.std_no='$row[std_no]' and tstd.now_dept=unit.cd and tstd.year='97' and tstd.term='2'";
			//
			$cur3 = sybase_query($Qs3, $cnx);
			if(!$cur3) {  
				Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
			}
			if(($row3 = sybase_fetch_array($cur3))!=0){
				$report[$i]['grp'] = $row3['abbrev'].$row3['now_grade'].$row3['now_class'];
				$report[$i]['index'] = $row3['now_dept'].$row3['now_grade'].$row3['now_class'].$report[$i]['std_no'];
			}

			$color = "#E6FFFC";
			//a32t_sel_score_t:�d�߾ǥͦ��Z(�аȳB��db)
			$Qs3 = "select trmgrd from a32t_sel_score_t where std_no = '$row[std_no]' and cour_cd = '$course_info[cid]' and grp = '$course_info[gid]' and year = '$course_info[year]' and term = '$course_info[term]'";
			$cur3 = sybase_query($Qs3, $cnx);
			if(!$cur3) {  
				Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
			}
			//a32v_sel_score_withdraw:�d�߱��ǥͦW��
			$Qs4 = "select std_no from a32v_sel_score_withdraw where std_no = '$row[std_no]' and cour_cd = '$course_info[cid]' and grp = '$course_info[gid]' and year = '$course_info[year]' and term = '$course_info[term]'";
			$cur4 = sybase_query($Qs4, $cnx);
			if(!$cur4) {  
				Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
			}
						
			if(($row3 = sybase_fetch_array($cur3))!=0){
				$report[$i][color] = "<tr bgcolor = \"#EEEEEE\">";
				$report[$i][check_data] = "<td><div align=\"center\">�w�W��</div></td>";
				$uploaded_count++;
				 //�Y���ή�h��ܬ��r by intree
                                $grade_color=is_fail($report[$i][std_no],$row3[trmgrd],$deptcd);
                                $report[$i][score_data] = "<td><div align=\"center\"><font color='$grade_color'>$row3[trmgrd]</font></div></td>";
			}
			else if(($row4 = sybase_fetch_array($cur4))!=0){
				$report[$i][color] = "<tr bgcolor = \"#EEEEEE\">";
				$report[$i][check_data] = "<td><div align=\"center\">���</div></td>";
				$report[$i][score_data] = "<td><div align=\"center\"> -- </div></td>";
			}
			//else if($row5[status]=="���/�h��"){// || $row5[status]=="�h��"){
			else if($row5[status]=="���" || $row5[status]=="�h��"){
				$report[$i][color] = "<tr bgcolor = \"#EEEEEE\">";
				$report[$i][check_data] = "<td><div align=\"center\">$row5[status]</div></td>";
				$report[$i][score_data] = "<td><div align=\"center\"> -- </div></td>";
			}
			else{
			   //�W��Excel�ɮ׷s�W��code
				if($do_upload_excel==1){
					for($k = 0; $k < $course_student_num; $k++ ){
						if(strcasecmp($report[$i]['std_no'],$course_student[$k]['id']) ==0){
						//echo $report[$i]['std_no']." ".$course_student[$k]['id']." ".$course_student[$k]['score']." ".$k."<br>";
							if( !(is_numeric($course_student[$k]['score'])) || $course_student[$k]['score'] == NULL || $course_student[$k]['score'] == "" || $course_student[$k]['score'] =="null") //--20070123jp:�ư��D�Ʀr�����Z��
							{
								$course_student[$k]['score'] = "";
							}
							elseif($course_student[$k]['score'] > 100)
							{
								$course_student[$k]['score'] = 100;
							}
							elseif($course_student[$k]['score'] < 0)
							{
								$course_student[$k]['score'] = 0;
							}
							else
							{
								$course_student[$k]['score'] = round($course_student[$k]['score']);
							}																
							break;
						}
					}	
					
					$report[$i]['grade'] = $course_student[$k]['score'];
				}
				//
				$report[$i][color] = "<tr bgcolor = $color>";
				$report[$i][check_data] = "<td><div align=\"center\"><input type='radio' name='state$row[std_no]' value='1' checked>�O <input type='radio' name='state$row[std_no]' value='0' >�_ </div></td>";

				//by carlyle
				if ($loadtemp != '1') //��sybase�W�����Z
					$report[$i][score_data] = "<td><div align=\"center\"><input type='text' name='grade$row[std_no]' value=\"".$report[$i]['grade']."\" size=2></div></td>";
				else { //�μȦs��Ʈw�̪����Z
					$Q1 = "SELECT a_id FROM user WHERE id = '" . $report[$i]['std_no'] . "'";
					if (!($result = mysql_db_query($DB,$Q1))) {
						show_page("not_access.tpl","��ƮwŪ�����~1!!");
						exit;
					} else
						$row2 = mysql_fetch_array($result);

					$Q2 = "SELECT grade FROM take_exam WHERE exam_id = '-2' and student_id = '" . $row2['a_id']. "'";
					if (!($result = mysql_db_query($DB.$course_id,$Q2))) {
						show_page("not_access.tpl","��ƮwŪ�����~1!!");
						exit;
					} else {
						$row2 = mysql_fetch_array($result);
						$count = mysql_num_rows($result);
					}

					if ($count != 0) //���ǥͦ��Ȧs�����Z
						$report[$i][score_data] = "<td><div align=\"center\"><input type='text' name='grade" . $row['std_no'] . "' value=\"".$row2['grade']."\" size=2></div></td>";
					else //���ǥ͵L�Ȧs�����Z
						$report[$i][score_data] = "<td><div align=\"center\"><input type='text' name='grade" . $row['std_no'] . "' value=\"\" size=2></div></td>";
				}
			}
		}
	}

	//$report = qsort_multiarray ( $report, "index", SORT_ASC );
	for($i=0;$i<sizeof($report);$i++){
		$grp_data = "<td><div align=\"center\">".$report[$i][grp]."</div></td>";
		$std_no_data = "<td><div align=\"center\">".$report[$i][std_no]."</div></td>";
		$name_data = "<td><div align=\"center\">".$report[$i][name]."</div></td>";
		$tpl->assign(DATA, $report[$i][color].$grp_data.$std_no_data.$name_data.$report[$i][score_data].$report[$i][check_data]);
		$tpl->parse(GRADE,".grade");
	}
//echo "Qoo~~".$report[$i][check_data].$grp_data.$std_no_data.$name_data.$report[$i][score_data]."***<br>";
	$tpl->assign( YEAR, $course_info['year']);
	$tpl->assign( TERM, $course_info['term']);
	$tpl->assign( CID, $course_info['cid'] );
	$tpl->assign( GID, $course_info['gid'] );
	$tpl->assign( CREDIT, $course_info["credit"] );
	$tpl->assign( CNAME, $course_info['cname'] );
	$tpl->assign( GNAME, $course_info['gname'] );
	$tpl->assign( TEACHER, $course_info['tch'] );
	$tpl->assign( DATE, (date("Y")-1911).date("/m/d"));
	$tpl->assign( UPLOADED, $uploaded_count);
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
	sybase_close( $cnx);
}
else{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}

function show_page_diff ( $page , $error="", $id="", $return="" ) {
	global $PHPSESSID, $SERVER_NAME,$skinnum;
	include_once("class.FastTemplate.php3");
	if ( is_file( "./templates/$page" ) ) {
		$tpl = new FastTemplate ( "./templates" );
	}
	else {
		$tpl = new FastTemplate ( "../templates" );
	}
	$tpl->define ( array ( body => $page) );
	$tpl->assign ( UID, $id );
	$tpl->assign ( MES, $error);
	$tpl->assign ( TITLE , $error );
	$tpl->assign ( RET, $return );
	$tpl->assign ( PHPSD, $PHPSESSID );
	$tpl->assign ( SERVER, $SERVER_NAME );
	$tpl->assign ( SKINNUM, $skinnum );
	$tpl->parse( BODY, "body");
	$tpl->FastPrint("BODY");
	exit;
}


function Error_Handler( $msg, $cnx ) {  
	echo "$msg \n";
	sybase_close( $cnx); exit();  
}

/*��course_id ���o���ҬO�t�άO��Ҷ}�� , by intree */
function get_deptcd(){
        global $course_id,$DB,$report;
        $Q11 =  "select course.name as cname, course_group.name as gname, course_group.deptcd as deptcd from course, course_group where course.group_id=course_group.a_id and course.a_id = '$course_id'";

        if( $result = mysql_db_query($DB,$Q11) )
        {
                $row1 = mysql_fetch_array($result);
                return $row1['deptcd'];
        }
        else{
                $error = "��ƮwŪ�����~!!";
                return "$error $Q11<br>";
        }

}

/*���ή�����ܦ� , by intree */
function is_fail($std_no , $score , $deptcd){
        //red�����ή� , black���ή�
        $pass = 60;

        $dep = substr($deptcd,3);

        $std_id = substr($std_no,0,1);

        //�t�}����60���ή�
        if($dep==4 || $deptcd==7006 || $deptcd==7306 || $deptcd=='F000' || $deptcd=='V000' || $deptcd=='Z121'){
                if($score < $pass) return 'red';
                else return 'black';
        }
        //��Ҷ}����,�n�ݾǸ��O�j�ǥͩά�s��
        else if($dep==6){
                //�Y�O�j�ǥͫh60���ή�
                if($std_id==4){
                        if($score < $pass) return 'red';
                        else return 'black';
                }
                //�Y�O��s�ͫh70���ή�
                if($std_id==5 || $std_id==6 || $std_id==8){
                        $pass=70;
                        if($score < $pass) return 'red';
                        else return 'black';
                }
        }

}


?>