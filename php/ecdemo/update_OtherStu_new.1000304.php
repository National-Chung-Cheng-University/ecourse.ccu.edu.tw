<?php
	require 'fadmin.php';
  global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
  if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
          $error = "��Ʈw�s�����~!!";
          return $error;
  }
?>
<?php
	//���o���Ǵ����
	$qs_sem = "SELECT * FROM this_semester";
	if ($result_sem = mysql_db_query($DB, $qs_sem )){
		if(($row_sem = mysql_fetch_array($result_sem))==0){
			$error = "���Ǵ���Ƥ��s�b!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql��ƮwŪ�����~1!!<br>";
		return $error;
	
	}
	$year = $row_sem['year'];
	$term = $row_sem['term'];	
	
	echo "This semester is:".$year."�Ǧ~��".$term."�Ǵ�<br>";
  //--------------------------------------------------------------
  $time = date("m-d");
  if( ( $time >= '01-01' and $time <= '03-31') or ( $time >= '10-01' and $time <= '10-31') )
  {
    echo "��s�ջڿ�ץͿ�׸��<br>";
    
    //�R���ջڿ�ҥ;ǥͿ�ҼȦs���temp_other_course_test
    $Q9 = "TRUNCATE TABLE temp_other_course_test";
    if( !($result9 = mysql_db_query($DB, $Q9)) ){
        $error = "��Ʈw��s���~!! $Q9<br>";
        return $error;
    }    
    
    //���o���y�t�ήջڿ�ҥ͸��
		if( !($cnx1 = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		
		$csd1 = @sybase_select_db("academic", $cnx1);	
		//���o�t�Ҹ�T ���ۭq�}�C �᭱�|�ϥ�
		$cur1 = sybase_query("select std_no, cour_grp, name, sch_name from a31vcurriculum_other where year='$year' and term='$term' order by std_no", $cnx1);
		if(!$cur1) {  
			echo "�bsybase_exec�����~�o��";  
		}
		while($result1=sybase_fetch_array($cur1))
		{
			//echo $result1['std_no']."--".$result1['cour_grp']."<br>";
			$s_id = $result1['std_no'];
			$c_no = $result1['cour_grp'];
			$name = $result1['name'];
			$school = $result1['sch_name'];
			$Q1 = "INSERT INTO temp_other_course_test VALUES( '$s_id', '$c_no', '$name', '$school') ";
			//echo $Q1."<br>";
			//�g�J�ջڿ�ҥ;ǥͿ�ҼȦs���temp_other_course_test
	    if( !($result2 = mysql_db_query($DB, $Q1)) ){
	        $error = "��Ʈw��s���~!! $Q1<br>";
	        return $error;
	    }
			
		}		
		
  }
  else
  {
    echo "�W�L��s�ɴ�,����s  <br>";
    exit;
  }
  
  echo "��s���\!!<br>";

?>

</body>
</html>