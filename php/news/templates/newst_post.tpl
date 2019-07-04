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
<option value="0" >一般性</option>
<option value="1" >時限性</option>
<option value="2" selected>週期性</option>
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
<td><div align="center"><font color="#FFFFFF" size="2">結束日期：<select name="end_y">
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
<tr bgcolor="#000066">
<td COLSPAN=3><div align="center"><font color="#FFFFFF" size="2">週期設定： 
<select name="T_y">
<!-- BEGIN DYNAMIC BLOCK: t_y -->
<option value=TYV>TYD</option>
<!-- END DYNAMIC BLOCK: t_y -->
</select>年
<select name="T_m">
<option value="00" TM00>每月</option>
<option value="01" TM01>1</option>
<option value="02" TM02>2</option>
<option value="03" TM03>3</option>
<option value="04" TM04>4</option>
<option value="05" TM05>5</option>
<option value="06" TM06>6</option>
<option value="07" TM07>7</option>
<option value="08" TM08>8</option>
<option value="09" TM09>9</option>
<option value="10" TM10>10</option>
<option value="11" TM11>11</option>
<option value="12" TM12>12</option>
</select>月
<select name="T_d">
<option value="00" TD00>每日</option>
<option value="01" TD01>1</option>
<option value="02" TD02>2</option>
<option value="03" TD03>3</option>
<option value="04" TD04>4</option>
<option value="05" TD05>5</option>
<option value="06" TD06>6</option>
<option value="07" TD07>7</option>
<option value="08" TD08>8</option>
<option value="09" TD09>9</option>
<option value="10" TD10>10</option>
<option value="11" TD11>11</option>
<option value="12" TD12>12</option>
<option value="13" TD13>13</option>
<option value="14" TD14>14</option>
<option value="15" TD15>15</option>
<option value="16" TD16>16</option>
<option value="17" TD17>17</option>
<option value="18" TD18>18</option>
<option value="19" TD19>19</option>
<option value="20" TD20>20</option>
<option value="21" TD21>21</option>
<option value="22" TD22>22</option>
<option value="23" TD23>23</option>
<option value="24" TD24>24</option>
<option value="25" TD25>25</option>
<option value="26" TD26>26</option>
<option value="27" TD27>27</option>
<option value="28" TD28>28</option>
<option value="29" TD29>29</option>
<option value="30" TD30>30</option>
<option value="31" TD31>31</option>
</select>日
<select name="T_w">
<option value="00" TW00>無論星期</option>
<option value="01" TW01>每逢星期一</option>
<option value="02" TW02>每逢星期二</option>
<option value="03" TW03>每逢星期三</option>
<option value="04" TW04>每逢星期四</option>
<option value="05" TW05>每逢星期五</option>
<option value="06" TW06>每逢星期六</option>
<option value="07" TW06>每逢星期日</option>
</select>
</td>
</tr>
<tr bgcolor="#000066">
<td COLSPAN=2><div align="center"><font color="#FFFFFF" size="2">過期處理方式： 
<select name="handle">
<option value="0" H0>刪除</option>
<option value="1" H1>保留但不顯示</option>
</select>
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
<td colspan="3"><div align="center"><font color="#FFFFFF" size="2">公告標題： 
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
