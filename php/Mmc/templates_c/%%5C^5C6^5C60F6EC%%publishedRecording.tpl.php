<?php /* Smarty version 2.6.14, created on 2010-09-20 23:46:21
         compiled from /home/opera/WWW/themes/IE2/mmc/publishedRecording.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>錄影檔資訊</title>


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

</head>

<body>

    <form action="playmodeMeeting.php" method="post">
    <h1>錄影檔資料</h1>
    <table class="datatable">
    <tr>
        <td>標題</td>
        <td><?php echo $this->_tpl_vars['meetingInfo']['title']; ?>
</td>
    </tr>
    <tr>
        <td>課程名稱</td>
        <td><?php echo $this->_tpl_vars['meetingInfo']['courseName']; ?>
</td>
    </tr>
    <tr>
        <td>主持人</td>
        <td><?php echo $this->_tpl_vars['meetingInfo']['coordinatorName']; ?>
   ( <?php echo $this->_tpl_vars['meetingInfo']['coordinatorEmail']; ?>
 )</td>
    </tr>
    <tr>
        <td>公佈日期</td>
        <td><?php echo $this->_tpl_vars['meetingInfo']['publishDate']; ?>
</td>
    </tr>
    <tr>
        <td>實際時間</td>
        <td><?php echo $this->_tpl_vars['meetingInfo']['actualTime']; ?>
</td>
    </tr>
    <tr>
        <td>時間長度</td>
        <td><?php echo $this->_tpl_vars['meetingInfo']['durationString']; ?>
</td>
    </tr>
    <tr>
        <td>縮圖與文字</td>
        <td><a href='<?php echo $this->_tpl_vars['meetingInfo']['slidingshowUrl']; ?>
' target=_blank>簡報縮圖</a>  <a href='<?php echo $this->_tpl_vars['meetingInfo']['chatUrl']; ?>
' target=_blank>文字記錄</a></td>
    </tr>
    </table>
    <input type="submit" value="播放" class="btn">
    <input type="hidden" name="meetingId" value="<?php echo $this->_tpl_vars['meetingInfo']['meetingId']; ?>
">
    </form>
   


</body>
</html>
