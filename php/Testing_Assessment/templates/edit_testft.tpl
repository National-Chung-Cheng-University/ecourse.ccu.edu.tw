<script language="JavaScript">
<!--
function Rows(){
	document.rownum.submit()
}
//-->
</script>
<form method=POST action=ACT1 name=rownum>
¡@TITLE¡G<select name=rownum  onChange="Rows();" >
<option value=0 RO0>ROWNUM</option>
<option value=1 RO1>1</option>
<option value=2 RO2>2</option>
<option value=3 RO3>3</option>
<option value=4 RO4>4</option>
</seletc>
<input type=hidden name=action value=ACT2>
<input type=hidden name=exam_id value="EXAMID">
<input type=hidden name=a_id value="AID">
<input type=hidden name=type value="TYPE">
<input type=hidden name=qno value="QNO">
</form>
ENDLINE