<?php
        require 'fadmin.php';
        global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                $error = "��Ʈw�s�����~!!";
                return $error;
        }

        $Q1 = "select * from temp_user where id like 'assistant%' order by id asc";
        if( !($result1 = mysql_db_query($DB, $Q1)) ){
                $error = "��Ʈwquery���~!! $Q1<br>";
                return $error;
        }
        $count=0;
        while(($row = mysql_fetch_array($result1))){
          $password = passwd_decrypt(htmlentities($row["pass"]));
          //�]���Ȧ��������p�߳Q�ݨ�,�ҥH18���remark�_��
          //echo $row[id]."|".$row[name]."|".$password."<br>";
          $count += 1;
        }
        echo "�`�@�B�zuser����: ".$count;
?>
