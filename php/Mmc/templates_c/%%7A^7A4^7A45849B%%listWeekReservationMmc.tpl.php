<?php /* Smarty version 2.6.14, created on 2013-02-09 06:39:45
         compiled from /datacenter/htdocs/php/Mmc/templates/listWeekReservationMmc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', '/datacenter/htdocs/php/Mmc/templates/listWeekReservationMmc.tpl', 189, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>課表模式</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/content.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/table.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

<?php echo '

<script language="JavaScript">

$(document).ready(function(){
   $("#form1").submit(function(){
        var objForm = document.forms[\'form1\'];
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
var objForm = document.forms[\'form1\'];
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

'; ?>


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
                <option value="01" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '01'): ?>selected<?php endif; ?>>01</option><option value="02" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '02'): ?>selected<?php endif; ?>>02</option>
                <option value="03" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '03'): ?>selected<?php endif; ?>>03</option><option value="04" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '04'): ?>selected<?php endif; ?>>04</option>
                <option value="05" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '05'): ?>selected<?php endif; ?>>05</option><option value="06" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '06'): ?>selected<?php endif; ?>>06</option>
                <option value="07" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '07'): ?>selected<?php endif; ?>>07</option><option value="08" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '08'): ?>selected<?php endif; ?>>08</option>
                <option value="09" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '09'): ?>selected<?php endif; ?>>09</option><option value="10" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '10'): ?>selected<?php endif; ?>>10</option>
                <option value="11" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '11'): ?>selected<?php endif; ?>>11</option><option value="12" <?php if ($this->_tpl_vars['month'][$this->_tpl_vars['week']] == '12'): ?>selected<?php endif; ?>>12</option>
            </select>月
            <select name="startTimeDay" >
                <option value="01" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '01'): ?>selected<?php endif; ?>>01</option><option value="02" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '02'): ?>selected<?php endif; ?>>02</option>
                <option value="03" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '03'): ?>selected<?php endif; ?>>03</option><option value="04" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '04'): ?>selected<?php endif; ?>>04</option>
                <option value="05" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '05'): ?>selected<?php endif; ?>>05</option><option value="06" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '06'): ?>selected<?php endif; ?>>06</option>
                <option value="07" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '07'): ?>selected<?php endif; ?>>07</option><option value="08" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '08'): ?>selected<?php endif; ?>>08</option>
                <option value="09" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '09'): ?>selected<?php endif; ?>>09</option><option value="10" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '10'): ?>selected<?php endif; ?>>10</option>
                <option value="11" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '11'): ?>selected<?php endif; ?>>11</option><option value="12" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '12'): ?>selected<?php endif; ?>>12</option>
                <option value="13" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '13'): ?>selected<?php endif; ?>>13</option><option value="14" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '14'): ?>selected<?php endif; ?>>14</option>
                <option value="15" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '15'): ?>selected<?php endif; ?>>15</option><option value="16" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '16'): ?>selected<?php endif; ?>>16</option>
                <option value="17" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '17'): ?>selected<?php endif; ?>>17</option><option value="18" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '18'): ?>selected<?php endif; ?>>18</option>
                <option value="19" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '19'): ?>selected<?php endif; ?>>19</option><option value="20" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '20'): ?>selected<?php endif; ?>>20</option>
                <option value="21" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '21'): ?>selected<?php endif; ?>>21</option><option value="22" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '22'): ?>selected<?php endif; ?>>22</option>
                <option value="23" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '23'): ?>selected<?php endif; ?>>23</option><option value="24" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '24'): ?>selected<?php endif; ?>>24</option>
                <option value="25" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '25'): ?>selected<?php endif; ?>>25</option><option value="26" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '26'): ?>selected<?php endif; ?>>26</option>
                <option value="27" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '27'): ?>selected<?php endif; ?>>27</option><option value="28" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '28'): ?>selected<?php endif; ?>>28</option>
                <option value="29" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '29'): ?>selected<?php endif; ?>>29</option><option value="30" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '30'): ?>selected<?php endif; ?>>30</option>
                <option value="31" <?php if ($this->_tpl_vars['day'][$this->_tpl_vars['week']] == '31'): ?>selected<?php endif; ?>>31</option>
            </select>日
            <select name="startTimeYear" >
                <option value="2010" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2010'): ?>selected<?php endif; ?>>2010</option><option value="2011" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2011'): ?>selected<?php endif; ?>>2011</option>
                <option value="2012" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2012'): ?>selected<?php endif; ?>>2012</option><option value="2013" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2013'): ?>selected<?php endif; ?>>2013</option>
                <option value="2014" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2014'): ?>selected<?php endif; ?>>2014</option><option value="2015" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2015'): ?>selected<?php endif; ?>>2015</option>
                <option value="2016" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2016'): ?>selected<?php endif; ?>>2016</option><option value="2017" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2017'): ?>selected<?php endif; ?>>2017</option>
                <option value="2018" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2018'): ?>selected<?php endif; ?>>2018</option><option value="2019" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2019'): ?>selected<?php endif; ?>>2019</option>
                <option value="2020" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2020'): ?>selected<?php endif; ?>>2020</option><option value="2021" <?php if ($this->_tpl_vars['year'][$this->_tpl_vars['week']] == '2021'): ?>selected<?php endif; ?>>2021</option>
            </select>年
            <input type="submit" value="到" class="btn">
            <a href='listWeekReservationMmc.php'>本週</a></div>
            </form>
        </td>
    </tr>
    <tr>
        <td colspan=16 bgcolor="#B4E2E2"><div align="center">
           <a href='listWeekReservationMmc.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&roll=up'>上週</a> <?php echo $this->_tpl_vars['month'][0]; ?>
月 <?php echo $this->_tpl_vars['day'][0]; ?>
,<?php echo $this->_tpl_vars['year'][0]; ?>
 - <?php echo $this->_tpl_vars['month'][6]; ?>
月 <?php echo $this->_tpl_vars['day'][6]; ?>
,<?php echo $this->_tpl_vars['year'][6]; ?>
<a href='listWeekReservationMmc.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&roll=down'>下週</a>
        </div>
        </td>
    </tr></table>
    <p></p>
    <table class="datatable" width=100% rules="rows" border=1 BORDERCOLOR=brown>
    <tr>
        <th colspan=2>
            <p align=center><a href='listWeekReservationMmc.php?y=<?php echo $this->_tpl_vars['year'][$this->_tpl_vars['week']]; ?>
&m=<?php echo $this->_tpl_vars['month'][$this->_tpl_vars['week']]; ?>
&d=<?php echo $this->_tpl_vars['day'][$this->_tpl_vars['week']]; ?>
'>重新整理</a></p>
        </th>
        <th colspan=2>
            <p align=center>週日 <?php echo $this->_tpl_vars['day'][0]; ?>
</p>
        </th>
        <th colspan=2>
            <p align=center>週一 <?php echo $this->_tpl_vars['day'][1]; ?>
</p>
        </th>
        <th colspan=2>
            <p align=center>週二 <?php echo $this->_tpl_vars['day'][2]; ?>
</p>
        </th>
        <th colspan=2>
            <p align=center>週三 <?php echo $this->_tpl_vars['day'][3]; ?>
</p>
        </th>
        <th colspan=2>
            <p align=center>週四 <?php echo $this->_tpl_vars['day'][4]; ?>
</p>
        </th>
        <th colspan=2>
            <p align=center>週五 <?php echo $this->_tpl_vars['day'][5]; ?>
</p>
        </th>
        <th colspan=2>
            <p align=center>週六 <?php echo $this->_tpl_vars['day'][6]; ?>
</p>
        </th>
    </tr>
    <form id="form1" action="reservationMeeting.php" method="post">
    <?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['loop'] = is_array($_loop=24) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
$this->_sections['foo']['step'] = 1;
$this->_sections['foo']['start'] = $this->_sections['foo']['step'] > 0 ? 0 : $this->_sections['foo']['loop']-1;
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = $this->_sections['foo']['loop'];
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
    <tr>
        <td rowspan="2" style="vertical-align:middle;border-right:1px #6699CC dashed">
            <?php if ($this->_sections['foo']['iteration'] == 1 || $this->_sections['foo']['iteration'] == 13): ?>
                <div align="center" valign="middle">12</div>
            <?php elseif ($this->_sections['foo']['iteration'] > 13): ?>
                <div align="center" valign="middle"><?php echo $this->_sections['foo']['iteration']-13; ?>
</div>
            <?php else: ?>
                <div align="center" valign="middle"><?php echo $this->_sections['foo']['iteration']-1; ?>
</div>
            <?php endif; ?>
        </td>
        <td style="border-right:1px #6699CC sloid">
            <?php if ($this->_sections['foo']['iteration'] == 1): ?>
                <div align=center>上午</div>
            <?php elseif ($this->_sections['foo']['iteration'] == 13): ?>
                <div align=center>下午</div>
            <?php else: ?>
                <div align=center>00</div>
            <?php endif; ?>
        </td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+1",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2-1)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+49",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+47)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+97",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+95)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+145",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+143)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+193",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+191)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+241",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+239)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+289",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+287)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
    </tr>
    <tr>
        <td></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+2",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+50",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+48)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+98",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+96)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+146",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+144)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+194",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+192)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+242",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+240)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
        <td><input value="<?php echo smarty_function_math(array('equation' => "(x-1)*2+290",'x' => $this->_sections['foo']['iteration']), $this);?>
" type="checkbox" name="checkedTimeSlot[]"></td>
        <td><?php $this->assign('tempIndex', ($this->_sections['foo']['iteration']*2+288)); ?><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&s=<?php echo $this->_tpl_vars['tempIndex']; ?>
' TARGET="_blank"><?php echo $this->_tpl_vars['meetingList'][$this->_tpl_vars['tempIndex']]; ?>
</a></td>
    </tr>
    <?php endfor; endif; ?>
    <tr>
        <th colspan=2>
            <p align=center><a href='listWeekReservationMmc.php?y=<?php echo $this->_tpl_vars['year'][$this->_tpl_vars['week']]; ?>
&m=<?php echo $this->_tpl_vars['month'][$this->_tpl_vars['week']]; ?>
&d=<?php echo $this->_tpl_vars['day'][$this->_tpl_vars['week']]; ?>
'>重新整理</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&w=0' TARGET="_blank">週日 <?php echo $this->_tpl_vars['day'][0]; ?>
</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&w=1' TARGET="_blank">週一 <?php echo $this->_tpl_vars['day'][1]; ?>
</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&w=2' TARGET="_blank">週二 <?php echo $this->_tpl_vars['day'][2]; ?>
</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&w=3' TARGET="_blank">週三 <?php echo $this->_tpl_vars['day'][3]; ?>
</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&w=4' TARGET="_blank">週四 <?php echo $this->_tpl_vars['day'][4]; ?>
</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&w=5' TARGET="_blank">週五 <?php echo $this->_tpl_vars['day'][5]; ?>
</a></p>
        </th>
        <th colspan=2>
            <p align=center><a href='listHalfhourMeeting.php?y=<?php echo $this->_tpl_vars['year'][0]; ?>
&m=<?php echo $this->_tpl_vars['month'][0]; ?>
&d=<?php echo $this->_tpl_vars['day'][0]; ?>
&w=6' TARGET="_blank">週六 <?php echo $this->_tpl_vars['day'][6]; ?>
</a></p>
        </th>
    </tr>
    <tr><td colspan=16 style="text-align:center">
        <a href='javascript:CancelSelected()'>取消全選</a>　<input type="submit" value="建立會議" class="btn"></td>
    </tr>
    </table>
    <input type="hidden" name="year" value="<?php echo $this->_tpl_vars['year'][0]; ?>
">
    <input type="hidden" name="month" value="<?php echo $this->_tpl_vars['month'][0]; ?>
">
    <input type="hidden" name="day" value="<?php echo $this->_tpl_vars['day'][0]; ?>
">
    </form>

</body>
</html>
