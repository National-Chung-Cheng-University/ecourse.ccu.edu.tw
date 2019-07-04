<?php /* Smarty version 2.6.14, created on 2010-09-05 20:09:18
         compiled from /home/opera/WWW/themes/IE2/mmc/listTodayMeeting.tpl */ ?>
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

</head>

<body>
    
    <h1>本門課程今日預約會議清單</h1>
    <table class="datatable">
    <tr>
        <td>課程名稱</td>
        <td>授課教師</td>
        <td>標題</td>
        <td>預約日期時間</td>
        <td>會議最大人數</td>
        <td>錄影</td>
        <td>準備模式</td>
        <td>取消會議</td>
    </tr>

    <?php $_from = $this->_tpl_vars['meetingList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['meetingData']):
?>
      <tr>
          <td><?php echo $this->_tpl_vars['meetingData']['courseName']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['teacherName']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['title']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['startTime']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['maxNumAttendee']; ?>
</td>
          <td><?php if ($this->_tpl_vars['meetingData']['recording'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
          <td><a href='<?php echo $this->_tpl_vars['meetingData']['preparaMeeting']; ?>
'>準備</a></td>
          <td><a href='<?php echo $this->_tpl_vars['meetingData']['cancelMeeting']; ?>
'>取消</a></td>
      </tr>
    <?php endforeach; endif; unset($_from); ?>
    </table>
     <caption style="font-size:14px">
    <p>
    今日的會議預約時間還沒有到，您也可以選擇先進入"準備課程模式"，<br>
    在"準備課程模式"中，您可以先上傳一些教材做會議前的準備，其它人不會進來打擾您。<br>
    等會議時間一到，關掉"準備課程模式"再重新進入網路辦公室，會議的其它參與者就可以進來了。<br>
    <p><font color="red">需要注意的是，在"準備課程模式"中，若到了會議預約時間，一定要記得重新進入網路辦公室<br>
    否則其他會議參與者無法進入你的預約會議。</font><br>
    <p>若您只是想開一個即時會議，可以<a href='./instanceMeeting.php'>   按這裡就馬上開一個即時會議</a></p></caption>



</body>
</html>

