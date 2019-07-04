<?php /* Smarty version 2.6.14, created on 2011-01-24 18:08:26
         compiled from /home/opera/WWW/themes/IE2/mmc/reservationMeeting.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>建立預約會議</title>


<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/content.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/table.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webroot']; ?>
script/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webroot']; ?>
script/jquery-ui-1.7.2.custom.min.js"></script>
<?php echo '

<script language="JavaScript">

$(document).ready(function(){
   $("#message").hide();
   $("#searching").hide();
   $("#searchingResult").hide();
   $("#choosedRecording").hide();
   //$("#message1").hide();
   var rightNow = new Date();
   DateSetting(rightNow);
   $("input[name=\'continueType\']").click(function(){check($(this).attr(\'value\'));});
   $("input[name=\'button\']").click(function(){ChooseRecording();});

});


// 設定現在時間+15分鐘為預設值
function DateSetting(datetime) {

  var hour = datetime.getHours() ;
  var minutes = datetime.getMinutes() ;
  var month = datetime.getMonth() +1 ;
  var day = datetime.getDate() ;
  var year = datetime.getFullYear() ;
  // 預約到隔天

    
  if (hour == 23 && minutes > 40) {
    day++;
    if(month == 2) {
        // 閏年
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
     $("select[name=\'morring\']").val(1).attr("checked", "true");
  }
  else if (hour < 10 ) {
    hour = "0"+hour;
    $("select[name=\'morring\']").val(1).attr("checked", "true");
  }
  else if (hour == 11) {
    $("select[name=\'morring\']").val(1).attr("checked", "true");
  }
  else if (hour == 12) {
    $("select[name=\'morring\']").val(0).attr("checked", "true");
  }
  else {
    hour = hour - 12 ;
    if (hour < 10)
        hour = "0"+hour;
    else
        ;
    $("select[name=\'morring\']").val(0).attr("checked", "true");
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
  // 先把收尋功能拿掉，需要的話把下一行的註解取消，收尋功能還沒做
  // $("#searching").show();
  $("#searchingResult").show();
  $.ajax({
    url: \'searchingMeeting.php\',
    cache: false,
    error: function(xhr) {
      alert(\'Ajax request 發生錯誤\');
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

    $("#choosedRecording").html("<table border=\\"2\\" bordercolor=\\"red\\"><tr><th>選取的錄影檔：</th><td>"+meetingTitle+meetingTime+meetingDuration+"</td</tr></table>");
    $("#choosedRecording").show();
    $("#searchingResult").hide();
  }
  else
    alert("未選取錄影檔!!");
}

</script>

'; ?>



</head>

<body>
<h1>建立預約會議</h1>
<form action="createReservationMeeting.php" method="post">
  <table class="datatable">
  <tr>
   <th colspan="2">請設定預約會議的屬性:</th>
  </tr>
  <tr>
    <th colspan="2">*必填欄位</th>
  </tr>
  <tr>
    <th>* 接續會議:</th>
    <td>
        <input type="radio" name="continueType" value="1" >是
        <input type="radio" name="continueType" value="0" checked >否
        <div id="choosedRecording">
        </div>
        <div id="message">
        <input  type = "button" name="button" value ="選取錄影檔">
        <br>
        <font >若需接續會議，請選取錄影檔；否則請選取"否" </font>
        </div>
        <!-- 先寫空的之後要可以將課名創立時間預先寫入 -->
        <div id="searching">
        <table class="searchdata">
        <caption style="font-size:14px">查詢錄影檔</caption>
        <tr>
          <th colspan="2">參加者可用姓名或電子郵址進行查詢。</th>
        </tr>
        <tr>
          <th>*標題:</th>
          <td><input type="text" name="searchtitle" size="30"></td>
        </tr>
        </table>
        </div>
        <div id="searchingResult">
        </div>
    </td>
  </tr>
  <tr>
      <th>*標題:</th>
      <td><input type="text" name="title" size="30" value="<?php echo $this->_tpl_vars['temptitle']; ?>
"></td>
  </tr>
  <tr>
        <th>*開始時間:</th>
        <!--先把日期寫死，有空的話在補把過去的時間濾掉的功能，跟年的時間補上 -->
        <td><select name="startTimeMonth" >
                <option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option>
                <option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option>
                <option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
            </select>月
            <select name="startTimeDay" >
                <option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option>
                <option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option>
                <option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                <option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option>
                <option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
                <option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
                <option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option>
                <option value="29">29</option><option value="30">30</option><option value="31">31</option>
            </select>日
            <select name="startTimeYear" >
                <option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option>
                <option value="2013">2013</option><option value="2014">2014</option><option value="2015">2015</option>
                <option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option>
                <option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option>
            </select>年
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
            <input type="radio" name="morring" value="1" >上午
            <input type="radio" name="morring" value="0" checked>下午             
  </tr>
        </td>
  </tr>
  <tr>
      <th>*時間長度:</th>
      <td>
          <select name="duration" >
                <option value="900">15分鐘</option><option value="1800" selected>30分鐘</option>
                <option value="2700">45分鐘</option><option value="3600">1小時</option>
                <option value="4500">1小時15分鐘</option><option value="5400">1小時30分鐘</option>
                <option value="6300">1小時45分鐘</option><option value="7200">2小時</option>
                <option value="8100">2小時15分鐘</option><option value="9000">2小時30分鐘</option>
                <option value="9900">3小時45分鐘</option><option value="10800">3小時</option>
          </select>
      </td>
  </tr>
  <tr>
    <th>* 錄影:</th>
      <td>
          <input type="radio" name="recordType" value="1" checked>是
          <input type="radio" name="recordType" value="0">否  </td>
  </tr>
  <tr>
      <!-- 之後要可以將預設值設定完成-->
      <th>*連線數:</th>
      <td>
          預設連線數為全班學生數 + 老師 + 5<br>
          <input type="text" name="connectionCount" size="5" value="<?php echo $this->_tpl_vars['tempconnectionCount']; ?>
">
      </td>
  </tr>
  <tr>
      <th>議程/留言:</th>
      <td>
          <textarea name="agenda" rows="8" cols="40"></textarea>
      </td>
  </tr>
  <tr>
      <th>寄信邀請:</th>
      <td>
          <input type="checkbox" name="sendMail" value="1" checked>全班(會發送 email 通知本課程全班同學)
      </td>
  </tr>
 
  </table>
  <p align="center">
  <input type="submit" value="預約會議" class="btn">
  <input type="reset" value="取消" class="btn">
  </p>
</form>
</body>
</html>
