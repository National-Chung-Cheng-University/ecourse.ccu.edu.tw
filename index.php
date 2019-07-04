<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>Ecourse課程平台</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
function doSubmit(){
  document.loginform.submit();
}
function doClear(){
  document.loginform.reset();
}

function changeImg(imgName){
	title_img.src=imgName;
}

function check(event){
  if(event.keyCode==13){
    document.loginform.submit();
  }
}

function fu(){
  document.loginform.id.focus();
}

//-->
</script>
<style>
BODY {
BODY {
SCROLLBAR-FACE-COLOR: #fcfcfc;
 SCROLLBAR-ARROW-COLOR: #0090fc;
 SCROLLBAR-DARKSHADOW-COLOR: #90d8fc
}
form{margin:-1px;}
.style5 {font-size: 12px}
</style>
</head>
<body background="images/bk.jpg" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onkeyup="check(event)" onLoad="fu();" >
<center>
<table width="925" height="683" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="195" height="128" align="left" valign="top" background="images/index_01.jpg"><p>&nbsp;</p>
    <p align="center">　　<span class="style5">
	<?php
		include 'php/getYearTerm.php';
		echo getYearTerm('./php/templates/count.tpl');
	?>
    </span></p>
    <p align="center" class="style5">　　　你是第
     <?php
	include("./php/filecnt.func.php");
        kjFileCounter("./php/counter.txt");
	// For SSO modified by yuwan 20110930
	/*if( $_SESSION['verifySso']=="Y"){
		//header('Location: '.SYS_LOGIN_URL);
		header("Location: http://cih.elearning.ccu.edu.tw/php/index_login.php?PHPSESSID=".session_id());
		//include ('/php/index_login.php');
		//echo "id".$_SESSION['sso_personid'];die;
		exit;
	}*/
     ?>
    位使用者
    </p></td>
    <td width="21" align="left" valign="top"><img src="images/index_02.gif" width="21" height="128"></td>
    <td width="708" align="left" valign="top"><img src="images/index_03.jpg" width="707" height="128"></td>
    <td width="1" rowspan="5" align="left" valign="top">&nbsp;</td>
  </tr>
  <tr><form method="post" action="/php/index_login.php" name="loginform">
    <td height="99" colspan="2" rowspan="2" align="left" valign="top"><table width="196" height="99" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="1" colspan="6" align="left" valign="top"><img src="images/21615.jpg" width="216" height="15"></td>
        </tr>
        <tr>
          <td height="16" colspan="6" align="center" valign="top">
	     <!--<form method="post" action="/php/index_login.php"
name="loginform">-->
	      <input type="hidden" name="ver" value="C" />
              <table width="216" border="0" cellspacing="0" cellpadding="0" background="images/index_34.gif" >
                <tr>
                  <td width="91"><img src="images/index_14.gif" width="91" height="18"></td>
                  <td width="104"><input type="password" name="id" size="13" style="border:thin #000000;margin-bottom:-2px"/></td>
                  <td width="21"><img src="images/index_16.gif" width="21" height="18"></td>
                </tr>
                <tr align="left" valign="top">
                  <td height="8" colspan="3"><img src="images/index_17.gif" width="216" height="7"></td>
                </tr>
                <tr>
                  <td height="16"><img src="images/index_18.gif" width="91" height="18"></td>
                  <td><input type="password" name="pass" size="13" style="border:thin #000000;margin:-1px" /></td>
                  <td><img src="images/index_20.jpg" width="20" height="18"></td>
                </tr>
              </table>
            </td>
        </tr>
        <tr>
          <td height="11" colspan="6" align="left" valign="top"><img src="images/index_21.jpg" width="216" height="11"></td>
        </tr>
        <tr>
          <td height="29" colspan="6" align="left" valign="top"> <table width="216" height="29" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="116" height="29" colspan="2" align="left" valign="top">
                <input type="button" name="btn1" value="" style="width:116; height:29; border:thin #000000;background:url(images/index_22.gif)" onClick="doSubmit();">
                <!--<input type="button" name="button" id="button" value="　　　" style="width='116'; height='29';border:0px thin #000000; border:thin; " onClick="doSubmit();"/>-->
				<!--<button style="width='116'; height='29';border:0px thin #000000;" onClick="doSubmit();" >
				<img src="images/index_22.gif" width="116" height="29">
				</button>-->
				</td>
                <td width="79" align="left" valign="top">
                <input type="button" name="btn2" value="" style="width:79; height:29; border:thin #000000;background:url(images/index_23.gif)" onClick="doClear();">
                <!--<input type="button" name="button2" id="button2" value="　　 " style="width='79'; height='29';border:0px thin #000000;border:thin; " onClick="doClear();"/>-->
				<!--<button  style="width='79'; height='29';border:0px thin #000000;" onClick="doClear();" >
				<img src="images/index_23.gif" width="79" height="29">
				</button>-->
				</td>
                <td width="21" align="left" valign="top"><img src="images/index_24.gif" width="21" height="29"></td>
              </tr>
            </table></td>
        </tr>
      </table></td></form>
    <td height="99" align="left" valign="top">
<!--</form>-->
<table width="707" height="99" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="200" height="23" nowrap>
<form name="form1" method="post" action="">
              <select style="width:95px;overflow:hidden;"  name="menu1" onChange="MM_jumpMenu('parent',this,0)">
                <option value="#" selected>使用手冊</option>
                <option value="./user_guide/teachers_manual.htm">教師手冊</option>
                <option value="./user_guide/student_guide_tch.htm">學生手冊</option>
                <option value="./user_guide/weboffice_manual_teacher.htm">網路辦公室教師手冊</option>
                <option value="./user_guide/weboffice_manual_student.htm">網路辦公室學生手冊</option>
              </select><select name="menu2" onChange="MM_jumpMenu('parent',this,0)">
                <option value="#" selected>課程大綱查詢</option>
                <option value="./php/Courses_Admin/show_guest.php?PHPSESSID=0466f8e4b492c9294334e34ad49e1de8">依系所查詢</option>
                <option value="./php/Courses_Admin/guest2.php?PHPSESSID=0466f8e4b492c9294334e34ad49e1de8">依教師查詢</option>
              </select>
            </form></td>
          <td width="507" align="right" valign="top"><img src="images/index_08.gif" width="507" height="23" alt=""></td>
        </tr>
    	<tr><td colspan="2">
	<font color="blue" size="3">請使用 Internet Explorer 7.0 以上版本!!</font><a href="question.htm#ipp" target="_blank"><font color="red" size="3">智慧財產權常見Q&A</font></a>
        </td></tr>
	<tr align="left" valign="top">
          <td height="76" colspan="2"><img id=title_img src="images/index_13.gif" width="707" height="76"></td>
        </tr>
      </table></td>
  </tr>

  <tr>
    <td rowspan="2" align="left" valign="top">
    <iframe name="I1" width="712" height="402" src="./php/system_news.php" scrolling="yes" frameborder="0" marginheight="0" marginwidth="0"></iframe>
<!--因系統發生異常，暫時關閉進行維護中，造成老師及同學的不便之處，尚祈見諒-->
    </td>
  </tr>

  <tr>
    <td height="402" colspan="2" align="left" valign="top"><img src="images/index_30.gif" width="216" height="402" border="0" usemap="#Map"></td>
  </tr>
  <tr>
    <td colspan="2" align="left" valign="top"><img src="images/index_32.gif" width="216" height="52"></td>
    <!--<td height="0" align="left" valign="top"><img src="images/index_33.gif" width="707" height="52"></td>-->
<td align="center"><font size="2">有任何平台使用上的問題請洽教務處教學組  TEL:(05)2720411轉11209<br/>或洽電算中心  TEL:(05)2720411轉14011</font><br /><font color="red" size="2">建議最佳瀏覽解析度為1024*768</font></td>
  </tr>
</table>
<map name="Map">
  <area shape="rect" coords="143,202,197,219" href="http://miswww1.cc.ccu.edu.tw/academic/gra/lost_passwd.htm" target="_blank">
  <area shape="rect" coords="85,235,161,275" href="./index.php" >
  <area shape="rect" coords="87,294,163,332" href="./guestbook/" onClick="changeImg('images/ms.gif') " target="I1">
  <area shape="rect" coords="90,349,158,388" href="./question.htm" onClick="changeImg('images/qa.gif') " target="I1">
  <area shape="rect" coords="69,89,130,109" href="./php/query_pwd.php"
target="_blank">
  <area shape="rect" coords="143,178,198,199" href="http://miswww1.cc.ccu.edu.tw/academic/lost_passwd.htm"
target="_blank">
</map>
</body>
</html>
