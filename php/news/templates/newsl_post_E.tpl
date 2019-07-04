<br><hr width="95%"><br>

<font color="#FF0000">MEG</font>
<a name="news"></a>
<script language="JavaScript">
function gotoPage()
{
	document.chpage.submit()
}
</script>
<font size="2"><a href="#newst">¡EBack to Top¡E<br><br></a></font> 
<table border="0" cellspacing="1" cellpadding="5">
<form name=chpage method=POST action=./news.php>
<input type=hidden name=views value="VIEWS">
<tr bgcolor="#000066">
<td><div align="center"><font color="#FFFFFF" size="2">News Style¡G<select name="style" onChange="gotoPage();">
<option value="0" >Generic</option>
<option value="1" selected>Have Limit</option>
<option value="2" >Have Cycle</option>
</select>
</font></div>
HIDDEN1
</td>
</form>
<form method=POST action=./news.php name="post_art">
<td><div align="center"><font color="#FFFFFF" size="2">Begin Date¡G<select name="start_y">
<!-- BEGIN DYNAMIC BLOCK: start_y -->
<option value=SYV>SYD</option>
<!-- END DYNAMIC BLOCK: start_y -->
</select>Year
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
</select>Month
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
</select>Day
</font></div>
</td>
<td><div align="center"><font color="#FFFFFF" size="2">End Date¡G<select name="end_y">
<!-- BEGIN DYNAMIC BLOCK: end_y -->
<option value=EYV>EYD</option>
<!-- END DYNAMIC BLOCK: end_y -->
</select>Year
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
</select>Month
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
</select>Day</font></div>
</td>
</tr>
<tr bgcolor="#000066"> 
<td COLSPAN=2><div align="center"><font color="#FFFFFF" size="2">If Passed¡G 
<select name="handle">
<option value="0" H0>Delete</option>
<option value="1" H1>Keep but Not Show</option>
</select></font></div>
</td>
</tr>
<tr> 
<td><div align="center"><font color="#FFFFFF" size="2">Priority¡G 
<select name="important">
<option value="2" I2>Important</option>
<option value="1" I1>Generic</option>
<option value="0" I0>Lowest</option>
</select></font></div>
</td>
</tr>
<tr bgcolor="#000066"> 
<td colspan="3"> 
<div align="center"><font color="#FFFFFF" size="2">News Subject¡G 
<input type="text" name="subject" maxlength="100" value="SUB"></font></div>
</td>
</tr>
<tr> 
<td colspan="3"> 
<div align="center"><font size="2"><br>News Content¡G<br>
<textarea name=news rows=12 cols=55 >NEWS</textarea>
</font></div></td>
</tr>
<tr> 
<td colspan="3"> 
<div align="center"><font size="2">
<input type=hidden name=T_y value=0000>
<input type=hidden name=T_m value=00>
<input type=hidden name=T_d value=00>
<input type=hidden name=T_w value=0>
<input type=hidden name=flag value=1>
<input type=hidden name=views value="VIEWS">
HIDDEN2
<input type=submit value=Send name="submit" OnClick="return check_post();">
<input type=reset value=Clear name="reset"></font></div>
</td>
</tr>
</form>
</table>
</center>
</body>
</HTML>
