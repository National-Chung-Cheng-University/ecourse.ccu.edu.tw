<?php
        require 'fadmin.php';
        global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
        if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                $error = "��Ʈw�s�����~!!";
                return $error;
        }

        $Q1 = "select a.a_id, a.id, a.disable ,a.pass, a.ftppass from user a , temp_user b where a.id=b.id";
        if( !($result1 = mysql_db_query($DB, $Q1)) ){
                $error = "��Ʈwquery���~!! $Q1<br>";
                return $error;
        }
        $count=0;
        echo passwd_encrypt('9407039');
        /*while(($row = mysql_fetch_array($result1))){
                 echo $row['a_id']."--".$row['id']."--".$row['disable']."--".$row[pass]."<br>";
                //echo $row2['num'];
                if( $row['id'] != "" ) {
                  //echo "No Body<br>";
                  $Q3 = "update user set authorization = '3' where a_id = ".$row['a_id'];
                  if ( !($result3 = mysql_db_query($DB,$Q3)) ){
                     $error = "mysql��Ʈw�g�J���~!!";
                     echo "$error".": $Q3".": $result3<br>";
                  }
                  $count++;
                  echo "�g�J�� $count �� ID: $row[id] <br>";
                }
                else
                {
                 echo "$row[id] ���s�b<br>";
                }
        }*/
        echo "�`�@�g�Juser����: ".$count;
?>
