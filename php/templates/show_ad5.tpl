<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>教師發展中心管理者功能頁</title>
<style type="text/css">
<!--
.style1 {
	font-size: large;
	font-weight: bold;
}
.style2 {color: #FFFFFF}

body {
	background-image: url(/images/img/bg.gif);
}
-->
</style>
<script language="javascript">
	function ModifyPassword() {
		window.location = './Learner_Profile/chang_pass_admin.php';
	}
</script>
</head>
<body>
<div align="center" class="style1">教師發展中心管理者功能頁</div>
<br/>
<table width="486" border="1" align="center" cellpadding="0" bordercolor="#999999">
  <tr bgcolor="#588ccc">

    <td width="140"><div align="center" class="style2">系統功能</div></td>
    <td width="334"><div align="center" class="style2">使用說明</div></td>
  </tr>
  <tr>
  	<form action="/php/sysnews.php" method="post">
    <td><input name="button223" type="submit" value="發佈系統公告" style="width:140px" /></td>
	</form>
    <td>發佈新的公告。</td>
  </tr>

  <tr>
    <td><input name="button226" type="button" value="修改密碼" style="width:140px" onclick="ModifyPassword();" /></td>
    <td>修改管理者密碼。</td>
  </tr>
</table>
</body>
</html>
