<html>
<title>合作學習環境</title>
<script language="JavaScript">
<!--
function msgwin(message) {
  msg=window.open('','','toolbar=no,directories=no,menubar=no,width=300,height=30');
  msg.document.write('<html><head><title>資料處理中</title></head><body bgcolor="#EFFBF9"><center><h4>'+ message +'</h4></center></body></html>');
}

function msgwin2() {
  msg=window.open('','','toolbar=no,directories=no,menubar=no,width=300,height=30');
  msg.document.write('<html><head><title>資料處理中</title></head><body bgcolor="#EFFBF9"><center><h4>資料處理中, 請稍候..</h4></center></body></html>');
}

function cwin() {
  if ( msg != null ) {
    msg.close();
  }
}
</script>
<frameset cols="170,*">
   <frameset rows="*,180">
     <frameset rows="*,180">
     <frame src="./Tool_menu.php?PHPSESSID=PHPSID" name="menu">
     <frame src="./memo/month.php?PHPSESSID=PHPID" name="mouth">
     </frameset>
   <frame src="./messager/detail.php?PHPSESSID=PHPID" name="contact">
   </frameset>
   <frame src="./info.php?PHPSESSID=PHPSID" name="main">
</frameset>
</html>