<?php
        require 'fadmin.php';
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
?>
