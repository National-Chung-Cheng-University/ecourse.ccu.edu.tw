<?php /* Smarty version 2.6.14, created on 2011-03-03 10:41:02
         compiled from /usr/local/apache/htdocs/php/Mmc/templates/listYearMeeting.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>�w���|ĳ�M��</title>

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

    <h3 align="center">�w���|ĳ�M��</h3>
    <table border=1 align="center">
    <tr>
        <td bgcolor="#E6FFFC">�Ұ�W��</td>
        <td bgcolor="#E6FFFC">�½ұЮv</td>
        <td bgcolor="#E6FFFC">���D</td>
        <td bgcolor="#E6FFFC">�w������ɶ�</td>
        <td bgcolor="#E6FFFC">�|ĳ�̤j�H��</td>
        <td bgcolor="#E6FFFC">���v</td>
        <td bgcolor="#E6FFFC">�ǳƼҦ�</td>
        <td bgcolor="#E6FFFC">�����|ĳ</td>
    </tr>

    <?php $_from = $this->_tpl_vars['meetingList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['meetingData']):
?>
      <tr>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['courseName']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['teacherName']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['title']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['startTime']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php echo $this->_tpl_vars['meetingData']['maxNumAttendee']; ?>
</td>
          <td bgcolor="#F0FFEE"><?php if ($this->_tpl_vars['meetingData']['recording'] == 1): ?>�O<?php else: ?>�_<?php endif; ?></td>
          <td bgcolor="#F0FFEE"><a href='<?php echo $this->_tpl_vars['meetingData']['preparaMeeting']; ?>
'>�ǳ�</a></td>
          <td bgcolor="#F0FFEE"><a href='<?php echo $this->_tpl_vars['meetingData']['cancelMeeting']; ?>
'>����</a></td>
      </tr>
    <?php endforeach; endif; unset($_from); ?>
    </table>
    <caption style="font-size:14px">
    <p align="center">
    �|ĳ�w���ɶ��٨S����A�z�]�i�H��ܥ��i�J"�ǳƼҦ�"�A<br>
    �b"�ǳƼҦ�"���A�z�i�H���W�Ǥ@�ǱЧ����|ĳ�e���ǳơA��L�H���|�i�ӥ��Z�z�C<br>
    ���|ĳ�ɶ��@��A����"�ǳƼҦ�"�A���s�i�J�����줽�ǡA�|ĳ���䥦�ѻP�̴N�i�H�i�ӤF�C<br>
    <p align="center"><font color="red">�ݭn�`�N���O�A�b"�ǳƼҦ�"���A�Y��F�|ĳ�w���ɶ��A�@�w�n�O�o���s�i�J�����줽��<br>
    �_�h��L�|ĳ�ѻP�̵L�k�i�J�A���w���|ĳ�C</font><br>


</body>
</html>
