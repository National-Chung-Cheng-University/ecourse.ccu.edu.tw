<tr>
<td >　</td>
<td>
<p align="left">　<img src="/images/arrow_ltr.gif"><input type="submit" value="組內分享" name="submit" onClick="return check(selected,1);"><input type="submit" value="組間分享" name="submit" onClick="return check(selected,4);"><input type="submit" value="不分享" name="submit" onClick="return check(selected,2);"><input type="submit" value="刪除" name="submit" onClick="return check(selected,3);"><input type="reset" value="清除" name="clear"></p>
</td>
<td >　</td>
</tr>
</table>
</form>
<hr>
新加檔案
<form name=addfile method="POST" action="./source_mag.php" enctype="multipart/form-data">
<input type="hidden" value="GID" name="group_id">
<table border="0" align="center" cellpadding="0" cellspacing="0">
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
<table border = 0 cellpadding="3" cellspacing="1">
<tr>
<td bgcolor="#F0FFEE"> 
<font size="4" color="#0066FF"><b>檔案連結：<input type="radio" name="style" value="url"><input type="text" name="url" size="37" value="URL" onFocus="addfile.style[0].checked=true"></b></font><br>
</td>
</tr>
<tr>
<td bgcolor="#E6FFFC"> 
<font size="4" color="#0066FF"><b>選擇檔案：<input type="radio" name="style" value="upload"></b><INPUT TYPE="FILE" NAME="file" SIZE="30" onFocus="addfile.style[1].checked=true"></font><br>
</td>
</tr>
<tr>
<td bgcolor="#F0FFEE"> 
<font size="4" color="#0066FF"><b>檔案描述：<textarea name="content" rows = 4 cols=38>TEXT</textarea></b></font><br>
</td>
</tr>
<tr>
<td bgcolor="#E6FFFC"> 
<input type="submit" value="送出" name="submit">　　<input type="reset" value="清除" name="Clear"><br><br>
</td>
</tr>
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
</form>
<hr>
新加目錄
<form name=addonline method="POST" action="./source_mag.php" enctype="multipart/form-data">
<input type="hidden" value="GID" name="group_id">
<table border="0" align="center" cellpadding="0" cellspacing="0">
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
<table border = 0 cellpadding="3" cellspacing="1">
<tr>
<td bgcolor="#F0FFEE"> 
<font size="4" color="#0066FF"><b>名稱：<input type="text" name="name" size="37" ></b></font><br>
</td>
</tr>
<tr>
<td bgcolor="#E6FFFC"> 
<input type="submit" value="新增" name="submit">　　<input type="reset" value="清除" name="Clear"><br><br>
</td>
</tr>
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
</form>
<hr>
刪除目錄
<form name=addonline method="POST" action="./source_mag.php">
<input type="hidden" value="GID" name="group_id">
<table border="0" align="center" cellpadding="0" cellspacing="0">
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
<table border = 0 cellpadding="3" cellspacing="1">
<tr>
<td bgcolor="#E6FFFC"> 
BUTTOM
</td>
</tr>
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
</form>
</center>
</body>
</html>