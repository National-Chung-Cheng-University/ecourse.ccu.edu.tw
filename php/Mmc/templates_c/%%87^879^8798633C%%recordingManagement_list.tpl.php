<?php /* Smarty version 2.6.14, created on 2011-02-21 17:53:22
         compiled from /usr/local/apache/htdocs/php/Mmc/templates/recordingManagement_list.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>���v�ɲM��</title>

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

</head>

<body>

    <h3 align="center" >���v�ɲM��</h3>

    <font >�w�ϥΪŶ��G<?php echo $this->_tpl_vars['totalused']; ?>
MB�@�@�@�`�@�i�ΪŶ�:<?php echo $this->_tpl_vars['totalquota']; ?>
MB �@�@�ϥζq:<?php echo $this->_tpl_vars['used']; ?>
%</font><br>
    <?php if ($this->_tpl_vars['used'] >= 95): ?>
        <font color="red">�i�ΪŶ��Y�N����</font><br>
    <?php endif; ?>

    <form action="moveRecording.php" method="post">
    <input type="hidden" name="currentFolderId" value="<?php echo $this->_tpl_vars['currentFolderId']; ?>
">
    <input type="hidden" name="currentSeq" value="<?php echo $this->_tpl_vars['currentSeq']; ?>
">
    <input type="submit" name="func" value="���ʨ�" class="btn">
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
        <table class="datatable" border=1>
          <tr>
          <td bgcolor="#E6FFFC"></td>
          <td bgcolor="#E6FFFC">�s��</td>
          <td bgcolor="#E6FFFC">�Ұ�W��</td>
          <td bgcolor="#E6FFFC">���D</td>
          <td bgcolor="#E6FFFC">�|ĳ�ɶ�</td>
          <td bgcolor="#E6FFFC">�|ĳ����</td>
          <td bgcolor="#E6FFFC">�o�G/�Ѱ�</td>
          <td bgcolor="#E6FFFC">�o�G</td>
          <td bgcolor="#E6FFFC">���v�ɾާ@</td>
          <td bgcolor="#E6FFFC">²���Y��</td>
          <td bgcolor="#E6FFFC">��ͰO��</td>
        </tr>
      <?php endif; ?>
      <tr>
          <td bgcolor="#F0FFEE"><input type="checkbox" name="recordingId[]" value="<?php echo $this->_tpl_vars['meetingData']['recordingId']; ?>
"></td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['recordingIndex']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['courseName']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['title']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['actualStartTime']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['durationString']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php if ($this->_tpl_vars['meetingData']['isRead'] == 1): ?><a href='<?php echo $this->_tpl_vars['meetingData']['cancelpublishUrl']; ?>
'>�Ѱ�</a><?php else: ?><a href='<?php echo $this->_tpl_vars['meetingData']['publishUrl']; ?>
'>�o�G</a><?php endif; ?></td>
          <td bgcolor="#F0FFEE"><?php if ($this->_tpl_vars['meetingData']['isRead'] == 1): ?>�O<?php else: ?>�_<?php endif; ?></td>
          <td bgcolor="#F0FFEE"><a href='<?php echo $this->_tpl_vars['meetingData']['operationUrl']; ?>
'>���v�ɾާ@</a></td>
          <td bgcolor="#F0FFEE"><a href='<?php echo $this->_tpl_vars['meetingData']['slidingshowUrl']; ?>
' target=_blank>²���Y��</a></td>
          <td bgcolor="#F0FFEE"><a href='<?php echo $this->_tpl_vars['meetingData']['chatUrl']; ?>
' target=_blank>��ͤ�r</a></td>
      </tr>
      <?php if (($this->_foreach['temp']['iteration'] == $this->_foreach['temp']['total'])): ?>
        </table>
      <?php endif; ?>

    <?php endforeach; else: ?>
       <br>����Ƨ��L������v��
    <?php endif; unset($_from); ?>


</body>
</html>

