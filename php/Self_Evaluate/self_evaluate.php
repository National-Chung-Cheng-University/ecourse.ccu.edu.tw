<?php
	/* Id:self_evaluate.php v1.0 2008/2/24 ghost777 Exp.  */
	/* function: �Юv�۵��� */
	include("class.FastTemplate.php3");
	require 'fadmin.php';
	
	if ( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) || ( isset( $course_id ) && ($check = session_check_stu($PHPSESSID)) ) ) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	global $TestType, $classtopic, $questionary,$AbilitiesList,$AbilitiesNumber;	

	//���ogroup_id�P�ҵ{�W��	
	$sql = "select * from course where a_id = '$course_id';";
	$result = mysql_query($sql) or die("Query Error 1");
	$row = mysql_fetch_array($result);
	$group_id = $row['group_id'];
	$course_name = $row['name'];
	
		
	update_mysql_data($group_id, $course_id);
	get_classtopic();
	get_questionary();

	//���o�~�סB�Ǵ�
	$sql = "select * from this_semester;";
	$result = mysql_db_query($DB, $sql) or die("Query Error2");
	$row = mysql_fetch_array($result);
	$this_year = $row['year'];
	$this_term = $row['term'];
	
	//���o�t�ҦW��
	$sql = "select name from course_group where a_id = $group_id;";
	$result = mysql_query($sql) or die("Query Error3");
	$row = mysql_fetch_array($result);
	$group_name = $row['name'];
	
	//���o�ҵ{�s��
	$sql = "select course_no from course where a_id = $course_id;";
	$result = mysql_query($sql) or die("Query Error4");
	$row = mysql_fetch_array($result);
	$course_no = $row['course_no'];
	
	//���o�ϥΪ̦W��
	$sql = "select * from user where id = '$user_id';";
	//echo $user_id;
	$result = mysql_query($sql) or die("Query Error5");
	$row = mysql_fetch_array($result);
	$user_name = $row['name'];
	
	//���o�۵����
	$sql = "select * from IEET_Self_Evaluate 
	where group_id = $group_id and course_id = $course_id order by LearningGoalNo;";
	$result = mysql_query($sql) or die("Query Error6");
	//$row = mysql_fetch_array($result);
	//$learning_goal = $row['LearningGoal'];
	
	//���U�ק� ���o�ק���
	$k = 0;
	$avg_tmp = array();
	if(isset($_POST['submit_edit'])){
		if(isset($_POST['AvS'])){
			foreach($_POST['AvS'] as $s){
				$avg_tmp[$k] = $s;
				$k++;
			}
		}
	}

	$tpl = new FastTemplate("./templates");
	
	$tpl->define(array(main => "display.tpl"));
	
	$tpl->assign(GROUP, $group_name);
	$tpl->assign(YEAR, $this_year);
	$tpl->assign(TERM, $this_term);
	
	$tpl->assign(CNAME, $course_name);
	$tpl->assign(CNO, $course_no);
	$tpl->assign(USER_NAME, $user_name);
	
	$tpl->define_dynamic(GoalList, main);
	$goal_index = 0;

	while($row = mysql_fetch_array($result)){
		$index = $row['LearningGoalNo'];

		//�ݩ󦹪��Ҫ��֤߯�O�~show
		if($AbilitiesList[$index]==1){

			//��IEET_CoreAbilities�d�֤߮߯�O���e
			if($group_id==11)
				$query = "select content from IEET_CoreAbilities where group_id=$group_id and ClassGoal_Index=".ceil($index/3)." and CoreAbilitiesNo=".(($index-1)%3+1);
			else if($group_id==12)
				$query = "select content from IEET_CoreAbilities where group_id=$group_id and ClassGoal_Index=".ceil($index/2)." and CoreAbilitiesNo=".$index;
			$query_result = mysql_query($query) or die("error");
			$content = mysql_fetch_array($query_result);
			
			$tpl->assign(Index, $row['CoreAbilities']);
			$tpl->assign(Content, $content['content']);

			$tpl->assign(ClassTopicList, $classtopic[$index]);

			$tpl->assign(RefList, $TestType[$index]);

			$tpl->assign(StudentEvaluate, $questionary[$index]);

			if( !isset($_POST['submit_edit']) )
				$tpl->assign(AverageScore,$row['AverageScore']);
			else
				$tpl->assign(AverageScore,$avg_tmp[$goal_index]);
			
			$tpl->assign(TS,"TS".$index);
			$tpl->assign(TopScore,$row['TopScore']);
			
			$tpl->assign(PS,"PS".$index);
			$tpl->assign(PassScore,$row['PassScore']);
			
			$tpl->parse(GOALLIST, ".GoalList");

			//�s�U��table �Ω������X
			$page_table .= "
			<tr>
				<td align='center'>{$row['CoreAbilities']}</td>
				<td align='center'>{$content['content']}</td>
				<td align='center'><textarea name=CA rows='4' cols='25' disabled>{$classtopic[$index]}</textarea></td>
				<td align='center'><textarea name=ET rows='4' cols='18' disabled>{$TestType[$index]}</textarea></td>       
				<td align='center'><input type='text' name=ET size='2' value={$questionary[$index]} disabled></td>
			";
			if( !isset($_POST['submit_edit']) )
				$page_table .= "<td align='center'><input type='text' name=AvS[] id=AvS[] size='2' value={$row['AverageScore']} disabled>%</td>";
			else
				$page_table .= "<td align='center'><input type='text' name=AvS[] id=AvS[] size='2' value={$avg_tmp[$goal_index]} disabled>%</td>";
			$page_table .= "
				<td align='center'><input type='text' name=TS size='2' value={$row['TopScore']} disabled>%</td>
				<td align='center'><input type='text' name=PS size='2' value={$row['PassScore']} disabled>%</td>
			</tr>
			";
			$goal_index++;
		}
	}
	//��questionary_r�o��table�����X�ǥͫ�ĳ
	$stu_sug="";
	$sug_count=0;
	$sql = "select * from questionary_r where course_id = $course_id";
	$result = mysql_query($sql) or die("questionary_r query error!");
	while( $row=mysql_fetch_array($result) ){
		if( $row['suggestion'] !='' || $row['suggestion'] !=null ){
			$sug_count++;
			$stu_sug = $stu_sug.$sug_count.". ".$row['suggestion'].chr(13).chr(10);
		}
	}
	if( $stu_sug == '' ){
		$stu_sug="�ثe�L����߱o�P��ĳ";
	}
	$tpl->assign(STUDENT_SUGGEST,$stu_sug);

	//��IEET_Self_Examination�o��table�����X"�оǤϫ�P��ĳ"
	$sql = "select * from IEET_Self_Examination where course_id = $course_id;";
	$result = mysql_query($sql) or die("IEET_Self_Examination query error!");
	$row = mysql_fetch_array($result);
	$sug = $row['Self_Examination'];
	$tpl->assign(SUGGEST,$row['Self_Examination']);
	
	$tpl->parse(BODY, main);
	$tpl->FastPrint(BODY);




	//�s�U���i����
	$page_content = "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=big5'>
<title>�Юv�۵���</title>
</head>

<body background=/images/skin1/bbg.gif>
        <center>
        <font point-size='24pt' color='#000000' >�����ҵ{���q��<br>
        <font point-size='14pt' color='#000000' >({$group_name} {$this_year}�Ǧ~ ��{$this_term}�Ǵ�) </font><br><br>
        <!--<font point-size='14pt' color='#000000' >�ǥ֤ͮ߯�O���q�H�����O�_�F�����t�Ш|�ؼбШ|�ؼ�</font><br>-->        
        ���G���½Ҧ۵������ǲߥؼХѱ½Ҥj�����^���A�Y�L��ƽХ��s��½Ҥj�������ؤu�{�{�Ү榡�C
        <br><br>
        <font point-size='16pt' color='#000000'>�ҵ{�W�١G{$course_name}</font><br>
        <font point-size='16pt' color='#000000'>�ҵ{�N�X�G{$course_no}</font><br>
        <font point-size='16pt' color='#000000'>���H�G{$user_name}</font><br><br>
        <font point-size='16pt' color='#000000'>�ǥ֤ͮ߯�O���q�H�����O�_�F�����t�Ш|�ؼ�</font>

        <table border=1>
        <tr bgcolor='#6699FF'>
                <td rowspan='2' colspan='2' align='center'><font color='#000000' point-size='16pt'>�ҵ{�����i���֤߯�O</font
></td>
                <td rowspan=2 width=300 align='center'><font color='#000000' point-size='16pt'>�������ҵ{�椸</font></td>
                <td rowspan=2 width=150 align='center'><font color='#000000' point-size='16pt'>���q�覡</font></td>
                <td rowspan=2 colspan=1 align='center'><font color='#000000' point-size='16pt'>�ǥ֤ͮ߯�O�۵�</font></td>

                <th rowspan=1 colspan='3' width=200 align='center'><font color='#000000' point-size='16pt' face='�з���'>�ǥ�
���q����</td>                                                                                                                
        </tr>
        <tr>
                <th><font color='#000000' point-size='16pt'>����%</font></th>
                <th><font color='#000000' point-size='16pt'>�̰�%</font></th>
                <th><font color='#000000' point-size='16pt'>�ή�%</font></th>
        </tr>
	{$page_table}
        </table>
        <br>
        <font color='#ff0000' point-size='16pt'>�ǥͯ�O�۵�����1~5  5���̰�</font><br><br>
        <font color='#000000' point-size='16pt'>�оǤϫ�P��ĳ�G</font><br>
        <font color='#333333' point-size='14pt'>(�Ш̾ڥ��t�ǥ֤ͮ߯�O�A�������ǽҵ{�ǲߥؼХ����n�[�j)</font><br>
        <textarea name='suggest' rows='10' cols='70' disabled>{$sug}</textarea><br><br>
        </center>
</body>
</html>
";

	//��ӭ����g�J�@�Ӻ����ɮ�
	if( !is_dir("../../$course_id/evaluation") )
		mkdir("../../$course_id/evaluation", 0771);

	if ( !is_file("../../$course_id/evaluation/evaluate.html") || isset($_POST['submit_edit']) ){
		if (isset($_POST['submit_edit']) )
			unlink("../../$course_id/evaluation/evaluate.html");
		$fp = fopen("../../$course_id/evaluation/evaluate.html", "w+");
		$content = $page_content;
		$content = str_replace ( "\\\"", "\"", $content );    
		$content = str_replace ( "\\\'", "\'", $content );
		$content = str_replace ( "\\\\", "\\", $content );    
		$content = str_replace ( "\\\?", "\?", $content );
		fwrite( $fp, $content );
		fclose($fp);
	}
	
	echo "<center><a target='_blank' href='../../{$course_id}/evaluation/evaluate.html'>�[�ݭק�L������������</a></center>";

	//function: ��s��Ʈw���
	function update_mysql_data($group_id, $course_id)
	{
		global $DB, $TestType, $AbilitiesNumber;

		if($group_id==11){
			$AbilitiesNumber=11;
			$PassScore=60;
		}
		else if($group_id==12){
			$AbilitiesNumber=8;
			$PassScore=70;
		}

		get_TestType();

		//�R���쥻���
		$sql_delete = "DELETE FROM IEET_Self_Evaluate WHERE group_id='$group_id' And course_id='$course_id'";
		mysql_db_query( $DB, $sql_delete);

		//�s�W���
		for($i=1; $i<=$AbilitiesNumber; $i++){
			$AverageScore = get_AVGScore($TestType[$i]);
			$TopScore = get_TopScore($TestType[$i]);

			if($group_id==11) $CoreAbilities=ceil($i/3).".".(($i-1)%3+1);
			else if($group_id==12) $CoreAbilities="A".$i;

			$sql_insert = "insert into IEET_Self_Evaluate
			(
				group_id,
				course_id,
				LearningGoalNo,
				CoreAbilities,
				TestType,
				AverageScore,
				TopScore,
				PassScore
			)values(
				'$group_id',
				'$course_id',
				'$i',
				'$CoreAbilities',
				'$TestType[$i]',
				'$AverageScore',
				'$TopScore',
				'$PassScore'
			)";
			mysql_db_query( $DB, $sql_insert);
		}
	
		
		$Suggest = $_POST['suggest'];	//���"�оǤϫ�P��ĳ"���
		$sql = "select count(*) from IEET_Self_Examination where course_id = $course_id;";
		$result = mysql_query($sql) or die("IEET_Self_Examination query error.");
		$num = mysql_fetch_row($result);
		//�p�G�@�}�lŪ���������n��s�оǤϫ�P��ĳ
		if(isset($_POST['suggest'])){
			if($num[0] == 0) //�s�W"�оǤϫ�P��ĳ(IEET_Self_Examination)"
			{
				$sql_insert = "insert into IEET_Self_Examination (course_id,Self_Examination) values('$course_id','$Suggest');";
				mysql_query($sql_insert) or die(mysql_error());
			}
			else 	//��s"�оǤϫ�P��ĳ(IEET_Self_Examination)"
			{
				$sql_update = "update IEET_Self_Examination set Self_Examination='$Suggest' where course_id='$course_id';";
				mysql_query($sql_update) or die(mysql_error());
			}
		}

		/*echo $CA1; echo $ET1; echo $AvS1; echo $TS1; echo $PS1;
		echo $CA2; echo $ET2; echo $AvS2; echo $TS2; echo $PS2;
		echo $CA3; echo $ET3; echo $AvS3; echo $TS3; echo $PS3;
		echo $CA4; echo $ET4; echo $AvS4; echo $TS4; echo $PS4;
		echo $CA5; echo $ET5; echo $AvS5; echo $TS5; echo $PS5;
		echo $CA6; echo $ET6; echo $AvS6; echo $TS6; echo $PS6;
		echo $Suggest;*/
	}
	
	function get_TestType()
	{
		global $DB, $TestType, $AbilitiesNumber, $course_id;

		//���o�@�~���֤߯�O
		$SQL_Select = "SELECT name, CoreAbilities FROM homework ORDER BY name ASC";
		if ( !($result = mysql_db_query( $DB.$course_id, $SQL_Select ) ) ) {
			$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
			echo $message;
		}
		$homeworkNumber = mysql_num_rows( $result);

		for($i=1; $i<=$homeworkNumber; $i++){ //row
			$row = mysql_fetch_array($result);

			$ClassTopic_CoreAbilitiesTmp = split(",", $row['CoreAbilities']);
			for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++){  //colum
				if(isset($TestType[$ClassTopic_CoreAbilitiesTmp[$j]])) $TestType[$ClassTopic_CoreAbilitiesTmp[$j]] = $TestType[$ClassTopic_CoreAbilitiesTmp[$j]] . "," . $row['name'];
				else $TestType[$ClassTopic_CoreAbilitiesTmp[$j]] = $row['name'];
			}
		}

		//���o���窺�֤߯�O ���C�X�u�W���� �A�C�X��L����
		$SQL_Select = "SELECT name, CoreAbilities FROM exam ORDER BY is_online DESC, a_id ASC";
		if ( !($result = mysql_db_query( $DB.$course_id, $SQL_Select ) ) ) {
			$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
			echo $message;
		}
		$homeworkNumber = mysql_num_rows( $result);

		for($i=1; $i<=$homeworkNumber; $i++){ //row
			$row = mysql_fetch_array($result);

			$ClassTopic_CoreAbilitiesTmp = split(",", $row['CoreAbilities']);
			for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++){  //colum
				if(isset($TestType[$ClassTopic_CoreAbilitiesTmp[$j]])) $TestType[$ClassTopic_CoreAbilitiesTmp[$j]] = $TestType[$ClassTopic_CoreAbilitiesTmp[$j]] . "," . $row['name'];
				else $TestType[$ClassTopic_CoreAbilitiesTmp[$j]] = $row['name'];
			}
		}
	}

	function get_TopScore($TestTypeString){
		global $DB, $course_id;

		//���@�~���Z �s�itemporary table t1
		$SQL_Select =  "create temporary table t1 SELECT sum(h2.grade) as totalgrade, h2.student_id
						FROM homework as h1, handin_homework as h2 WHERE ";
		$TestTypeSplitName = split(",", $TestTypeString);
		for($i=0; $i<count($TestTypeSplitName); $i++){
			if($i!=0) $SQL_Select = $SQL_Select." or h1.name='".$TestTypeSplitName[$i]."'";
			else $SQL_Select = $SQL_Select." (h1.name='".$TestTypeSplitName[$i]."'";
		}
		$SQL_Select = $SQL_Select . ") And h1.a_id=h2.homework_id And h2.grade>-1 group by h2.student_id order by totalgrade desc";
		mysql_db_query( $DB.$course_id, $SQL_Select );

		//���Ҹզ��Z �s�it1�̭�
		$SQL_Select =  "insert into t1 SELECT sum(e2.grade) as totalgrade, e2.student_id
						FROM exam as e1,take_exam as e2 WHERE ";
		$TestTypeSplitName = split(",", $TestTypeString);
		for($i=0; $i<count($TestTypeSplitName); $i++){
			if($i!=0) $SQL_Select = $SQL_Select." or e1.name='".$TestTypeSplitName[$i]."'";
			else $SQL_Select = $SQL_Select." (e1.name='".$TestTypeSplitName[$i]."'";
		}
		$SQL_Select = $SQL_Select . ") And e1.a_id=e2.exam_id And e2.grade>-1 group by e2.student_id order by totalgrade desc";
		mysql_db_query( $DB.$course_id, $SQL_Select );


		//�X��t1�̪�data
		$SQL_Select="SELECT sum(totalgrade) as totalgrade ,student_id FROM t1 group by student_id order by totalgrade desc";
		if ( !($result = mysql_db_query( $DB.$course_id, $SQL_Select ) ) ) {
			$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
			echo $message;
		}
		$resultNumber = mysql_num_rows( $result);

		$TotalCount = count($TestTypeSplitName);
		if($TotalCount==0) $TotalCount=1;
		if($resultNumber!=0){
			$row = mysql_fetch_array($result);
			//�R���Ȧstable
			$SQL_DROP = "DROP temporary table t1";
			if ( !($result = mysql_db_query( $DB.$course_id, $SQL_DROP) ) ) {
				$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
				echo $message;
			}
			return round($row['totalgrade']/$TotalCount);
		}
		else{
			//�R���Ȧstable
			$SQL_DROP = "DROP temporary table t1";
			if ( !($result = mysql_db_query( $DB.$course_id, $SQL_DROP) ) ) {
				$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
				echo $message;
			}
			return 0;
		}
	}

	function get_AVGScore($TestTypeString){
		global $DB, $course_id;

		//���@�~���Z����
		$SQL_Select =  "SELECT percentage, AVG(grade) as avg FROM homework as h1, handin_homework as h2 WHERE ";
		$TestTypeSplitName = split(",", $TestTypeString);
		for($i=0; $i<count($TestTypeSplitName); $i++){
			if($i!=0) $SQL_Select = $SQL_Select." or h1.name='".$TestTypeSplitName[$i]."'";
			else $SQL_Select = $SQL_Select." (h1.name='".$TestTypeSplitName[$i]."'";
		}
		$SQL_Select = $SQL_Select . ") And h1.a_id=h2.homework_id And h2.grade>-1 group by a_id";
		if ( !($result = mysql_db_query( $DB.$course_id, $SQL_Select ) ) ) {
			$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
			echo $message;
		}
//echo $SQL_Select."<br>";
		$resultNumber = mysql_num_rows( $result);
		for($i=0; $i<$resultNumber; $i++){
			$row = mysql_fetch_array($result);
			$ScoreList[$i][0] = $row['percentage'];
			$ScoreList[$i][1] = $row['avg'];
		}

		//���Ҹզ��Z����
		$SQL_Select = "SELECT percentage, AVG(grade) as avg FROM exam as e1, take_exam as e2 WHERE "; 
		$TestTypeSplitName = split(",", $TestTypeString);
		for($i=0; $i<count($TestTypeSplitName); $i++){
			if($i!=0) $SQL_Select = $SQL_Select." or e1.name='".$TestTypeSplitName[$i]."'";
			else $SQL_Select = $SQL_Select." (e1.name='".$TestTypeSplitName[$i]."'";
		}
		$SQL_Select = $SQL_Select . ") And e1.a_id=e2.exam_id And e2.grade>-1 group by a_id";
		if ( !($result = mysql_db_query( $DB.$course_id, $SQL_Select ) ) ) {
			$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
			echo $message;
		}
		$resultNumber = mysql_num_rows( $result);
		$ScoreListNumber=count($ScoreList);
		for($i=$ScoreListNumber; $i< $resultNumber+$ScoreListNumber; $i++){
			$row = mysql_fetch_array($result);
			$ScoreList[$i][0] = $row['percentage'];
			$ScoreList[$i][1] = $row['avg'];
		}

		//�X�֥���
		$ScoreListNumber=count($ScoreList);
		for($i=0; $i<$ScoreListNumber; $i++){
			$TotalScore = $TotalScore + $ScoreList[$i][0]/100*$ScoreList[$i][1];
			$TotalPercentage = $TotalPercentage + $ScoreList[$i][0]/100;
		}
		if($TotalPercentage==0) $TotalPercentage=1;

		return round($TotalScore/$TotalPercentage);
	}
	
	function get_classtopic(){
		global $DB, $classtopic, $course_id, $group_id;

		//�ҵ{�j���̭��Ŀ�X�Ӭ۹������֤߯�O
		$SQL_Select = "SELECT ClassTopicNo, ClassTopic, CoreAbilities FROM IEEE_CourseIntro_ClassTopic WHERE course_id = '$course_id' AND group_id = '$group_id' ORDER BY ClassTopicNo ASC";
		if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
			$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
			echo $message;
		}
		$ClassTopicNumber = mysql_num_rows( $result);
		for($i=1; $i<=$ClassTopicNumber; $i++){ //row
			$row = mysql_fetch_array($result);
			$ClassTopic_CoreAbilitiesTmp = split(",", $row['CoreAbilities']);
			for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++){  //colum
				if(isset($classtopic[$ClassTopic_CoreAbilitiesTmp[$j]])) $classtopic[$ClassTopic_CoreAbilitiesTmp[$j]] = $classtopic[$ClassTopic_CoreAbilitiesTmp[$j]] . "," . $row['ClassTopic'];
				else $classtopic[$ClassTopic_CoreAbilitiesTmp[$j]] = $row['ClassTopic'];
			}
		}
	}
	
	function get_questionary(){
		global $DB, $course_id, $group_id, $questionary, $AbilitiesNumber,$AbilitiesList;

		//initial
		for($i=1; $i<=$AbilitiesNumber; $i++){
			$questionary[$i]=0;		//�O���ݨ���������
			$AbilitiesList[$i]=0;	//�O���֤߯�O�۹������D�ؽs��
		}
		$AbilitiesListNumber=0;
		$CoreList = array();		//�O�����Ψ쪺�֤߯�O�s��
		$QuestionaryList = array(); //�O�����Ψ쪺���D�s��

		//�q�ҵ{�j���o�����h�ֺخ֤߯�O
		$SQL_Select =  "select CoreAbilities from IEEE_CourseIntro_ClassTopic where group_id='$group_id' and course_id='$course_id' order by ClassTopicNo";
		if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
			$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
			echo $message;
		}
		$ClassTopicNumber = mysql_num_rows( $result);
		for($i=1; $i<=$ClassTopicNumber; $i++){ //row
			$row = mysql_fetch_array($result);
			$ClassTopic_CoreAbilitiesTmp = split(",", $row['CoreAbilities']);
			for($j=0; $j<count($ClassTopic_CoreAbilitiesTmp); $j++){  //colum
				$AbilitiesList[$ClassTopic_CoreAbilitiesTmp[$j]] = 1;  //���Ҧ����Ǯ֤߯�O���O�� ����|��אּ�D�ؽs��
			}
		}
		for($i=1; $i<=count($AbilitiesList); $i++){
			if($AbilitiesList[$i]==1) $AbilitiesListNumber++;
		}
		/* ���X�o�ӽҵ{�Ҧ��ǥͪ����� */
		$Q1="SELECT u.id, u.name, q.answer FROM take_course as t, user as u, questionary_r as q WHERE t.course_id='".$course_id."' and t.student_id=u.a_id and u.id=q.student_id and q.course_id='".$course_id."' order by q.student_id";

		if (!($result2 = mysql_db_query($DB,$Q1)))
                        show_page("not_access.tpl","��ƮwŪ�����~!!");
                else {
                        $r_count = mysql_num_rows($result2); //�Ҧ��w��ݨ����ǥ��`��
                }
                /* �v�D�ˬd�U�ӿﶵ���^���H�� */
		$q_count=0; //��M���Ʀs���I
                for($q_index=0;$q_index<$AbilitiesListNumber;$q_index++) {
                        $select_A_count = 0; //�o�D��A���H��
                        $select_B_count = 0; //�o�D��B���H��
                        $select_C_count = 0; //�o�D��C���H��
                        $select_D_count = 0; //�o�D��D���H��
                        $select_E_count = 0; //�o�D��E���H��

                        /* �p��o�D�����ӿﶵ�U���X�ӤH�� */
                        if($r_count!=0){ //�p�G�^���ݨ��H�Ƥ��O0�H�~���έp
                                mysql_data_seek($result2,0); //�Npointer���^�Ĥ@�C
                                for ($sr=0;$sr<$r_count;$sr++) {
                                        $row1 = mysql_fetch_array($result2);
                                        $tmpans = substr($row1['answer'],$q_index,1); //��X�o�Ӿǥͪ��o�@�D������

                                        if ($tmpans == 'A')
                                               $select_A_count++;
                                        else if ($tmpans == 'B')
                                                $select_B_count++;
                                        else if ($tmpans == 'C')
                                                $select_C_count++;
                                        else if ($tmpans == 'D')
                                                $select_D_count++;
                                        else if ($tmpans == 'E')
                                                $select_E_count++;
                                }
				//�ˬd�O���D
				for(;;){
					$q_count++;
					if($AbilitiesList[$q_count]==1){
						$questionary[$q_count]=5*$select_A_count+4*$select_B_count+3*$select_C_count+2*$select_D_count+$select_E_count;
						break;
					}
				}
                        }
		}

		//�p�⥭������p�Ʋ�1��
		for($i=1; $i<=$AbilitiesNumber; $i++){
			if($r_count==0) $r_count=1;
			$questionary[$i] = $questionary[$i] / $r_count;
			$questionary[$i] = round(10*$questionary[$i])/10 ;
		}

	}
?>
