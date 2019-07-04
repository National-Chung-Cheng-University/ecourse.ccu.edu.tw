<?php
	require_once 'fadmin.php';
	include 'xml.php';
	
	/*
	問卷匯出程式 
	written by 大師兄 2006/12/22日
	*/
	//import_questionary($course_id);

	function import_questionary($course_id){
		$location="../../".$course_id."/textbook/questionary.xml";
	        insertQuestionary($course_id,$location,$location);
        	syncQuestionaryUsers($course_id, getNewQid($course_id));
	        createQunestionaryTable($course_id,getNewQid($course_id));
        	insertQtiku($course_id,getNewQid($course_id),$location);
	}

	function insertQuestionary($course_id,$location){
		$xml = new XML($location);
		$name = $xml->get_content("/questionary[1]/name[1]");
		//$abstract = $xml->get_content("/questionary[1]/abstract[1]");
		$is_once = $xml->get_content("/questionary[1]/is_once[1]");
		if($name == "" || $name == NULL){
			die("bad import xml file");
			exit();
		}
		else{
			$sql = "insert into questionary(name,is_once) values ('"
				.$name."','".$is_once."')";
			mysql_db_query("study".$course_id,$sql) or die ("insert questionary時發生錯誤");
			
		}
	}

	function getNewQid($course_id){
		$query = "select a_id from questionary order by a_id desc";
		$rs = mysql_db_query("study".$course_id,$query) or die("Query 新的q_id時發生錯誤");
		$array = mysql_fetch_array($rs);
		return $array['a_id'];

	}
	function syncQuestionaryUsers($course_id,$q_id){
		global $course_year, $course_term;
		$query = "select student_id from take_course where course_id=".$course_id." and year='".$course_year."' and term = '".$course_term."'";
		$rs = mysql_db_query("study",$query) or die("sync questionary使用者答題狀況發生錯誤");
		while($array = mysql_fetch_array($rs)){
			insertUserToTake_questionary($course_id,$array['student_id'],$q_id);
		}
	}

	function insertUserToTake_Questionary($course_id,$student_id,$q_id){
		$query ="insert into take_questionary(q_id,student_id) values ('".$q_id."','".$student_id."')";
		mysql_db_query("study".$course_id,$query) or die("將使用者填入take_questionary發生錯誤");

	}

	function createQunestionaryTable($course_id,$q_id){
		$query ="create table questionary_".$q_id." (a_id int(11) NOT NULL auto_increment,q_id int(11) DEFAULT '0', student_id int(11), mtime timestamp(14),PRIMARY KEY (a_id))";
		mysql_db_query("study".$course_id,$query) or die("create table時發生錯誤");

	}

	function insertQtiku($course_id,$q_id,$location){
		$xml = new XML($location);
		//insert subject and subject child node
		$results = $xml->evaluate ("/questionary/subject","");
		foreach($results as $subject){
			//把問卷subject insert到qtiku (block id = 3)

			$description = $xml->get_content($subject."/description[1]");
			$query = "insert into qtiku (q_id,type,question) values ('".$q_id."','3','".$description."')";
			mysql_db_query("study".$course_id,$query) or die("填入題目時發生錯誤");
			//塞跟他相關的題目
			syncQtiku($course_id,$subject,$location,$q_id);
		}
		/*		
		做完後要把questionary_qid的table欄位建回去,這個步驟不能遺忘
		*/
		$sql = "select a_id from qtiku where q_id = ".$q_id." and type != 3";
		$rs = mysql_db_query("study".$course_id,$sql) or die("選取題目發生錯誤");
		while( $array = mysql_fetch_array($rs)){
			createQuestionaryColumn($course_id,$array['a_id'] ,$q_id);
		}
		
		
	}
	
	
	function createQuestionaryColumn($course_id,$q_id,$a_id){
		$query =  "alter table questionary_".$a_id." ADD COLUMN q".$q_id." text ";
		mysql_db_query("study".$course_id,$query) or die("新增欄位發生錯誤");

	}

	function syncQtiku($course_id,$path,$location,$q_id){
		
		$xml = new XML($location);		
		$query = "select a_id from qtiku order by a_id desc";
		/*
		當你insert到qtiku時,你的a_id一定是最大,所以跟你相關的題目的block_id都要塞這個a_id
		*/
		$rs = mysql_db_query("study".$course_id,$query);
		$array = mysql_fetch_array($rs);
		//目前block = 3 的 a_id
		$a_id = $array['a_id'];
		$results = $xml->evaluate($path."/question","");
		foreach($results as $question){
			$type = $xml->get_content($question."/type[1]");
			$qname = $xml->get_content($question."/qname[1]");
			$selection1 = $xml->get_content($question."/selection1[1]");
			$selection2 = $xml->get_content($question."/selection2[1]");
			$selection3 = $xml->get_content($question."/selection3[1]");
			$selection4 = $xml->get_content($question."/selection4[1]");
			$selection5 = $xml->get_content($question."/selection5[1]");
			$ismultiple = $xml->get_content($question."/ismultiple[1]");
			$query = "insert into qtiku (q_id,type,question,block_id,".
				 "selection1,selection2,selection3,selection4,".
				 "selection5,ismultiple) values".
				 "('".$q_id."','".$type."','".$qname."','".$a_id."','".
				  $selection1."','".$selection2."','".
				  $selection3."','".$selection4."','".$selection5."','".
				  $ismultiple."')";

			mysql_db_query("study".$course_id,$query) or die("insert qtiku error!!");
		}
		
		
	}

?>
