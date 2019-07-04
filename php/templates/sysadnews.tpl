<html>
<head>
<title>系統公告</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE type=text/css>
	BODY { SCROLLBAR-FACE-COLOR: #4d6eb2; SCROLLBAR-ARROW-COLOR: #FFFFFF; SCROLLBAR-TRACK-COLOR: #DDDDFF; SCROLLBAR-SHADOW-COLOR: #054878; SCROLLBAR-3DLIGHT-COLOR: black; }
</STYLE> 
</head>
<body background=/images/img/bg.gif>
<center>
<H2>系統公告</H2>
<a href=./check_admin.php>回系統管理介面</a><br>
<table width="98%" border="1" cellspacing="0" cellpadding="0" height="350" bordercolor="#CCCCCC">
<!-- BEGIN DYNAMIC BLOCK: news_list -->
<tr height=25>
<form method=post action=./sysnews.php>
<td bgcolor="#CFDECD" align="left" valign="top">
<font color="#ff0000" size="2">DATE SUJ</font>AID
</td></form>
</tr>
<tr height=25>
<td bgcolor="#C0CFD3" align="left" valign="top">
<font color="FONT" size="2">CONTENT</font>
</td>
</tr>
<!-- END DYNAMIC BLOCK: news_list -->
</table>
<hr>
<font color="#ff0000">MES</font><br>
<Table border=0>
<form method=POST action=./sysnews.php>
<tr>
<td>起始日期：<select name="start_y">
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
</td>
</tr>
<tr>
<td>結束日期：<select name="end_y">
<!-- BEGIN DYNAMIC BLOCK: end_y -->
<option value=EYV>EYD</option>
<!-- END DYNAMIC BLOCK: end_y -->
</select>年 
<select name="end_m">
<option value="01" EM01>1</option>
<option value="02" EM02>2</option>
<option value="03" EM03>3</option>
<option value="04" EM04>4</option>
<option value="05" EM05>5</option>
<option value="06" EM06>6</option>
<option value="07" EM07>7</option>
<option value="08" EM08>8</option>
<option value="09" EM09>9</option>
<option value="10" EM10>10</option>
<option value="11" EM11>11</option>
<option value="12" EM12>12</option>
</select>月 
<select name="end_d">
<option value="01" ED01>1</option>
<option value="02" ED02>2</option>
<option value="03" ED03>3</option>
<option value="04" ED04>4</option>
<option value="05" ED05>5</option>
<option value="06" ED06>6</option>
<option value="07" ED07>7</option>
<option value="08" ED08>8</option>
<option value="09" ED09>9</option>
<option value="10" ED10>10</option>
<option value="11" ED11>11</option>
<option value="12" ED12>12</option>
<option value="13" ED13>13</option>
<option value="14" ED14>14</option>
<option value="15" ED15>15</option>
<option value="16" ED16>16</option>
<option value="17" ED17>17</option>
<option value="18" ED18>18</option>
<option value="19" ED19>19</option>
<option value="20" ED20>20</option>
<option value="21" ED21>21</option>
<option value="22" ED22>22</option>
<option value="23" ED23>23</option>
<option value="24" ED24>24</option>
<option value="25" ED25>25</option>
<option value="26" ED26>26</option>
<option value="27" ED27>27</option>
<option value="28" ED28>28</option>
<option value="29" ED29>29</option>
<option value="30" ED30>30</option>
<option value="31" ED31>31</option>
</select>日
</td>
</tr>
<tr> 
<td>過期處理方式： 
<select name="handle">
<option value="0" H0>刪除</option>
<option value="1" H1>保留</option>
</select>
<input type=checkbox name=nolimit value=1 CHECK>永遠顯示
</td>
</tr>
<tr> 
<td>重要等級： 
<select name="important">
<option value="2" I2>高</option>
<option value="1" I1>中</option>
<option value="0" I0>低</option>
</select>
</td>
</tr>
<tr> 
<td>公告標題： 
<input type="text" name="subject" maxlength="100" value=SUB>
</td>
</tr>
<tr> 
<td>發佈新消息：<br>
<textarea name=news rows=12 cols=55>NEWS
</textarea>
</td>
</tr>
<tr> 
<td>
<input type=hidden name=flag value=1>
<input type=submit value=發佈 name="submit">
<input type=reset value=清除 name="reset">
</td>
</tr>
</form>
</table>
</center>
</body>
</html>
