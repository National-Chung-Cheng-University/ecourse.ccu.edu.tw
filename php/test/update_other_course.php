<?php
        require 'fadmin.php';
        global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                $error = "��Ʈw�s�����~!!";
                return $error;
        }
  $time = date("Y-m-d");
  /*if( $time >= '2010-09-20' and $time <= '2010-10-31')
  {
    echo "��s�ջڿ�ץͿ�׸��";
    $Q1 = "insert into temp_takecourse select st_id, course_no from temp_other_course";
    if( !($result1 = mysql_db_query($DB, $Q1)) ){
        $error = "��Ʈw��s���~!! $Q1<br>";
        return $error;
    }
  }
  else
  {
    echo "�W�L��s�ɴ�,����s  <br>";
  }*/
  echo "��s���\!!<br>";
?>
