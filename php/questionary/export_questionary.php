<?php
	/*
	written by ¤j®v¥S

	*/
	require_once 'fadmin.php';


	/*	
	$location="../../".$course_id."/textbook/questionary.xml";
	$file = fopen($location,"w");
	fwrite($file,"<questionary>\n");
	$questionary = dumpQuestionary($q_id,$course_id);
	fwrite($file,$questionary);
	$qtiku = dumpQtiku($q_id,$course_id);
	fwrite($file,$qtiku);
	fwrite($file,"</questionary>\n");
	fclose($file);
	*/
	function export_questionary($course_id,$q_id){
		$location="../../".$course_id."/textbook/questionary.xml";
	        $file = fopen($location,"w");
        	fwrite($file,"<questionary>\n");
        	$questionary = dumpQuestionary($q_id,$course_id);
        	fwrite($file,$questionary);
        	$qtiku = dumpQtiku($q_id,$course_id);
        	fwrite($file,$qtiku);
        	fwrite($file,"</questionary>\n");
        	fclose($file);

	}


	function dumpQuestionary($qid,$course_id){
		$query = "select * from questionary where a_id=".$qid;
		$rs = mysql_db_query("study".$course_id,$query);
		$array = mysql_fetch_array($rs);
		$content = "<name>".$array['name']."</name>\n".
			   "<is_once>".$array['is_once']."</is_once>\n";
			
		return $content;
	}
	function dumpQtiku($qid,$course_id){
		$query = "select * from qtiku where q_id=".$qid. " and  type = 3 order by a_id";
		$content = '';
		$rs = mysql_db_query("study".$course_id,$query);
			while($array = mysql_fetch_array($rs)){
			
				$content=$content."<subject>\n<description>".$array['question']."</description>\n";
				$query = "select * from qtiku where q_id=".$qid." and block_id=".$array['a_id']." and type !=3";
				$rs2 = mysql_db_query("study".$course_id,$query);
				while($array = mysql_fetch_array($rs2)){
					$content = $content."<question>\n";
					$content = $content."<qname>".$array['question']."</qname>\n";
					$content = $content."<type>".$array['type']."</type>\n";
					$content = $content."<selection1>".$array['selection1']."</selection1>\n";
				        $content = $content."<selection2>".$array['selection2']."</selection2>\n";
					$content = $content."<selection3>".$array['selection3']."</selection3>\n";
					$content = $content."<selection4>".$array['selection4']."</selection4>\n";
					$content = $content."<selection5>".$array['selection5']."</selection5>\n";
					$content = $content."<ismultiple>".$array['ismultiple']."</ismultiple>\n";
					$content = $content."</question>\n";
				}
				$content = $content."</subject>\n";

			}		
		return $content;
	}
	/*
	function queryNewQid($course_id){
		$query = "select a_id from questionary order by a_id DESC";
		$rs = mysql_db_query("study".$course_id,$query);
		$array = mysql_fetch_array($rs);
		echo $array['a_id'];
		return $array['a_id'];
	}
	*/

	
?>
