<HTML>

<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<script>
done=0;
function doSubmit(){
	done=1;
	document.insert_Frame.submit();
}
</script>
</head>

<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">

<p align="center"> <font size="2"><font color="#000000">成績新增</font></font></p>
<center>
<form method="POST" action="./TGInsertFrame1.php" name="insert_Frame" >

<script type="text/javascript">
window.onbeforeunload = function () {
  if(done!=1){
        return '按一下「取消」停留在此頁。';
  }
}
</script>

<table border="0" align="center" cellpadding="0" cellspacing="0" width="800">
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_01.GIF" width="12" height="11"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_02.GIF" width="100%" height="11"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_03.GIF" width="17" height="11"></div>
</td>
</tr>
<tr> 
<td height=10> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="100%"></div>
</td>
<td bgcolor="#CCCCCC"> 
<table border=0 cellspacing=1 cellpadding=3 width="100%" >
<tr bgcolor=#000066> 
<td> 
<div align="center"><font color = "#FFFFFF">姓名</font></div>
</td>
<td> 
<div align="center"><font color = "#FFFFFF">學號</font></div>
</td>
<td> 
<div align="center"><font color = "#FFFFFF">成績</font></div>
</td>
<td bgcolor="#FFFFFF"></td>
<td> 
<div align="center"><font color = "#FFFFFF">姓名</font></div>
</td>
<td> 
<div align="center"><font color = "#FFFFFF">學號</font></div>
</td>
<td> 
<div align="center"><font color = "#FFFFFF">成績</font></div>
</td>
<td bgcolor="#FFFFFF"></td>
<td> 
<div align="center"><font color = "#FFFFFF">姓名</font></div>
</td>
<td> 
<div align="center"><font color = "#FFFFFF">學號</font></div>
</td>
<td> 
<div align="center"><font color = "#FFFFFF">成績</font></div>
</td>
</tr>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR">
<td><div align="center">SNAME1</div></td><td><div align="center">SNO1</div></td><td><div align="center"><input type=text name=GSVAR1 size=10 value="GRADE1"></div></td>
<td bgcolor="#FFFFFF"></td>
SMARK2
<td><div align="center">SNAME2</div></td><td><div align="center">SNO2</div></td><td><div align="center"><input type=text name=GSVAR2 size=10 value="GRADE2"></div></td>
<td bgcolor="#FFFFFF"></td>
SMARK3
<td><div align="center">SNAME3</div></td><td><div align="center">SNO3</div></td><td><div align="center"><input type=text name=GSVAR3 size=10 value="GRADE3"></div></td>
EMARK3
</tr>	        
<!-- END DYNAMIC BLOCK: row --> 

</table>
</td>
<td height=10> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="100%"></div>
</td>
</tr>
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_07.GIF" width="12" height="17"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_08.GIF" width="100%" height="17"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_09.GIF" width="17" height="17"></div>
</td>
</tr>
</table>
<input type=button name=Submit value='確定' onclick="doSubmit();" >   <input type=reset name=Reset value=清除>
<input type=hidden name=exam_id value="EXAMID">
<input type=hidden name=action value="ACT2">
</form>
</center>
</HTML>
