<?php /* Smarty version 2.6.14, created on 2013-02-09 06:39:40
         compiled from /datacenter/htdocs/php/Mmc/templates/reservationMeeting.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>�إ߹w���|ĳ</title>

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

<script language="JavaScript">
var stamp = <?php echo $this->_tpl_vars['stamp']; ?>
;
<?php echo '
$(document).ready(function(){
   $("#message").hide();
   $("#searching").hide();
   $("#searchingResult").hide();
   $("#choosedRecording").hide();
   //$("#message1").hide();
   var rightNow = new Date(stamp*1000);
   DateSetting(rightNow);
   $("input[name=\'continueType\']").click(function(){check($(this).attr(\'value\'));});
   $("input[name=\'button\']").click(function(){ChooseRecording();});

});


// �]�w�{�b�ɶ�+15�������w�]��
function DateSetting(datetime) {

  var hour = datetime.getHours() ;
  var minutes = datetime.getMinutes() ;
  var month = datetime.getMonth() +1 ;
  var day = datetime.getDate() ;
  var year = datetime.getFullYear() ;
  // �w����j��

    
  if (hour == 23 && minutes > 40) {
    day++;
    if(month == 2) {
        // �|�~
        if (year%4 == 0 && (year%100 != 0 || (year%100 == 0 & year%400 == 0))) {
            if(day >29) {
                month++;
                day = 1 ;
            }
            else
                ;
        }
        else {
            if(day >28) {
                month++;
                day = 1 ;
            }
            else
                ;            
        }
    }
    else if (month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10  || month == 12) {
            if(day >31) {
                month++;
                day = 1 ;
            }
            else
                ;

    }
    else {
            if(day >30) {
                month++;
                day = 1 ;
            }
            else
                ;

    }
    
    if (month > 12 ) {
        year++;
        month = 1 ;
    }
    else
        ;
    hour = 0;
    minutes = 10;
  }
  else if (minutes > 40) {
    hour++;
    minutes = 10;
  }
  else {
    minutes = minutes + (5-(minutes%5)) +10;
  }
  
  if(month < 10 )
    month = "0"+month;
  $("select[name=\'startTimeMonth\']").val(month).attr("selected", "true");

  if (day < 10 )
    day = "0"+day;
  $("select[name=\'startTimeDay\']").val(day).attr("selected", "true");

  $("select[name=\'startTimeYear\']").val(year).attr("selected", "true");


  if (hour == 0) {
     hour = 12 ;
     $("input[name=\'morring\']").get(0).checked = true;
  }
  else if (hour < 10 ) {
    hour = "0"+hour;
    $("input[name=\'morring\']").get(0).checked = true;
  }
  else if (hour == 10) {
    $("input[name=\'morring\']").get(0).checked = true;
  }
  else if (hour == 11) {
    $("input[name=\'morring\']").get(0).checked = true;
  }
  else if (hour == 12) {
    $("input[name=\'morring\']").get(1).checked = true;
  }
  else {
    hour = hour - 12 ;
    if (hour < 10)
        hour = "0"+hour;
    else
        ;
    $("input[name=\'morring\']").get(1).checked = true;
  }
  
  
  $("select[name=\'startTimeHour\']").val(hour).attr("selected", "true");

  if (minutes < 10 )
    minutes = "0"+minutes;
  $("select[name=\'startTimeMinutes\']").val(minutes).attr("selected", "true");  
  
}

function check(show)
{
  if(show == 1)
    $(\'#message\').show();
  else {
    $("#message").hide();
    $("#searching").hide();
    $("#searchingResult").hide();
    $("#choosedRecording").hide();
  }
}

function ChooseRecording()
{
  // ���⦬�M�\\�ள���A�ݭn���ܧ�U�@�檺���Ѩ����A���M�\\���٨S��
  // $("#searching").show();
  $("#searchingResult").show();
  $.ajax({
    url: \'searchingMeeting.php\',
    cache: false,
    error: function(xhr) {
      alert(\'Ajax request �o�Ϳ��~\');
      $(e.target).attr(\'disabled\', false);
    },
    success: function(response) {
      $(\'#searchingResult\').html(response);
      $("input[name=\'continueDecided\']").click(function(){DecideRecording();});
    }
  });
}

function DecideRecording()
{
  var meetingId = $("input[name=\'continueMeeting\']:checked").attr(\'value\');

  if (meetingId != null) {
    var meetingTitle = $("td[name=\'title"+meetingId+"\']").text()+"<br>";
    var meetingTime = $("td[name=\'time"+meetingId+"\']").text()+"<br>";
    var meetingDuration = $("td[name=\'duration"+meetingId+"\']").text();

    $("#choosedRecording").html("<table border=\\"2\\" bordercolor=\\"red\\"><tr><th>��������v�ɡG</th><td>"+meetingTitle+meetingTime+meetingDuration+"</td</tr></table>");
    $("#choosedRecording").show();
    $("#searchingResult").hide();
  }
  else
    alert("��������v��!!");
}

</script>

'; ?>



</head>

<body>
<h3 align="center" >�إ߹w���|ĳ</h3>
<form action="createReservationMeeting.php" method="post">
  <table border=0 align="center" cellpadding="0" cellspacing="0" width="50%">
    <tr>
      <td>
         <div align="right"><img src="/images/skin1/bor/bor_01.GIF" width="12" height="11"></div>
      </td>
      <td>
         <div align="center"><img src="/images/skin1/bor/bor_02.GIF" width="100%" height="11"></div>
      </td>
      <td>
         <div align="left"><img src="/images/skin1/bor/bor_03.GIF" width="17" height="11"></div>
      </td>
    </tr>
    <tr>
      <td background="/images/skin1/bor/bor_04.GIF" width="12">
      </td>
      <td bgcolor="#CCCCCC" height="100%">  
        <table cellpadding=2 align=center border=0 bordercolorlight="#666666" bordercolordark="#FFFFFF" width="100%" cellspacing="1">
          <tr align=center>
             <th colspan="2" bgcolor="#E6FFFC">�г]�w�w���|ĳ���ݩ�:<br>*�������</th>
          </tr>
             <tr align=center>
             <th bgcolor="#F0FFEE">* ����|ĳ:</th>
             <td bgcolor="#F0FFEE">
               <input type="radio" name="continueType" value="1" >�O
               <input type="radio" name="continueType" value="0" checked >�_
               <div id="choosedRecording">
               </div>
               <div id="message">
                 <input  type = "button" name="button" value ="������v��">
                 <br>
                 <font >�Y�ݱ���|ĳ�A�п�����v�ɡF�_�h�п��"�_" </font>
               </div>
               <!-- ���g�Ū�����n�i�H�N�ҦW�Х߮ɶ��w���g�J -->
               <div id="searching">
                 <table class="searchdata">
                   <caption style="font-size:14px">�d�߿��v��</caption>
                   <tr>
                     <th colspan="2">�ѥ[�̥i�Ωm�W�ιq�l�l�}�i��d�ߡC</th>
                   </tr>
                   <tr>
                     <th>*���D:</th>
                     <td><input type="text" name="searchtitle" size="30"></td>
                   </tr>
                 </table>
               </div>
               <div id="searchingResult">
               </div>
             </td>
          </tr>
          <tr align=center>
             <th bgcolor="#E6FFFC">*���D:</th>
             <td bgcolor="#E6FFFC"><input type="text" name="title" size="30" value="<?php echo $this->_tpl_vars['temptitle']; ?>
"></td>
          </tr>
          <tr align=center>
             <th bgcolor="#F0FFEE">*�}�l�ɶ�:</th>
             <!--�������g���A���Ū��ܦb�ɧ�L�h���ɶ��o�����\��A��~���ɶ��ɤW -->
             <td bgcolor="#F0FFEE"><select name="startTimeMonth" >
                <option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option>
                <option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option>
                <option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                </select>��
                <select name="startTimeDay" >
                   <option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option>
                   <option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option>
                   <option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                   <option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option>
                   <option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
                   <option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
                   <option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option>
                   <option value="29">29</option><option value="30">30</option><option value="31">31</option>
               </select>��
               <select name="startTimeYear" >
                   <option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option>
                   <option value="2013">2013</option><option value="2014">2014</option><option value="2015">2015</option>
                   <option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option>
                   <option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option>
               </select>�~
               <br>
               <select name="startTimeHour" >
                   <option value="12">12</option><option value="01">01</option><option value="02">02</option><option value="03">03</option>
                   <option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option>
                   <option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option>
               </select> : 
               <select name="startTimeMinutes" >
                   <option value="00">00</option><option value="05">05</option><option value="10">10</option><option value="15">15</option>
                   <option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option>
                   <option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option>
               </select>             
               <input type="radio" name="morring" value="1" >�W��
               <input type="radio" name="morring" value="0" checked>�U��
	       <br><a href="listWeekReservationMmc.php">�Ҫ�Ҧ�</a>             
             </td>
           </tr>
           <tr align=center>
             <th bgcolor="#E6FFFC">*�ɶ�����:</th>
             <td bgcolor="#E6FFFC">
               <select name="duration" >
                  <option value="900">15����</option><option value="1800" <?php if ($this->_tpl_vars['duration'] == 1800): ?>selected<?php endif; ?>>30����</option>
                  <option value="2700">45����</option><option value="3600" <?php if ($this->_tpl_vars['duration'] == 3600): ?>selected<?php endif; ?>>1�p��</option>
                  <option value="4500">1�p��15����</option><option value="5400" <?php if ($this->_tpl_vars['duration'] == 5400): ?>selected<?php endif; ?>>1�p��30����</option>
                  <option value="6300">1�p��45����</option><option value="7200" <?php if ($this->_tpl_vars['duration'] == 7200): ?>selected<?php endif; ?>>2�p��</option>
                  <option value="8100">2�p��15����</option><option value="9000" <?php if ($this->_tpl_vars['duration'] == 9000): ?>selected<?php endif; ?>>2�p��30����</option>
                  <option value="9900">3�p��45����</option><option value="10800" <?php if ($this->_tpl_vars['duration'] == 10800): ?>selected<?php endif; ?>>3�p��</option>
               </select>
            </td>
          </tr>
          <tr align=center>
            <th bgcolor="#F0FFEE">* ���v:</th>
            <td bgcolor="#F0FFEE">
              <input type="radio" name="recordType" value="1" checked>�O
              <input type="radio" name="recordType" value="0">�_  </td>
          </tr>
          <tr align=center>
            <!-- ����n�i�H�N�w�]�ȳ]�w����-->
            <th bgcolor="#E6FFFC">*�s�u��:</th>
            <td bgcolor="#E6FFFC">
              �w�]�s�u�Ƭ����Z�ǥͼ� + �Ѯv + 5<br>
              <input type="text" name="connectionCount" size="5" value="<?php echo $this->_tpl_vars['tempconnectionCount']; ?>
">
            </td>
         </tr>
         <tr align=center>
          <th bgcolor="#F0FFEE">ĳ�{/�d��:</th>
          <td bgcolor="#F0FFEE">
              <textarea name="agenda" rows="8" cols="40"></textarea>
          </td>
        </tr>
        <tr align=center>
          <th bgcolor="#E6FFFC">�H�H�ܽ�:</th>
          <td bgcolor="#E6FFFC">
              <input type="checkbox" name="sendMail" value="1" checked>���Z(�|�o�e email �q�����ҵ{���Z�P��)
          </td>
        </tr>
        <tr align=center>
           <th colspan=2 bgcolor="#F0FFEE">
              <input type="submit" value="�Ұ�" class="btn">
              <input type="reset" value="����" class="btn">
           </th>
        </tr>
      </table>
    </td>
    <td background="/images/skin1/bor/bor_06.GIF" width="17">

    </td>
  </tr>
  <tr>
     <td>
         <div align="right"><img src="/images/skin1/bor/bor_07.GIF" width="12" height="17"></div>
      </td>
      <td>
         <div align="center"><img src="/images/skin1/bor/bor_08.GIF" width="100%" height="17"></div>
      </td>
      <td>
         <div align="left"><img src="/images/skin1/bor/bor_09.GIF" width="17" height="17"></div>
      </td>
  </tr>
</table>

</form>
</body>
</html>

