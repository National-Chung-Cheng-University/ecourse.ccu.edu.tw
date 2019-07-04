<?php
	include 'common.php';
?>

<?php
//linsy@20080227 移除特定的tag
function rmTag($str)
{
    $farr = array(
//過濾多餘的空白
        "/\s+/",
//過濾 <script 等可能引入惡意內容或惡意改變顯示佈局的code,如果不需要插入flash等,還可以加入<object的過濾
        "/<(\/?)(script|i?frame|style|html|img|object|body|title|link|meta|\?|\%)([^>]*?)>/isU",
//過濾javascript的on事件
        "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
//過濾 = 或 +
	"/[=|+]/",

  );
  $tarr = array(
        " ",
        "＜\\1\\2\\3＞",          //如果要直接清除不安全的標籤，這裡可以留空
        "\\1\\2",
  );

  $str = preg_replace( $farr,$tarr,$str);
  return $str;
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<style type="text/css">
input {
	BORDER-RIGHT: #666666 1px solid; BORDER-TOP: #666666 1px solid; FONT-SIZE: 9pt; 
	BORDER-LEFT: #666666 1px solid; COLOR: rgb(0,0,0); BORDER-BOTTOM: #666666 1px solid; FONT-FAMILY: Verdana,Arial
}
td{
	color:#283F64;
	background-attachment: fixed;
	background-repeat: no-repeat;
	background-position: center center;
}
</style>
<title>課程教學系統密碼查詢</title>
<script language="JavaScript">
function isValidEmail(str) {
   return (str.lastIndexOf(".") > 2) && (str.indexOf("@") > 0) && (str.lastIndexOf(".") > (str.indexOf("@")+1)) && (str.indexOf("@") == str.lastIndexOf("@"));
}
function checkForm(formObj){
	if ( formObj.user.value == ""  || formObj.email.value == ""){
		alert( "請輸入您的身分證字號和E-Mail信箱！" );
		return false;
	}
	if(!isValidEmail(formObj.email.value)){
		alert ("E-Mail不正確！");
		return false;
	}		
		return true;
}
function ResetForm(){
	document.forms[0].user.value = "";
	document.forms[0].email.value = "";
}
</script>
</head>
<body>
<div align="center">
  <p>&nbsp;</p>
  <form action="query_pwd.php" method="post" name="self_form" onSubmit="return checkForm(this);">
    <table width="500" border="0" align="center" cellpadding="2" cellspacing="2">
            <tr><td colspan="2"><h2>課程教學系統密碼查詢</h2></td></tr>
            <tr> 
              <td width="300">
		<div align="right">身分證字號(或統一證號)：</div></td>
              <td width="200">
		<input type="text" name="user" size="30" maxlength="10" value="<?php echo rmTag($user);  ?>">
              </td>
            </tr>
            <tr> 
              <td><div align="right">E-Mail：</div></td>
              <td><input type="text" name="email" size="30" value="<?php echo rmTag($email);  ?>"></td>
            </tr>
            <tr> 
              <td colspan="2" align="center">
                  <input type="submit" name="Submit" value="確定">
                &nbsp;&nbsp;
                <input type="button" onclick="ResetForm()" value="清除"> 
			  </td>
            </tr>    
            <tr>
              <td colspan="2">
              	<font color="#CE0000" size="3"><br>
              <dl><dt>【注意事項】  
　				<dt>1. 請輸入您的身分證字號(或統一證號)和E-Mail信箱，以供查詢。
				<dt>2. 查詢結果將寄至系統中您登錄的電子信箱。
 				 <dt>3. <b>本查詢僅供教師使用，學生查詢密碼請至<a href="http://mis.cc.ccu.edu.tw/academic/lost_passwd.htm">選課系統密碼查詢</a></b>
 				 <dt>5. 如仍有疑問：<br>
　　　◎請洽教學組(分機:11204或11209)
              	</font>
            </dl>
              </td>
            </tr>
          </table>

  </form>
  <p>&nbsp;</p>
</div>
<?php
$user=trim($user);
$email=trim($email);
//if ( $user<>'' && $email <> '' && strlen($user)< 11 && !preg_match("/^[-]/",$user) )//防駭：限制帳號的長度
if ( $user<>'' && $email <> '' && strlen($user)< 11)//防駭：限制帳號的長度
{	
	($link = mysql_pconnect("$DB_SERVER","$DB_LOGIN","$DB_PASSWORD")) or
		die('Not connected : ' . mysql_error());
	($db_selected = mysql_select_db("study", $link)) or
		die ('db error: ' . mysql_error());
		
	$q0="select name, pass, email, authorization from user where id='$user'";
	$rs0=mysql_query($q0,$link);
	
	if($rows=mysql_fetch_array($rs0)) {
		if(trim($rows["email"]) == $email ) {
			$MailTo=$rows["email"];
			$MailSubject="課程教學系統密碼查詢";
			$MailMsg="親愛的".$rows["name"];
			if ($rows["authorization"]=='3')
				$MailMsg.=" 同學您好：\n";
			else
				$MailMsg.=" 老師您好：\n";
			$MailMsg.="您於".date("Y-m-d")."查詢課程教學系統密碼\n\n";
			$MailMsg.="您的密碼為：".passwd_decrypt($rows["pass"])."(請注意密碼有分大小寫)\n\n";
			$MailMsg.="若有任何問題，請聯絡教務處同仁(分機:11204-陳玉嬌，11209-賴永宗)";

			$MailHeader="課程教學系統密碼查詢";
			mail($MailTo,$MailSubject,$MailMsg,$MailHeader);
			echo '<script language="JavaScript">	alert("查詢成功，系統已寄送密碼通知到您的電子郵件信箱");</script>';
		}
		else {
			echo '<script language="JavaScript">	alert("查詢失敗，可能原因為:\n\n您所輸入的E-Mail信箱與課程教學系統中的不符\n\n請聯絡教學組負責同仁(分機:11204-陳玉嬌,11209-賴永宗)");</script>';
		}		
	}
	else {
		echo '<script language="JavaScript">	alert("課程教學系統中沒有您的帳號！");</script>';
	}
	
}
?>
</body>
</html>
