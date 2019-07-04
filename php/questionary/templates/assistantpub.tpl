<HTML><title>期末問卷發佈設定</title>
<BODY background="/images/skin1/bbg.gif">
<p>
<!--<img src="/images/img/a332.gif"> -->
</p>
<center>
<BR><BR>
<font color="#FF0000">MESSAGE</font>
<table border=1 bordercolor=#9FAE9D><tr><td>
<Table border=0>
<tr bgcolor="#4d6eb2"><th><font color="#FFFFFF">現在狀態</font></th></tr>
<tr bgcolor="#D0DFE3"><td><center>STATUS</center></td></tr>
</Table>
</Table>
<br>
<form method=POST action=assistantpub.php>
<table width="545" border=1 bordercolor=#9FAE9D>
  <tr><td width="541">
<Table border=0>
<tr bgcolor="#4d6eb2" >
  <th colspan="2"><font color="#FFFFFF">系所單位</font></th>
  <th width="352"><font color="#FFFFFF">開始時間</font></th>
</tr>
<tr bgcolor="#D0DFE3">
  <td height="24" colspan="2"><div align="center">QNAME</div></td>
  <td><div align="center">
  <select name=sel_year size=1>
    <!-- BEGIN DYNAMIC BLOCK: yy -->
    <option value=YEAV>YEAR</option>
    <!-- END DYNAMIC BLOCK: yy -->
  </select>
    年
  <select name=sel_month size=1>
    <option value=01 MA01>01</option>
    <option value=02 MA02>02</option>
    <option value=03 MA03>03</option>
    <option value=04 MA04>04</option>
    <option value=05 MA05>05</option>
    <option value=06 MA06>06</option>
    <option value=07 MA07>07</option>
    <option value=08 MA08>08</option>
    <option value=09 MA09>09</option>
    <option value=10 MA10>10</option>
    <option value=11 MA11>11</option>
    <option value=12 MA12>12</option>
  </select>
    月
  <select name=sel_day size=1>
    <option value=01 DA01>01</option>
    <option value=02 DA02>02</option>
    <option value=03 DA03>03</option>
    <option value=04 DA04>04</option>
    <option value=05 DA05>05</option>
    <option value=06 DA06>06</option>
    <option value=07 DA07>07</option>
    <option value=08 DA08>08</option>
    <option value=09 DA09>09</option>
    <option value=10 DA10>10</option>
    <option value=11 DA11>11</option>
    <option value=12 DA12>12</option>
    <option value=13 DA13>13</option>
    <option value=14 DA14>14</option>
    <option value=15 DA15>15</option>
    <option value=16 DA16>16</option>
    <option value=17 DA17>17</option>
    <option value=18 DA18>18</option>
    <option value=19 DA19>19</option>
    <option value=20 DA20>20</option>
    <option value=21 DA21>21</option>
    <option value=22 DA22>22</option>
    <option value=23 DA23>23</option>
    <option value=24 DA24>24</option>
    <option value=25 DA25>25</option>
    <option value=26 DA26>26</option>
    <option value=27 DA27>27</option>
    <option value=28 DA28>28</option>
    <option value=29 DA29>29</option>
    <option value=30 DA30>30</option>
    <option value=31 DA31>31</option>
  </select>
    日
  <select name=sel_hour size=1>
    <option value=00 HB00>00</option>
    <option value=01 HB01>01</option>
    <option value=02 HB02>02</option>
    <option value=03 HB03>03</option>
    <option value=04 HB04>04</option>
    <option value=05 HB05>05</option>
    <option value=06 HB06>06</option>
    <option value=07 HB07>07</option>
    <option value=08 HB08>08</option>
    <option value=09 HB09>09</option>
    <option value=10 HB10>10</option>
    <option value=11 HB11>11</option>
    <option value=12 HB12>12</option>
    <option value=13 HB13>13</option>
    <option value=14 HB14>14</option>
    <option value=15 HB15>15</option>
    <option value=16 HB16>16</option>
    <option value=17 HB17>17</option>
    <option value=18 HB18>18</option>
    <option value=19 HB19>19</option>
    <option value=20 HB20>20</option>
    <option value=21 HB21>21</option>
    <option value=22 HB22>22</option>
    <option value=23 HB23>23</option>
  </select>
    時
  <select name=sel_minute size=1>
    <option value=00 MB00>00</option>
    <option value=15 MB15>15</option>
    <option value=30 MB30>30</option>
    <option value=45 MB45>45</option>
  </select>
    分</div></td>
</tr>
<tr bgcolor="#4d6eb2" >
  <th width="86"><font color="#FFFFFF">立即發佈</font></th>
  <th width="93"><font color="#FFFFFF">類型</font></th>
  <th><font color="#FFFFFF">結束時間</font></th></tr>
<tr bgcolor="#D0DFE3">
  <td><div align="center">
    <input type=checkbox name=pub value=1 PUB>
  </div></td>
  <td>
  <div align="center">
		<select name="publictype">
    	  <option value="showname" PUBLICSEL1>記名</option>
      	  <option value="noname" PUBLICSEL2>不記名</option>
		</select>		
  </div>
  </td>
<td><div align="center">
  <select name=ed_year size=1>
    <!-- BEGIN DYNAMIC BLOCK: ye -->
    <option value=YEAEV>YEAED</option>
    <!-- END DYNAMIC BLOCK: ye -->
  </select>
  年
  <select name=ed_month size=1>
    <option value=01 MOE01>01</option>
    <option value=02 MOE02>02</option>
    <option value=03 MOE03>03</option>
    <option value=04 MOE04>04</option>
    <option value=05 MOE05>05</option>
    <option value=06 MOE06>06</option>
    <option value=07 MOE07>07</option>
    <option value=08 MOE08>08</option>
    <option value=09 MOE09>09</option>
    <option value=10 MOE10>10</option>
    <option value=11 MOE11>11</option>
    <option value=12 MOE12>12</option>
  </select>
  月
  <select name=ed_day size=1>
    <option value=01 DE01>01</option>
    <option value=02 DE02>02</option>
    <option value=03 DE03>03</option>
    <option value=04 DE04>04</option>
    <option value=05 DE05>05</option>
    <option value=06 DE06>06</option>
    <option value=07 DE07>07</option>
    <option value=08 DE08>08</option>
    <option value=09 DE09>09</option>
    <option value=10 DE10>10</option>
    <option value=11 DE11>11</option>
    <option value=12 DE12>12</option>
    <option value=13 DE13>13</option>
    <option value=14 DE14>14</option>
    <option value=15 DE15>15</option>
    <option value=16 DE16>16</option>
    <option value=17 DE17>17</option>
    <option value=18 DE18>18</option>
    <option value=19 DE19>19</option>
    <option value=20 DE20>20</option>
    <option value=21 DE21>21</option>
    <option value=22 DE22>22</option>
    <option value=23 DE23>23</option>
    <option value=24 DE24>24</option>
    <option value=25 DE25>25</option>
    <option value=26 DE26>26</option>
    <option value=27 DE27>27</option>
    <option value=28 DE28>28</option>
    <option value=29 DE29>29</option>
    <option value=30 DE30>30</option>
    <option value=31 DE31>31</option>
  </select>
  日</div></td>
</tr>
</Table>
</td></tr></table>
<BR>
<input type=submit name=sure value=確定>
<input type=submit name=sure value=取消發佈>
<input type=hidden name=q_id value=QID>
<input type=hidden name=action value=publish>
</form>
</center>
</BODY>
</HTML>
