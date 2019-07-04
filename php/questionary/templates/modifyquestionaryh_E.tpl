<HTML>
<head>
<script language="JavaScript">
function Change(){
	document.selectop.submit()
}
//--> 
</script>
</head>
<BODY background="/images/img_E/bg.gif">
<p>
<img src="/images/img_E/a332.gif">
</p>
<form method=POST action=modify_test.php>
¡@<input type=submit name=submit value="Add Question">
  <input type=hidden name=exam_id value="EXAMID">
¡@<input type=hidden name=action value="newtestq">
</form>
<form method=POST action=modify_test.php>
¡@DELETE
  <input type=hidden name=exam_id value="EXAMID">
  <input type=hidden name=a_id value="AID">
  <input type=hidden name=action value="deletequestion">
</form>