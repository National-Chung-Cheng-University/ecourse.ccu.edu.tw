<?php
//Creat by ghost777 at 2008/03/07
//Show Core Abilities Descrption

global $Content;
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

function CADes($group_id)
{
	global $Content;
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	/*linsy@20140313, ���t�Φ۰ʧ��group_id���֤߯�O��T
		if($group_id==11){
			$Content[0] = "1.1. ��T�u�{������¦���Ѥ��l���P�F�Ѫ���O �C";
			$Content[1] = "1.2. �B�θ�T�u�{�z�פ����Ϊ��ѡA���R�P�ѨM�������D����O �C";
			$Content[2] = "1.3. �b��T�u�{���\�h��줤�A�㦳�ܤ֬Y�@���M�~��O�Ҧp�G�w��B�n��B�h�C��B�t�ΡB�����B�z�׵� �C";
			$Content[3] = "2.1. �㦳��T�u�{��@�޳N�Ψϥέp������U�u�㪺��O �C";
			$Content[4] = "2.2. �㦳�]�p��T�t�ΡB����λs�{����O �C";
			$Content[5] = "2.3. �㦳�u�q���²���P�g�@����O �C";
			$Content[6] = "3.1. ���F�w�������λ�줧�~�A��i�H�N�ۤv���M�~�������Ω�s�����θ�h�����A�i���o�γзs����O �C";
			$Content[7] = "3.2. ��ɩΰѻP�@�ӹζ������@���M�ץ��Ȫ���O�åB�㦳���q�B��ջP�ζ��X�@����O �C";
			$Content[8] = "3.3. �]����T��ާֳt�ܾE����O�A���i�ۧګ���ǲߤ���O �C";
			$Content[9] = "4.1. �㦳���|�d���B�H����i�Ω^�m�믫 �C";
			$Content[10] = "4.2. �㦳�u�{�۲z�B���[��O�B����[�Ϋe¤���� �C";
			return BulidTable();
		}
		else if($group_id==12){
			$Content[0] = "A1.�㦳��T�u�{�P��ǻ�줧�M�~���ѡC";
			$Content[1] = "A2.�㦳�зs��ҡB���D�ѨM�B�W�߬�s����O�C";
			$Content[2] = "A3.�㦳���g���^��M�~�פ��²������O�C";
			$Content[3] = "A4.�㵦���ΰ���M�D��s����O�C";
			$Content[4] = "A5.�㦳���q�B��աB��X�ζi�����ζ��X�@����O�C";
			$Content[5] = "A6.�㦳�ר��ǲ߻P�]����T��ާֳt�ܾE����O�C";
			$Content[6] = "A7.�{�Ѩÿ�`�ǳN�P�u�{�۲z�C";
			$Content[7] = "A8.�����[�ά�ޫe¤���� �C";
			return BulidTable();
		}
	*/


	$sql = "SELECT CA.*, CG.ClassGoalNo  
			FROM `IEET_CoreAbilities` AS CA, `IEET_ClassGoal` AS CG 
			WHERE CA.group_id=$group_id 
			AND CA.ClassGoal_Index = CG.ClassGoal_Index 
			ORDER BY CG.ClassGoalNo, CA.CoreAbilitiesNo
			";
	if(!($result = mysql_db_query($DB, $sql)))
	{
		$message = "'$sql' ��ƮwŪ�����~!!<br>";
		echo $message;
	}
	$index = 0;
	while($row = mysql_fetch_assoc($result))
	{
		$Content[$index] = $row['ClassGoalNo'] . "." . $row['CoreAbilitiesNo'] . ". " . $row['content'];
		$index++;
	}
	return BulidTable();
}

function BulidTable(){
	global $Content;

	$str =  "<table align='center'>";
	foreach($Content as $value){
		$str .= BulidTR(BulidTD($value));
	}
	$str .= "</table>";
	return $str;
}

function BulidTR($value){
	return "<tr>" . $value . "</tr>";
}

function BulidTD($value){
	return "<td><font color=\"#FF0000\">" . $value . "</font></td>";
}

?>

