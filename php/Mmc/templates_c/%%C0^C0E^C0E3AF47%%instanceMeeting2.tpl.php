<?php /* Smarty version 2.6.14, created on 2011-02-10 17:51:51
         compiled from /var/www/html/php/Mmc/templates/instanceMeeting2.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>建立即時會議</title>

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
  // 先把收尋功\能拿掉，需要的話把下一行的註解取消
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
<h3 align="center">建立即時會議</h3>
<form action="gotoinstanceMeeting.php" method="post">
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
             <th colspan=2 bgcolor="#E6FFFC" >請設定即時會議的屬性:<br>*必填欄位</th>
           </tr>
           <tr align=center>
             <th bgcolor="#F0FFEE">*標題:</th>
             <td bgcolor="#F0FFEE"><input type="text" name="title" size="30" value="即時會議"></td>
           </tr>
           <tr align=center>
             <th bgcolor="#E6FFFC" >共同瀏覽網頁網址:</th>
             <td bgcolor="#E6FFFC" ><input type="text" name="website" size="30"></td>
           </tr>
           <tr align=center>
             <th bgcolor="#F0FFEE">* 錄影:</th>
	     <td bgcolor="#F0FFEE">
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
          <tr align=center>
            <th bgcolor="#E6FFFC">* 接續會議:</th>
            <td bgcolor="#E6FFFC">
               <input type="radio" name="continueType" value="1" >是
               <input type="radio" name="continueType" value="0" checked >否
               <div id="choosedRecording">      
               </div>
               <div id="message">
                 <input  type = "button" name="button" value ="選取錄影檔">
                 <br>
                 <font >若需接續會議，請選取錄影檔；否則請選取"否" </font>
               </div>
               <!-- 以下功能沒有實作 --!>
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
               <!-- 以上功能沒有實作 --!>
               <div id="searchingResult">
               </div>
            </td>
          </tr>
          <tr align="center" >
            <th colspan=2 bgcolor="#F0FFEE">
              <input type="submit" value="啟動" class="btn">
              <input type="reset" value="取消" class="btn">
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
