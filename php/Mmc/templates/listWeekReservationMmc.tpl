<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>課表模式</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

{literal}

<script language="JavaScript">

$(document).ready(function(){
   $("#form1").submit(function(){
        var objForm = document.forms['form1'];
        var objLen = objForm.length;
        var selectedNum = 0 ;
        var tempValue = 0 ;
        for (var iCount = 0; iCount < objLen; iCount++)
        {

            if (objForm.elements[iCount].type == "checkbox")
                if (objForm.elements[iCount].checked == true) {
                    if(selectedNum == 0 )
                        tempValue = objForm.elements[iCount].value;
                    else {
                        if((parseInt(tempValue)+1) == parseInt(objForm.elements[iCount].value))
                            tempValue = objForm.elements[iCount].value;
                        else {
                            alert("請選擇連續的核取框");
                            return false;
                        }
                    }
                    selectedNum++;
                }
                else
                    ;
        }
        if(selectedNum > 6) {
            alert("最多只能選取6個連續的核取框");
            return false;
        }


   });

})

function CancelSelected()
{
var objForm = document.forms['form1'];
var objLen = objForm.length;
for (var iCount = 0; iCount < objLen; iCount++)
{

if (objForm.elements[iCount].type == "checkbox")
if (objForm.elements[iCount].checked == true)
objForm.elements[iCount].checked = false;
else
 ;
}
}

</script>

{/literal}

</head>

<body>
    <h3 align=center>會議預約狀態</h3>
    <table class="datatable" border=1 width=100%>
    <tr>
        <td colspan=16>
            在表格中的每一格會顯示該半小時內所預約的連線數。點選數值的連結將可看見該期間內的預約會議清單。 點選日期的連結將可看見當日的預約會議清單。<br><br>
            表格底部有個 "建立會議" 按鈕，選擇您欲建立會議的時間，然後點選此按鈕即可預約會議。每場預約會議最多只能選取 6 個連續的核取框。 <br><br>
            最大預留會議個數：  200<br>
            預留會議的最大連線數：  100<br>
            即時會議的最大連線數：  100<br>
        </td>
    </tr>
    <tr>
        <td colspan=16 bgcolor="#B4E2E2">
            <form action="listWeekReservationMmc.php" method="post">
            <div align="center">
            <select name="startTimeMonth" >
                <option value="01" {if $month[$week] == "01"}selected{/if}>01</option><option value="02" {if $month[$week] == "02"}selected{/if}>02</option>
                <option value="03" {if $month[$week] == "03"}selected{/if}>03</option><option value="04" {if $month[$week] == "04"}selected{/if}>04</option>
                <option value="05" {if $month[$week] == "05"}selected{/if}>05</option><option value="06" {if $month[$week] == "06"}selected{/if}>06</option>
                <option value="07" {if $month[$week] == "07"}selected{/if}>07</option><option value="08" {if $month[$week] == "08"}selected{/if}>08</option>
                <option value="09" {if $month[$week] == "09"}selected{/if}>09</option><option value="10" {if $month[$week] == "10"}selected{/if}>10</option>
                <option value="11" {if $month[$week] == "11"}selected{/if}>11</option><option value="12" {if $month[$week] == "12"}selected{/if}>12</option>
            </select>月
            <select name="startTimeDay" >
                <option value="01" {if $day[$week] == "01"}selected{/if}>01</option><option value="02" {if $day[$week] == "02"}selected{/if}>02</option>
                <option value="03" {if $day[$week] == "03"}selected{/if}>03</option><option value="04" {if $day[$week] == "04"}selected{/if}>04</option>
                <option value="05" {if $day[$week] == "05"}selected{/if}>05</option><option value="06" {if $day[$week] == "06"}selected{/if}>06</option>
                <option value="07" {if $day[$week] == "07"}selected{/if}>07</option><option value="08" {if $day[$week] == "08"}selected{/if}>08</option>
                <option value="09" {if $day[$week] == "09"}selected{/if}>09</option><option value="10" {if $day[$week] == "10"}selected{/if}>10</option>
                <option value="11" {if $day[$week] == "11"}selected{/if}>11</option><option value="12" {if $day[$week] == "12"}selected{/if}>12</option>
                <option value="13" {if $day[$week] == "13"}selected{/if}>13</option><option value="14" {if $day[$week] == "14"}selected{/if}>14</option>
                <option value="15" {if $day[$week] == "15"}selected{/if}>15</option><option value="16" {if $day[$week] == "16"}selected{/if}>16</option>
                <option value="17" {if $day[$week] == "17"}selected{/if}>17</option><option value="18" {if $day[$week] == "18"}selected{/if}>18</option>
                <option value="19" {if $day[$week] == "19"}selected{/if}>19</option><option value="20" {if $day[$week] == "20"}selected{/if}>20</option>
                <option value="21" {if $day[$week] == "21"}selected{/if}>21</option><option value="22" {if $day[$week] == "22"}selected{/if}>22</option>
                <option value="23" {if $day[$week] == "23"}selected{/if}>23</option><option value="24" {if $day[$week] == "24"}selected{/if}>24</option>
                <option value="25" {if $day[$week] == "25"}selected{/if}>25</option><option value="26" {if $day[$week] == "26"}selected{/if}>26</option>
                <option value="27" {if $day[$week] == "27"}selected{/if}>27</option><option value="28" {if $day[$week] == "28"}selected{/if}>28</option>
                <option value="29" {if $day[$week] == "29"}selected{/if}>29</option><option value="30" {if $day[$week] == "30"}selected{/if}>30</option>
                <option value="31" {if $day[$week] == "31"}selected{/if}>31</option>
            </select>日
            <select name="startTimeYear" >
                <option value="2010" {if $year[$week] == "2010"}selected{/if}>2010</option><option value="2011" {if $year[$week] == "2011"}selected{/if}>2011</option>
                <option value="2012" {if $year[$week] == "2012"}selected{/if}>2012</option><option value="2013" {if $year[$week] == "2013"}selected{/if}>2013</option>
                <option value="2014" {if $year[$week] == "2014"}selected{/if}>2014</option><option value="2015" {if $year[$week] == "2015"}selected{/if}>2015</option>
                <option value="2016" {if $year[$week] == "2016"}selected{/if}>2016</option><option value="2017" {if $year[$week] == "2017"}selected{/if}>2017</option>
                <option value="2018" {if $year[$week] == "2018"}selected{/if}>2018</option><option value="2019" {if $year[$week] == "2019"}selected{/if}>2019</option>
                <option value="2020" {if $year[$week] == "2020"}selected{/if}>2020</option><option value="2021" {if $year[$week] == "2021"}selected{/if}>2021</option>
            </select>年
            <input type="submit" value="到" class="btn">
            <a href='listWeekReservationMmc.php'>本週</a></div>
            </form>
        </td>
    </tr>
    <tr>
        <td colspan=16 bgcolor="#B4E2E2"><div align="center">
           <a href='listWeekReservationMmc.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&roll=up'>上週</a> {$month[0]}月 {$day[0]},{$year[0]} - {$month[6]}月 {$day[6]},{$year[6]}<a href='listWeekReservationMmc.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&roll=down'>下週</a>
        </div>
        </td>
    </tr></table>
    <p></p>
    <table class="datatable" width=100% rules="rows" border=1 BORDERCOLOR=brown>
    <tr>
        <th colspan=2>
            <p align=center><a href='listWeekReservationMmc.php?y={$year[$week]}&m={$month[$week]}&d={$day[$week]}'>重新整理</a></p>
        </th>
        <th colspan=2>
            <p align=center>週日 {$day[0]}</p>
        </th>
        <th colspan=2>
            <p align=center>週一 {$day[1]}</p>
        </th>
        <th colspan=2>
            <p align=center>週二 {$day[2]}</p>
        </th>
        <th colspan=2>
            <p align=center>週三 {$day[3]}</p>
        </th>
        <th colspan=2>
            <p align=center>週四 {$day[4]}</p>
        </th>
        <th colspan=2>
            <p align=center>週五 {$day[5]}</p>
        </th>
        <th colspan=2>
            <p align=center>週六 {$day[6]}</p>
        </th>
    </tr>
    <form id="form1" action="reservationMeeting.php" method="post">
    {section name=foo loop=24}
    <tr>
        <td rowspan="2" style="vertical-align:middle;border-right:1px #6699CC dashed">
            {if $smarty.section.foo.iteration == 1 || $smarty.section.foo.iteration == 13}
                <div align="center" valign="middle">12</div>
            {elseif $smarty.section.foo.iteration > 13}
                <div align="center" valign="middle">{$smarty.section.foo.iteration-13}</div>
            {else}
                <div align="center" valign="middle">{$smarty.section.foo.iteration-1}</div>
            {/if}
        </td>
        <td style="border-right:1px #6699CC sloid">
            {if $smarty.section.foo.iteration == 1}
                <div align=center>上午</div>
            {elseif $smarty.section.foo.iteration ==13}
                <div align=center>下午</div>
            {else}
                <div align=center>00</div>
            {/if}
        </td>
        <td><input value="{math equation="(x-1)*2+1" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2-1`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+49" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+47`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+97" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+95`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+145" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+143`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+193" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+191`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+241" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+239`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+289" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+287`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
    </tr>
    <tr>
        <td></td>
        <td><input value="{math equation="(x-1)*2+2" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+50" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+48`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+98" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+96`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+146" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+144`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+194" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+192`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+242" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+240`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
        <td><input value="{math equation="(x-1)*2+290" x=$smarty.section.foo.iteration}" type="checkbox" name="checkedTimeSlot[]"></td>
        <td>{assign var="tempIndex" value=`$smarty.section.foo.iteration*2+288`}<a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&s={$tempIndex}' TARGET="_blank">{$meetingList.$tempIndex}</a></td>
    </tr>
    {/section}
    <tr>
        <th colspan=2>
            <p align=center><a href='listWeekReservationMmc.php?y={$year[$week]}&m={$month[$week]}&d={$day[$week]}'>重新整理</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&w=0' TARGET="_blank">週日 {$day[0]}</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&w=1' TARGET="_blank">週一 {$day[1]}</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&w=2' TARGET="_blank">週二 {$day[2]}</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&w=3' TARGET="_blank">週三 {$day[3]}</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&w=4' TARGET="_blank">週四 {$day[4]}</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&w=5' TARGET="_blank">週五 {$day[5]}</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y={$year[0]}&m={$month[0]}&d={$day[0]}&w=6' TARGET="_blank">週六 {$day[6]}</a></p>
        </th>
    </tr>
    <tr><td colspan=16 style="text-align:center">
        <a href='javascript:CancelSelected()'>取消全選</a>　<input type="submit" value="建立會議" class="btn"></td>
    </tr>
    </table>
    <input type="hidden" name="year" value="{$year[0]}">
    <input type="hidden" name="month" value="{$month[0]}">
    <input type="hidden" name="day" value="{$day[0]}">
    </form>

</body>
</html>

