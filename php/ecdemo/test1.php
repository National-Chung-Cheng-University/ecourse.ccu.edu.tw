<?php
	require 'fadmin.php';
	include 'logger.php';
	header("Content-Type:text/html;charset=big-5");
  global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
  if ( !($link = mysql_connect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
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
  echo $time;
  if( ( $time >= '01-01' and $time <= '03-31') or ( $time >= '09-01' and $time <= '10-30') )
  {
    echo "��s�ջڿ�ץͿ�׸��<br>";
    
    //�R���ջڿ�ҥ;ǥͿ�ҼȦs���temp_other_course
    $Q9 = "TRUNCATE TABLE temp_other_course";
    if( !($result9 = mysql_db_query($DB, $Q9)) ){
        $error = "��Ʈw��s���~!! $Q9<br>";
        return $error;
    }    
    
    //���o���y�t�ήջڿ�ҥ͸��---�@��͸�Ʈw
		if( !($cnx1 = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx1 );  
		}
		
		$csd1 = @sybase_select_db("academic", $cnx1);	
		//���o�t�Ҹ�T ���ۭq�}�C �᭱�|�ϥ�
		$cur = sybase_query("select count(*) as c_nt from a31vcurriculum_other where year='100' and term='2' ", $cnx1);
		$result=sybase_fetch_array($cur);
		echo $result['c_nt'];
		echo "---";		
		
		$cur1 = sybase_query("select std_no, cour_grp, name, sch_name from a31vcurriculum_other where year='$year' and term='$term' order by std_no", $cnx1);
		if(!$cur1) {  
			echo "�bsybase_exec�����~�o��";  
		}
		$index = 1;
		while($row[$index]=sybase_fetch_array($cur1))
		{
			echo $result1['std_no']."--".$result1['cour_grp']."<br>";
			
			//$s_id = $result1['std_no'];
			//$c_no = $result1['cour_grp'];
			//$name = $result1['name'];
			//$school = $result1['sch_name'];
			//$row[$index] = $result1;
			
			$index++;
		}
		
		for($i=1; $i<$index; $i++)
		{
			$s_id = $row[$i]['std_no'];
			$c_no = $row[$i]['cour_grp'];
			$name = $row[$i]['name'];
			$school = $row[$i]['sch_name'];			
			//$Q1 = "INSERT INTO temp_other_course VALUES( '$s_id', '$c_no', '$name', '$school') ";
			$Q1 = "INSERT INTO temp_other_course(st_id, course_no, name) VALUES( '$s_id', '$c_no', '$name') ";
			echo "$s_id -- $c_no <br>";
			echo $Q1."<br>";
			//�g�J�ջڿ�ҥ;ǥͿ�ҼȦs���temp_other_course
			mysql_select_db($DB, $link);
			$result2 = mysql_query($Q1, $link );
	    /*if( !($result2 = mysql_query($Q1, $link )) ){
	        $error = "��Ʈw��s���~!! $Q1<br>";
	        return $error;
	    }*/
			
		}		
		
  }
  else
  {
    echo "�W�L��s�ɴ�,����s  <br>";
    exit;
  }
  
  echo "��s���\!!<br>";

	function add_user()
	{
    global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
    if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
            $error = "��Ʈw�s�����~!!";
            return $error;
    }

    $Q1 = "select * from temp_user";
    if( !($result1 = mysql_db_query($DB, $Q1)) ){
            $error = "��Ʈwquery���~!! $Q1<br>";
            return $error;
    }
    $count=0;
    while(($row = mysql_fetch_array($result1))){
            $Q2 = "select count(*) as num from user where id = '$row[id]'";
            if( !($result2 = mysql_db_query($DB, $Q2)) ){
                    $error = "��Ʈwquery���~!! $Q2<br>";
                    return $error;
            }
            //$count++;
            $row2 = mysql_fetch_array($result2);
            //echo $row2['num'];
            if( $row2['num'] < 1 ) {
              //echo "No Body<br>";
              $Q3 = "insert into user (id, pass, ftppass, authorization, disable, name, sex, grade) values (\"$row[id]\", \"" . passwd_encrypt($row[id]) . "\", \"" . md5($row[id]) . "\", \"3\", \"0\", \"$row[name]\", \"1\", \" \")";
              if ( !($result3 = mysql_db_query($DB,$Q3)) ){
                 $error = "mysql��Ʈw�g�J���~!!";
                 echo "$error".": $Q3".": $result3<br>";
              }
              $count++;
              echo "�g�J�� $count �� ID: $row[id] <br>";
            }
            else
            {
             echo "$row[id] �w�g�s�b<br>";
            }
    }
    echo "�`�@�g�Juser����: ".$count;	
	}
	
?>
