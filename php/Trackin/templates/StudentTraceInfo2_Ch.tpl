<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>個人使用紀錄</title>
<style type="text/css">
body {background-color: #EFFBF9; scrollbar-face-color: #4d6eb2; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #DDDDFF; scrollbar-shadow-color: #054878; scrollbar-3dlight-color: black; }
.style1 {color: #FFFFFF; font-weight: bold;}
</style>
</head>
<body>
<img src="/images/img/b62.gif" />

<a name="hw"></a>
<div align="center">學生作業列表</div>
<table width="500" border="1" align="center">
  <tr bgcolor="#000066">
    <td><div align="center" class="style1">名稱</div></td>
    <td><div align="center" class="style1">題目</div></td>
    <td><div align="center" class="style1">答案</div></td>
    <td><div align="center" class="style1">分數</div></td>
  </tr>
  <!-- BEGIN DYNAMIC BLOCK: homework -->
  <tr>
    <td align="center">WORKNAME</td>
    <td align="center">WORKLINK</td>
    <form method="POST" action="../Testing_Assessment/show_allwork.php">
    <td align="center">WORKANSWER</td>
    </form>
    <td align="center">WORKGRADE</td>
  </tr>
  <!-- END DYNAMIC BLOCK: homework -->
</table>

<br/>

<a name="exam"></a>
<div align="center">學生測驗列表</div>
<table width="450" border="1" align="center">
  <tr bgcolor="#000066">
    <td><div align="center" class="style1">名稱</div></td>
    <td><div align="center" class="style1">測驗</div></td>
    <td><div align="center" class="style1">分數</div></td>
  </tr>
  <!-- BEGIN DYNAMIC BLOCK: exam -->
  <tr>
    <td align="center">EXAMNAME</td>
    <td align="center">EXAMLINK</td>
    <td align="center">EXAMGRADE</td>
  </tr>
  <!-- END DYNAMIC BLOCK: exam -->
</table>

</body>
</html>
