<?php
/***************
 *���ܩҦ��Юv���w�]function_list
 *�N"�s�W�ǥ�", "�ק�ǥ�"���w�]�����
 **************/
require 'fadmin.php';

$m_sql = "SELECT * FROM function_list";
$begin = 30000;//10172;// begin of course id
$end = 30693;//25481;// end of course id
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
			
				//$u_sql = "ALTER TABLE `function_list` ADD `officehr` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `tein` ,ADD `core` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `officehr`";
				$u_sql = "ALTER TABLE `function_list` ADD `officehr` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `tein`, ".
				             "ADD `core` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `officehr`, ".
				             "ADD `evaluate` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `core`, ".
				             "ADD `warning` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `tgquery`, ".
				             "ADD `reservation` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `discuss`, ".
				             "ADD `recording` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `reservation`, ".
				             "ADD `eroll` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `rollbook`  ";
				if(!mysql_db_query( $DB.$i, $u_sql ))
					echo "<font color=red>$i error ".mysql_error().'</font>';
				else
					echo "<font color=blue>$i done. </font>";
			
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
