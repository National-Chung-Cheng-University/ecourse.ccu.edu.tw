<?
echo "<frameset rows='*,0' cols='*,0'>\r\n";
//echo "<frame src='dis_list.php?PHPSESSID=$PHPSESSID' name='discuss'>\r\n";
echo "<frame src='dis_list.php?PHPSESSID=$PHPSESSID&field=$_GET[field]&sort=$_GET[sort]' name='discuss'>\r\n";
echo "<frame src='' name='log5'>\r\n";
echo "</frameset>";
?>
