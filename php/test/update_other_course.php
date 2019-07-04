<?php
        require 'fadmin.php';
        global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                $error = "資料庫連結錯誤!!";
                return $error;
        }
  $time = date("Y-m-d");
  /*if( $time >= '2010-09-20' and $time <= '2010-10-31')
  {
    echo "更新校際選修生選修資料";
    $Q1 = "insert into temp_takecourse select st_id, course_no from temp_other_course";
    if( !($result1 = mysql_db_query($DB, $Q1)) ){
        $error = "資料庫更新錯誤!! $Q1<br>";
        return $error;
    }
  }
  else
  {
    echo "超過更新時期,未更新  <br>";
  }*/
  echo "更新成功!!<br>";
?>
