<?php /* Smarty version 2.6.14, created on 2011-01-05 17:00:48
         compiled from /home/opera/WWW/themes/IE2/mmc/instanceMeeting.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>建立即時會議</title>


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
   $("input[name=\'continueType\']").click(function(){check($(this).attr(\'value\'));});
   $("input[name=\'button\']").click(function(){ChooseRecording();});

});

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
  // 先把收尋功能拿掉，需要的話把下一行的註解取消
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
<h1>建立即時會議</h1>
<form action="gotoinstanceMeeting.php" method="post">
  <table class="datatable">
  <tr>
   <th colspan="2">請設定即時會議的屬性:</th>
  </tr>
  <tr>
    <th colspan="2">*必填欄位</th>
  </tr>
  <tr>
    <th>*標題:</th>
	  <td><input type="text" name="title" size="30" value="即時會議"></td>
  </tr>
  <tr>
      <th>共同瀏覽網頁網址:</th>
      <td><input type="text" name="website" size="30"></td>
  </tr>
  <tr>
    <th>* 錄影:</th>
	  <td>
	      <input type="radio" name="recordType" value="1" checked>是
	      <input type="radio" name="recordType" value="0">否  </td>
  </tr>
  <!--
  <tr>
      <th>* 自由討論模式:</th>
      <td>
          <input type="radio" name="allquestionerType" value="1" >是
          <input type="radio" name="allquestionerType" value="0" checked>否  </td>
  </tr>
  -->
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

  </table>
  <p class="al-left">  
  <input type="submit" value="啟動" class="btn">
  <input type="reset" value="取消" class="btn">
  </p>
</form>
</body>
</html>