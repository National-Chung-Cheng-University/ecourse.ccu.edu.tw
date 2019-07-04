<html>
<base href="<? echo "http://".$SERVER_NAME."/php/".$basehref."/"; ?>">
<body background="/images/img/bg.gif">
<?
// param : hidden_content (editor_root/chap/sect)
//         basehref

   echo stripslashes($content);
?>
<input type="button" value="Back" onClick="history.go(-1);">
</body>
</html>