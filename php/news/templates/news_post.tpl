<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
</head>
<br><hr width="95%"><br>

<font color="#FF0000">MEG</font>
<a name="news"></a>
<script language="JavaScript">
function gotoPage()
{
	document.chpage.submit()
}
</script>
<font size="2"><a href="#newst">‧回最新消息‧<br><br></a></font> 
<table border="0" cellspacing="1" cellpadding="5">
<form name=chpage method=POST action=./news.php>
<input type=hidden name=views value="VIEWS">
<tr bgcolor="#000066">
<td><div align="center"><font color="#FFFFFF" size="2">公告類型：<select name="style" onChange="gotoPage();">
<option value="0" selected>一般性</option>
<option value="1" >時限性</option>
<option value="2" >週期性</option>
</select>
</font></div>
HIDDEN1
</td>
</form>
<form method=POST action=./news.php name="post_art">
<td><div align="center"><font color="#FFFFFF" size="2">起始日期：<select name="start_y">
<!-- BEGIN DYNAMIC BLOCK: start_y -->
<option value=SYV>SYD</option>
<!-- END DYNAMIC BLOCK: start_y -->
</select>年
<select name="start_m">
<option value="01" SM01>1</option>
<option value="02" SM02>2</option>
<option value="03" SM03>3</option>
<option value="04" SM04>4</option>
<option value="05" SM05>5</option>
<option value="06" SM06>6</option>
<option value="07" SM07>7</option>
<option value="08" SM08>8</option>
<option value="09" SM09>9</option>
<option value="10" SM10>10</option>
<option value="11" SM11>11</option>
<option value="12" SM12>12</option>
</select>月
<select name="start_d">
<option value="01" SD01>1</option>
<option value="02" SD02>2</option>
<option value="03" SD03>3</option>
<option value="04" SD04>4</option>
<option value="05" SD05>5</option>
<option value="06" SD06>6</option>
<option value="07" SD07>7</option>
<option value="08" SD08>8</option>
<option value="09" SD09>9</option>
<option value="10" SD10>10</option>
<option value="11" SD11>11</option>
<option value="12" SD12>12</option>
<option value="13" SD13>13</option>
<option value="14" SD14>14</option>
<option value="15" SD15>15</option>
<option value="16" SD16>16</option>
<option value="17" SD17>17</option>
<option value="18" SD18>18</option>
<option value="19" SD19>19</option>
<option value="20" SD20>20</option>
<option value="21" SD21>21</option>
<option value="22" SD22>22</option>
<option value="23" SD23>23</option>
<option value="24" SD24>24</option>
<option value="25" SD25>25</option>
<option value="26" SD26>26</option>
<option value="27" SD27>27</option>
<option value="28" SD28>28</option>
<option value="29" SD29>29</option>
<option value="30" SD30>30</option>
<option value="31" SD31>31</option>
</select>日
</font></div>
</td>
<td><div align="center"><font color="#FFFFFF" size="2">重要等級： 
<select name="important">
<option value="2" I2>高</option>
<option value="1" I1>中</option>
<option value="0" I0>低</option>
</select></font></div>
</td>
</tr>
<tr bgcolor="#000066"> 
<td colspan="3"> 
<div align="center"><font color="#FFFFFF" size="2">公告標題：
<input type="text" name="subject" maxlength="100" value="SUB"></font></div>
</td>
</tr>
<tr> 
<td colspan="3"> 
<div align="center"><font size="2"><br>發佈新消息：<br>
<textarea name=news rows=12 cols=55 >NEWS</textarea>
</font></div></td>
</tr>
<tr> 
<td colspan="3"> 
<div align="center"><font size="2">
<input type=hidden name=flag value=1>
<input type=hidden name=end_y value=9999>
<input type=hidden name=end_m value=12>
<input type=hidden name=end_d value=31>
<input type=hidden name=T_y value=0000>
<input type=hidden name=T_m value=00>
<input type=hidden name=T_d value=00>
<input type=hidden name=T_w value=0>
<input type=hidden name=handle value=1>
<input type=hidden name=views value="VIEWS">
HIDDEN2
<input type=submit value=發佈 name="submit" OnClick="return check_post();">
<input type=reset value=清除 name="reset"></font></div>
</td>
</tr>
</form>
</table>
</center>
</body>
</HTML>
