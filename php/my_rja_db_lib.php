<?PHP
/*
	author: rja
	�o��{���O�@�ǧڦۤv�b�Ϊ� db library 
	�ݭn�`�N�쪺�O�A�b�ϥ� query_db_xxx �t�C�� function �ɡA�n�`�N���� global $DB ��
*/

function getAIDFromUserID($user_id ){
	$getUserID ="select a_id from user where id = '$user_id'";
	$a_id = query_db_to_value($getUserID);
	return $a_id;
}

function getCourseNameFromCourserID($courseId ){
	$getUserID ="select name  from course where a_id = $courseId";
	$name = query_db_to_value($getUserID);
	return $name;
}

function flatArray ($arr)
{
	if (empty($arr))return Array();
	$returnArray = Array();
	foreach( $arr as $key => $value ) {
		foreach( $value as $innerKey => $innerValue ) {
			$returnArray[] =  $innerValue ;
		}
	}

	return $returnArray;

}

function getOne ($arr)
{
	foreach( $arr as $key => $value ) {
		foreach( $value as $innerKey => $innerValue ) {
			return  $innerValue ;
		}
	}


}

function query_db_to_array($query) {

	global $DB;
	if ( $result = mysql_db_query( $DB, $query) ) {
		while($row= mysql_fetch_assoc( $result )){
			$row_result[]=$row;
		}

		if ( !isset($row_result)){
			return ;
		}
		else
			return  $row_result;
	}
	else
		echo $_SERVER['PHP_SELF'] . ': '. $query; //$result->getDebugInfo();


	return;

}


function query_db_to_value($query, $thisDB ='') {

	global $DB;
	if (empty($thisDB))
		$thisDB = $DB;
	if ( $result = mysql_db_query( $thisDB, $query) ) {
		$row_result= mysql_fetch_row( $result );
		return  $row_result[0];
	}
	else
		echo $_SERVER['PHP_SELF'] . ': '. $query; //$result->getDebugInfo();


	return;

}


function query_db($query, $thisDB = '') {

	global $DB;
	if (empty($thisDB))
		$thisDB = $DB;

	$result = mysql_db_query( $thisDB, $query);
	return  $_SERVER['PHP_SELF'] . ': '. mysql_error();

}

?>
