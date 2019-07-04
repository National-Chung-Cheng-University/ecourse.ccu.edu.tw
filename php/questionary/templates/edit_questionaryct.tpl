<script language="JavaScript">
<!--
function Rows(){
	document.rownums.submit()
}
//-->
</script>
<form method=POST action=editor_main.php name=rownums>
¡@TITLE¡G<select name=rownum onChange="Rows();" >
<option value=0 RO0>ROWNUM</option>
<option value=1 RO1>1</option>
<option value=2 RO2>2</option>
<option value=3 RO3>3</option>
<option value=4 RO4>4</option>
<option value=5 RO5>5</option>
</seletc>
<input type=hidden name=q_id value="QUESID">
<input type=hidden name=block_id value="BLOCK_ID">
<input type=hidden name=bno value="BLOCK_NUM">
<input type=hidden name=type value="TYPE">
<input type=hidden name=qno value="QNO">
<input type=hidden name=item value="ITEM">
</form>
ENDLINE