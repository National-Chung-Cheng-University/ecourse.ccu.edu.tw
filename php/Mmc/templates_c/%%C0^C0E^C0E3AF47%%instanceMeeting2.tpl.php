<?php /* Smarty version 2.6.14, created on 2011-02-10 17:51:51
         compiled from /var/www/html/php/Mmc/templates/instanceMeeting2.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>�إߧY�ɷ|ĳ</title>

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
  // ���⦬�M�\\�ள���A�ݭn���ܧ�U�@�檺���Ѩ���
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
<h3 align="center">�إߧY�ɷ|ĳ</h3>
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
             <th colspan=2 bgcolor="#E6FFFC" >�г]�w�Y�ɷ|ĳ���ݩ�:<br>*�������</th>
           </tr>
           <tr align=center>
             <th bgcolor="#F0FFEE">*���D:</th>
             <td bgcolor="#F0FFEE"><input type="text" name="title" size="30" value="�Y�ɷ|ĳ"></td>
           </tr>
           <tr align=center>
             <th bgcolor="#E6FFFC" >�@�P�s���������}:</th>
             <td bgcolor="#E6FFFC" ><input type="text" name="website" size="30"></td>
           </tr>
           <tr align=center>
             <th bgcolor="#F0FFEE">* ���v:</th>
	     <td bgcolor="#F0FFEE">
	        <input type="radio" name="recordType" value="1" checked>�O
	        <input type="radio" name="recordType" value="0">�_  </td>
           </tr>
           <!--
           <tr>
             <th>* �ۥѰQ�׼Ҧ�:</th>
             <td>
             <input type="radio" name="allquestionerType" value="1" >�O
             <input type="radio" name="allquestionerType" value="0" checked>�_  </td>
           </tr>
           -->
          <tr align=center>
            <th bgcolor="#E6FFFC">* ����|ĳ:</th>
            <td bgcolor="#E6FFFC">
               <input type="radio" name="continueType" value="1" >�O
               <input type="radio" name="continueType" value="0" checked >�_
               <div id="choosedRecording">      
               </div>
               <div id="message">
                 <input  type = "button" name="button" value ="������v��">
                 <br>
                 <font >�Y�ݱ���|ĳ�A�п�����v�ɡF�_�h�п��"�_" </font>
               </div>
               <!-- �H�U�\��S����@ --!>
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
               <!-- �H�W�\��S����@ --!>
               <div id="searchingResult">
               </div>
            </td>
          </tr>
          <tr align="center" >
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
