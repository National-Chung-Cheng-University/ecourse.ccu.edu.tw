<?php
	include 'common.php';
?>

<?php
//linsy@20080227 �����S�w��tag
function rmTag($str)
{
    $farr = array(
//�L�o�h�l���ť�
        "/\s+/",
//�L�o <script ���i��ޤJ�c�N���e�δc�N������ܧG����code,�p�G���ݭn���Jflash��,�٥i�H�[�J<object���L�o
        "/<(\/?)(script|i?frame|style|html|img|object|body|title|link|meta|\?|\%)([^>]*?)>/isU",
//�L�ojavascript��on�ƥ�
        "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
//�L�o = �� +
	"/[=|+]/",

  );
  $tarr = array(
        " ",
        "��\\1\\2\\3��",          //�p�G�n�����M�����w�������ҡA�o�̥i�H�d��
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
<title>�ҵ{�оǨt�αK�X�d��</title>
<script language="JavaScript">
function isValidEmail(str) {
   return (str.lastIndexOf(".") > 2) && (str.indexOf("@") > 0) && (str.lastIndexOf(".") > (str.indexOf("@")+1)) && (str.indexOf("@") == str.lastIndexOf("@"));
}
function checkForm(formObj){
	if ( formObj.user.value == ""  || formObj.email.value == ""){
		alert( "�п�J�z�������Ҧr���ME-Mail�H�c�I" );
		return false;
	}
	if(!isValidEmail(formObj.email.value)){
		alert ("E-Mail�����T�I");
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
            <tr><td colspan="2"><h2>�ҵ{�оǨt�αK�X�d��</h2></td></tr>
            <tr> 
              <td width="300">
		<div align="right">�����Ҧr��(�βΤ@�Ҹ�)�G</div></td>
              <td width="200">
		<input type="text" name="user" size="30" maxlength="10" value="<?php echo rmTag($user);  ?>">
              </td>
            </tr>
            <tr> 
              <td><div align="right">E-Mail�G</div></td>
              <td><input type="text" name="email" size="30" value="<?php echo rmTag($email);  ?>"></td>
            </tr>
            <tr> 
              <td colspan="2" align="center">
                  <input type="submit" name="Submit" value="�T�w">
                &nbsp;&nbsp;
                <input type="button" onclick="ResetForm()" value="�M��"> 
			  </td>
            </tr>    
            <tr>
              <td colspan="2">
              	<font color="#CE0000" size="3"><br>
              <dl><dt>�i�`�N�ƶ��j  
�@				<dt>1. �п�J�z�������Ҧr��(�βΤ@�Ҹ�)�ME-Mail�H�c�A�H�Ѭd�ߡC
				<dt>2. �d�ߵ��G�N�H�ܨt�Τ��z�n�����q�l�H�c�C
 				 <dt>3. <b>���d�߶ȨѱЮv�ϥΡA�ǥͬd�߱K�X�Ц�<a href="http://mis.cc.ccu.edu.tw/academic/lost_passwd.htm">��Ҩt�αK�X�d��</a></b>
 				 <dt>5. �p�����ðݡG<br>
�@�@�@���Ь��оǲ�(����:11204��11209)
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
//if ( $user<>'' && $email <> '' && strlen($user)< 11 && !preg_match("/^[-]/",$user) )//���b�G����b��������
if ( $user<>'' && $email <> '' && strlen($user)< 11)//���b�G����b��������
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
			$MailSubject="�ҵ{�оǨt�αK�X�d��";
			$MailMsg="�˷R��".$rows["name"];
			if ($rows["authorization"]=='3')
				$MailMsg.=" �P�Ǳz�n�G\n";
			else
				$MailMsg.=" �Ѯv�z�n�G\n";
			$MailMsg.="�z��".date("Y-m-d")."�d�߽ҵ{�оǨt�αK�X\n\n";
			$MailMsg.="�z���K�X���G".passwd_decrypt($rows["pass"])."(�Ъ`�N�K�X�����j�p�g)\n\n";
			$MailMsg.="�Y��������D�A���p���аȳB�P��(����:11204-���ɼb�A11209-��év)";

			$MailHeader="�ҵ{�оǨt�αK�X�d��";
			mail($MailTo,$MailSubject,$MailMsg,$MailHeader);
			echo '<script language="JavaScript">	alert("�d�ߦ��\�A�t�Τw�H�e�K�X�q����z���q�l�l��H�c");</script>';
		}
		else {
			echo '<script language="JavaScript">	alert("�d�ߥ��ѡA�i���]��:\n\n�z�ҿ�J��E-Mail�H�c�P�ҵ{�оǨt�Τ�������\n\n���p���оǲխt�d�P��(����:11204-���ɼb,11209-��év)");</script>';
		}		
	}
	else {
		echo '<script language="JavaScript">	alert("�ҵ{�оǨt�Τ��S���z���b���I");</script>';
	}
	
}
?>
</body>
</html>
