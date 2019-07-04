<?php
/*
   author: rja
   �ΨӦL�X�I�s my_reservation_list�|�Ψ쪺 lib �A�e�X��浥
   ��ӥ[�W�F�]�n�� my_gotomeeting.php �]�i�H�ΡA�b�Ѯv�i�J�줽�ǮɡA�Y�٨S��w���|ĳ�}�l���ɶ��A
   �i�H�L�X����������|ĳ�A�H�άO�_�i�J�ǳƼҦ�

 */
?>
<?PHP

function editTableContent($reservation_meeting = null){
if ($reservation_meeting == null )return;

	$listTable = array();
	foreach($reservation_meeting as $key => $value){
		$listTable[$key] = array();
		$listTable[$key][] =  $value['courseName'];
		$listTable[$key][] =  $value['teacherName'];

		$listTable[$key][] =  $value['title'];
		$listTable[$key][] =  date('Y-m-d h:i a', $value['startTime']);

		if($value['isOnline']) $listTable[$key][] =  '�O';
		else $listTable[$key][] =  '�_';

		$listTable[$key][] =  $value['maxNumAttendee'];

		if($value['recording']) $listTable[$key][] =  '�O';
		else $listTable[$key][] =  '�_';

		$encodeCourseName = urlencode($value['courseName']);
		global $user_id;
		if(isTeacher($user_id)){
			$prepareUrl = "<a href='./my_gotojoinnet.php?action=gotoPrepareModeMeeting&meetingId={$value['meetingId']}&courseName=$encodeCourseName'>�i�J�ǳƽҵ{�Ҧ�</a>";
			$listTable[$key][] =  $prepareUrl;

    $prepareUrl = "<a href='./my_gotojoinnet.php?action=delReservation&meetingId={$value['meetingId']}' onClick=\"return confirm('ĵ�i�G�o�Ӱʧ@�N�|�@�֧R�����p������C\\n\\n�нT�{�O�_�R�����|ĳ�H');\">�Ѱ��w���|ĳ</a>";
                        $listTable[$key][] =  $prepareUrl;


		}

	}
	return $listTable;

}

function printTable($listTable){
global $user_id;

#var_dump($listTable);
	echo '<table border="1">';
	$tableHeader = array( '�ҵ{�W��', '�½ұЮv', '���D', '�w������ɶ�', '�u�W', '�|ĳ�̤j�H��',  '���v');
		if(isTeacher($user_id)){
			$tableHeader[]='�ǳƼҦ�';
			$tableHeader[]='�����w��';
		}
		print putTr(putTh($tableHeader));

		if ($listTable == null ){
			echo '</table>' ;
			return;
		}

		foreach ($listTable as $value){
			print putTr(putTd($value));
		}
		echo '</table>';

	}
	function putTh ($arr) {
		$ret='';
		foreach( $arr as $key => $value )
			$ret .="\n<th>\n\t$value\n</th>";
		return $ret;
	}

	function putTr ($arr) {
		//  foreach( $arr as $key => $value )
		return  "\n<tr>\t$arr\n</tr>\n";
	}

	function putTd ($arr) {
		$ret='';
		foreach( $arr as $key => $value )
			$ret .= "\n<td>$value</td>";
		return $ret;
	}

	?>
