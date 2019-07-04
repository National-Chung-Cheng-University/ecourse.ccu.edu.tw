<?php /* Smarty version 2.6.14, created on 2010-09-20 23:00:45
         compiled from /home/opera/WWW/themes/IE2/mmc/recordingManagement_list.tpl */ ?>
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

    <h1>錄影檔清單</h1>
    <form action="moveRecording.php" method="post">
    <input type="hidden" name="currentFolderId" value="<?php echo $this->_tpl_vars['currentFolderId']; ?>
">
    <input type="hidden" name="currentSeq" value="<?php echo $this->_tpl_vars['currentSeq']; ?>
">
    <input type="submit" name="func" value="移動到" class="btn">
    <select name="moveTofolderId">
    <?php $_from = $this->_tpl_vars['folderList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['folderData']):
?>
       <option value="<?php echo $this->_tpl_vars['folderData']['folderId']; ?>
"><?php echo $this->_tpl_vars['folderData']['folderCaption']; ?>
</option> 
    <?php endforeach; endif; unset($_from); ?>
    </select>

    <?php $_from = $this->_tpl_vars['recordingList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['temp'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['temp']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['meetingData']):
        $this->_foreach['temp']['iteration']++;
?>
      <?php if (($this->_foreach['temp']['iteration'] <= 1)): ?>
        <table class="datatable">
          <tr>
          <td></td>
          <td>編號</td>
          <td>課程名稱</td>
          <td>標題</td>
          <td>會議時間</td>
          <td>會議長度</td>
          <td>發佈/解除</td>
          <td>發佈</td>
          <td>錄影檔操作</td>
          <td>簡報縮圖</td>
          <td>交談記錄</td>
        </tr>
      <?php endif; ?>
      <tr>
          <td><input type="checkbox" name="recordingId[]" value="<?php echo $this->_tpl_vars['meetingData']['recordingId']; ?>
"></td>
          <td><?php echo $this->_tpl_vars['meetingData']['recordingIndex']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['courseName']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['title']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['actualStartTime']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meetingData']['durationString']; ?>
</td>
          <td><?php if ($this->_tpl_vars['meetingData']['isRead'] == 1): ?><a href='<?php echo $this->_tpl_vars['meetingData']['cancelpublishUrl']; ?>
'>解除</a><?php else: ?><a href='<?php echo $this->_tpl_vars['meetingData']['publishUrl']; ?>
'>發佈</a><?php endif; ?></td>
          <td><?php if ($this->_tpl_vars['meetingData']['isRead'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
          <td><a href='<?php echo $this->_tpl_vars['meetingData']['operationUrl']; ?>
'>錄影檔操作</a></td>
          <td><a href='<?php echo $this->_tpl_vars['meetingData']['slidingshowUrl']; ?>
' target=_blank>簡報縮圖</a></td>
          <td><a href='<?php echo $this->_tpl_vars['meetingData']['chatUrl']; ?>
' target=_blank>交談文字</a></td>
      </tr>
      <?php if (($this->_foreach['temp']['iteration'] == $this->_foreach['temp']['total'])): ?>
        </table>
      <?php endif; ?>

    <?php endforeach; else: ?>
       <br>此資料夾無任何錄影檔
    <?php endif; unset($_from); ?>


</body>
</html>
