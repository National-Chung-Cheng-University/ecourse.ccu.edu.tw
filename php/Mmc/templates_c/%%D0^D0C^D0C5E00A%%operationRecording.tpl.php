<?php /* Smarty version 2.6.14, created on 2011-02-19 18:13:50
         compiled from /usr/local/apache/htdocs/php/Mmc/templates/operationRecording.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>錄影檔資訊</title>

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
$("input[value=\'刪除\']").click(function(){DecideDelete();});

});

function DecideDelete(buttonType)
{
   if($("#published").attr(\'value\') != 0) {
        alert("此錄影檔正在發佈中，請先取消發佈才可刪除此錄影檔");
        $("input[value=\'刪除\']").attr(\'value\', "取消");
   }
   else
      ;

}


</script>
'; ?>



</head>

<body>
    <div id="published" value="<?php echo $this->_tpl_vars['meetingInfo']['isRead']; ?>
"></div>
    <form id="operation" action="operationRecording_proc.php" method="post">
    <h3 align=center>錄影檔資訊</h3>
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
              <td bgcolor="#E6FFFC">標題</td>
              <td bgcolor="#E6FFFC"><?php echo $this->_tpl_vars['meetingInfo']['title']; ?>
</td>
            </tr>
            <tr align=center>
              <td bgcolor="#F0FFEE">課堂名稱</td>
              <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingInfo']['courseName']; ?>
</td>
            </tr>
            <tr align=center>
              <td bgcolor="#E6FFFC">主持人</td>
              <td bgcolor="#E6FFFC"><?php echo $this->_tpl_vars['meetingInfo']['coordinatorName']; ?>
   ( <?php echo $this->_tpl_vars['meetingInfo']['coordinatorEmail']; ?>
 )</td>
            </tr>
            <tr align=center>
              <td bgcolor="#F0FFEE">實際時間</td>
              <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingInfo']['actualTime']; ?>
</td>
           </tr>
           <tr align=center>
              <td bgcolor="#E6FFFC">時間長度</td>
              <td bgcolor="#E6FFFC"><?php echo $this->_tpl_vars['meetingInfo']['durationString']; ?>
</td>
           </tr>
           <tr align=center>
              <td bgcolor="#F0FFEE">縮圖與文字</td>
              <td bgcolor="#F0FFEE"><a href='<?php echo $this->_tpl_vars['meetingInfo']['slidingshowUrl']; ?>
' target=_blank>簡報縮圖</a>  <a href='<?php echo $this->_tpl_vars['meetingInfo']['chatUrl']; ?>
' target=_blank>文字記錄</a></td>
           </tr>
	   <tr align=center>
	     <td colspan=2 bgcolor="#E6FFFC">
               <input type="submit" name="func" value="播放" class="btn">
               <input type="submit" name="func" value="下載" class="btn">
               <input type="submit" name="func" value="刪除" class="btn">
   	       <input type="submit" name="func" value="返回" class="btn">
	     </td>
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

    <input type="hidden" name="meetingId" value="<?php echo $this->_tpl_vars['meetingInfo']['meetingId']; ?>
">
    <input type="hidden" name="rid" value="<?php echo $this->_tpl_vars['meetingInfo']['fid']; ?>
">
    <input type="hidden" name="seq" value="<?php echo $this->_tpl_vars['meetingInfo']['seq']; ?>
">
    </form>
   


</body>
</html>

