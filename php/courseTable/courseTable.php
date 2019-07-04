<?
require 'fadmin.php';
?>
<html>
<body>
<?
function txData($result){
	while( $row = mysql_fetch_array($result)){
		if($row['course_no'] !=''){
			$str = $str.$row['a_id'].",".$row['course_no'].",";
		}
	}
?>
        <form name=txData action='<? echo $_POST['txAddr'] ?>' method='post'>
		<input type='hidden' name='txData' value='<? echo $str ?>'>
	        <input type='hidden' name='Done'   value='1'              >
	</form>
        <script language=javascript>	
                document.txData.submit();
        </script>
<?
}
$Q1 = 'select a_id, course_no from course';
if(!($result = mysql_db_query($DB,$Q1))){
	echo '¸ê®Æ®wÅª¨ú¿ù»~';
}
else{
	txData($result);
}
?>
</body>
</html>

