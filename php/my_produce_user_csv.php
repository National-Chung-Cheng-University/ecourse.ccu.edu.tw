<?php
/* 
�o��{���ΨӲ��� ecourse_user.csv
�Ψ��� mmc �P�B�P��s��


*/
require_once 'common.php';
require_once 'fadmin.php';

require_once 'passwd_encryption.php';
require_once 'my_rja_db_lib.php';

// ecourse �v��������컡��
//authorization	char(1)	�ϥΪ��v�� 0:�޲z�� 1:���v 2:�U�� 3:�ǥ� 4:�t�ҧU�� 9:guest 

$Q1 = "select * from user where authorization='1'";
$user_data = query_db_to_array($Q1);
//print_r($user_data);

$write_data = '';
foreach($user_data as $key => $value){


#a trick , �Ҧ��}�C�����������|�ܦ� local variable
#a_id, id, pass, ftppass, authorization, validated, disable, name, nickname, color, forbear, sex, birthday, tel, zip, addr, email, php, photo, job, grade, introduction, interest, skill, experience, note, mtime
	foreach($value as $k => $v) $$k = $v;

	$pass = passwd_decrypt($pass);
		$write_data .=  $a_id .'|'.
			$id .'|'.
			$pass .'|'.
			$authorization .'|'.
			$name .'|'.
			$email ."\n";


}

print_r($write_data);

$csvFile = './ecourse_user.csv';

$res=file_put_contents( $csvFile, $write_data);
//var_dump($res);
die;

$mmcUrl = 'http://mmc.elearning.ccu.edu.tw/my_add_user.php';
$remotePage = file_get_contents($mmcUrl);
print '----------';
print_r($remotePage);
unlink($csvFile);



?>
<?PHP


define('FILE_APPEND', 1);
function file_put_contents($n, $d, $flag = false) {
    $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
    $f = @fopen($n, $mode);
    if ($f === false) {
        return 0;
    } else {
        if (is_array($d)) $d = implode($d);
        $bytes_written = fwrite($f, $d);
        fclose($f);
        return $bytes_written;
    }
}

?>
