<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>教師使用紀錄查詢</title>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
body {
	background-image: url(../../images/img/bg.gif);
}
-->
</style>
</head>

<script language="javascript">
	function c1_click() {
		document.getElementById("c1").checked = true;
		document.getElementById("c2").checked = false;
		document.getElementById("c3").checked = false;
	}

	function c2_click() {
		document.getElementById("c1").checked = false;
		document.getElementById("c2").checked = true;
		document.getElementById("c3").checked = false;
	}
	
	function c3_click() {
		document.getElementById("c1").checked = false;
		document.getElementById("c2").checked = false;
		document.getElementById("c3").checked = true;
	}
</script>

<body>
<br/>
<p align="center"><strong>教師使用紀錄查詢</strong></p>
<form action="/php/Learner_Profile/query_tch_log.php" method="post">
<table align="center" width="340" height="61" border="1">
  <tr>
    <td width="330"><table align="center" width="99%" border="0">
      <tr>
        <td colspan="3" bgcolor="#000000"><span class="style1">搜尋條件</span></td>
      </tr>
      <tr>
        <td><input type="radio" id="c1" name="checkbox1" checked="checked" onclick="c1_click()" /></td>
        <td><div align="right">身份證字號</div></td>
        <td><div align="center">
          <input type="text" name="id" onkeydown="c1_click()" />
        </div></td>
      </tr>
      <tr>
        <td colspan="3"><br/></td>
      </tr>
      <tr>
        <td width="20" height="25" rowspan="2"><input type="radio" id="c2" name="checkbox2" onclick="c2_click()" /></td>
        <td width="96"><div align="right">科目編碼</div></td>
        <td width="197"><div align="center">
          <input type="text" name="course1" onkeydown="c2_click()" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right">班別</div></td>
        <td width="197"><div align="center">
          <input type="text" name="course2" onkeydown="c2_click()" />
        </div></td>
      </tr>
	  <tr>
        <td colspan="3"><br/></td>
      </tr>
      <tr>
        <td><input type="radio" id="c3" name="checkbox3" onclick="c3_click()" /></td>
        <td><div align="right">來源IP</div></td>
        <td><div align="center">
          <input type="text" name="ip" onkeydown="c3_click()" />
        </div></td>
      </tr>
	  <tr>
        <td colspan="3"><br/></td>
      </tr>
	  <tr>
        <td colspan="3"><div align="center">
          <input type="submit" value="搜尋" />
        </div></td>
      </tr>
    </table></td>
  </tr>
</table>
<br/>
<p align="center"><a href="/php/check_admin.php">回系統管理介面</a></p>
</form>
</body>
</html>
