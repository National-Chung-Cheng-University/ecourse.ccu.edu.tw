<?php
/***************
 *���ܩҦ��Юv���w�]function_list
 *�N"�s�W�ǥ�", "�ק�ǥ�"���w�]�����
 **************/
require 'fadmin.php';

$m_sql = "SELECT * FROM function_list";
$begin = 29574;//10172;// begin of course id
$end = 29999;//25481;// end of course id
$tmpstr = '';
if ( !($m_link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
                        echo "��Ʈw�s�����~!!";
}
else 
{
	$i=$begin;
	for($i; $i<=$end; $i++){
		if ( !( $result = mysql_db_query( $DB.$i, $m_sql )) ) {
			echo "$i �L��db!!";
		}else{
			while($row = mysql_fetch_array($result) ){
				$u_sql = "UPDATE function_list SET `tsins`='1', `tsmod`='1' WHERE `u_id`='".$row['u_id']."'";
				if(!mysql_db_query( $DB.$i, $u_sql ))
					echo "<font color=red>$i error ".mysql_error().'</font>';
				else
					echo "<font color=blue>$i done. </font>";
			}
		}
	
		if($i%10==0){
			/*
			 echo "$tmpstr<br>";
			 $tmpstr = '';
			*/
			echo '<br>';
		}
	}
}



?>
